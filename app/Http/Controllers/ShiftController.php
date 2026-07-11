<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        return response()->json($shift);
    }

    // Open a new shift
    public function open(Request $request)
    {
        $request->validate([
            'starting_cash' => 'required|numeric|min:0',
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
            'status' => 'open',
            'start_time' => Carbon::now(),
        ]);

        return response()->json($shift, 201);
    }

    // Close the current active shift
    public function close(Request $request)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
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

        // Placeholders for future refund & paid out features
        $cashRefund = 0;
        $cashPaidOut = 0;

        $expectedCash = $shift->starting_cash + $cashSales - $cashRefund - $cashPaidOut;

        $shift->update([
            'end_time' => Carbon::now(),
            'expected_cash' => $expectedCash,
            'actual_cash' => $request->actual_cash,
            'status' => 'closed',
        ]);

        return response()->json($shift);
    }

    // Get shift history
    public function index(Request $request)
    {
        $shifts = Shift::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($shifts);
    }
}
