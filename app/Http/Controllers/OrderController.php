<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product.category']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $perPage = $request->input('per_page', 15);
        return response()->json($query->orderBy('created_at', 'desc')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'payment_method' => 'required|in:cash,qris,card',
            'amount_paid' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'discount_type' => 'nullable|string|in:percentage',
            'discount_value' => 'required|numeric|min:0|max:100',
            'discount_amount' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'service_charge' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'change_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $calculatedSubtotal = 0;
            $orderItemsData = [];

            // Calculate subtotal and prepare order items
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $calculatedSubtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];

                // Deduct stock
                $product->decrement('stock', $item['quantity']);
            }

            // Create Order snapshot
            $order = Order::create([
                'user_id' => $request->user()->id,
                'shift_id' => $validated['shift_id'],
                'subtotal' => $validated['subtotal'],
                'discount_type' => $validated['discount_type'] ?? 'percentage',
                'discount_value' => $validated['discount_value'],
                'discount_amount' => $validated['discount_amount'],
                'tax' => $validated['tax'],
                'service_charge' => $validated['service_charge'],
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'],
                'amount_paid' => $validated['amount_paid'],
                'change_amount' => $validated['change_amount'],
                'status' => 'completed',
            ]);

            // Create Order Items
            $order->orderItems()->createMany($orderItemsData);

            DB::commit();

            return response()->json($order->load('orderItems.product'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show(Order $order)
    {
        return response()->json($order->load(['user', 'orderItems.product']));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        // If order is cancelled, we should probably restock the products
        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            DB::transaction(function () use ($order) {
                foreach ($order->orderItems as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
                $order->update(['status' => 'cancelled']);
            });
        } else {
            $order->update(['status' => $request->status]);
        }

        return response()->json($order);
    }
}
