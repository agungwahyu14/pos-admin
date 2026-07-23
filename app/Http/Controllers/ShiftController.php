<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ShiftController extends Controller
{
    // Check if the current logged-in user has an active shift
    public function current(Request $request)
    {
        $shift = Shift::where('user_id', $request->user()->id)
            ->where('status', 'open')
            ->first();

        if (!$shift) {
            return response()->json(['message' => 'No active shift found.'], 404);
        }

        $shift->load(['orders.orderItems.product.category']);
        $expectedCups = 0;
        $expectedFoods = 0;

        foreach ($shift->orders as $order) {
            if ($order->status !== 'completed') continue;
            foreach ($order->orderItems as $item) {
                $catName = strtolower($item->product->category->name ?? '');
                if ($catName === 'cups' || $catName === 'cup') {
                    $expectedCups += $item->quantity;
                } elseif ($catName === 'food' || $catName === 'foods') {
                    $expectedFoods += $item->quantity;
                }
            }
        }
        
        $shift->expected_cups = $expectedCups;
        $shift->expected_foods = $expectedFoods;

        return response()->json($shift);
    }

    // Open a new shift
    public function open(Request $request)
    {
        $request->validate([
            'starting_cash' => 'required|numeric|min:0',
            'target_cups' => 'nullable|integer',
            'target_foods' => 'nullable|integer',
        ]);

        // Check if there's already an open shift for this user
        $activeShift = Shift::where('user_id', $request->user()->id)
            ->where('status', 'open')
            ->first();

        if ($activeShift) {
            return response()->json(['message' => 'You already have an open shift.'], 400);
        }

        $shift = Shift::create([
            'user_id' => $request->user()->id,
            'starting_cash' => $request->starting_cash,
            'target_cups' => $request->target_cups ?? 30,
            'target_foods' => $request->target_foods ?? 30,
            'status' => 'open',
            'start_time' => Carbon::now(),
        ]);

        Log::info('API: Shift opened', [
            'shift_id' => $shift->id,
            'starting_cash' => $shift->starting_cash,
            'user_id' => $shift->user_id,
            'ip' => $request->ip()
        ]);

        return response()->json($shift, 201);
    }

    // Close the current active shift
    public function close(Request $request)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'actual_qris' => 'required|numeric|min:0',
            'actual_cups' => 'nullable|integer',
            'actual_foods' => 'nullable|integer',
            'close_notes' => 'nullable|string',
        ]);

        $shift = Shift::where('user_id', $request->user()->id)
            ->where('status', 'open')
            ->first();

        if (!$shift) {
            return response()->json(['message' => 'No active shift found to close.'], 404);
        }

        // Calculate expected cash based on completed cash orders
        $cashSales = $shift->orders()
            ->where('status', 'completed')
            ->where('payment_method', 'cash')
            ->sum('total_amount');

        // Calculate expected QRIS based on completed qris orders
        $qrisSales = $shift->orders()
            ->where('status', 'completed')
            ->where('payment_method', 'qris')
            ->sum('total_amount');

        // Placeholders for future refund & paid out features
        $cashRefund = 0;
        $cashPaidOut = 0;

        $expectedCash = $shift->starting_cash + $cashSales - $cashRefund - $cashPaidOut;
        $expectedQris = $qrisSales; // Assuming no starting balance for QRIS

        $shift->update([
            'end_time' => Carbon::now(),
            'expected_cash' => $expectedCash,
            'actual_cash' => $request->actual_cash,
            'expected_qris' => $expectedQris,
            'actual_qris' => $request->actual_qris,
            'actual_cups' => $request->actual_cups ?? 0,
            'actual_foods' => $request->actual_foods ?? 0,
            'close_notes' => $request->close_notes,
            'status' => 'closed',
        ]);

        $cashDiff = $request->actual_cash - $expectedCash;
        $qrisDiff = $request->actual_qris - $expectedQris;

        if ($cashDiff != 0 || $qrisDiff != 0) {
            Log::warning('API: Shift closed with discrepancy', [
                'shift_id' => $shift->id,
                'user_id' => $shift->user_id,
                'cash_difference' => $cashDiff,
                'qris_difference' => $qrisDiff,
                'ip' => $request->ip()
            ]);
        } else {
            Log::info('API: Shift closed successfully', [
                'shift_id' => $shift->id,
                'user_id' => $shift->user_id,
                'ip' => $request->ip()
            ]);
        }

        return response()->json($shift);
    }

    // Get shift history
    public function index(Request $request)
    {
        $query = Shift::with('user')->orderBy('created_at', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        if ($request->has('per_page') && $request->per_page == -1) {
             return response()->json($query->get());
        }

        $shifts = $query->paginate($request->per_page ?? 15);

        return response()->json($shifts);
    }
}
