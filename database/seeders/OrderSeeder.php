<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shift;
use App\Models\User;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Dapatkan Kasir (Petugas)
        $kasir = User::where('role', 'petugas')->first();
        if (!$kasir) {
            $kasir = User::first(); // Fallback if no petugas found
        }

        if (!$kasir) return; // Exit if no users

        // 2. Buat Dummy Shift Historis (Shift Kemarin)
        $shift = Shift::create([
            'user_id' => $kasir->id,
            'start_time' => Carbon::yesterday()->setHour(8)->setMinute(0),
            'end_time' => Carbon::yesterday()->setHour(16)->setMinute(0),
            'starting_cash' => 100000,
            'expected_cash' => 100000, // Will be updated later
            'actual_cash' => 350000,
            'status' => 'closed',
            'created_at' => Carbon::yesterday()->setHour(8)->setMinute(0),
        ]);

        // 3. Ambil beberapa produk yang sudah ada di database
        $products = Product::all();
        if ($products->isEmpty()) return;

        $totalShiftSales = 0;

        // 4. Buat 5 Dummy Orders
        for ($i = 0; $i < 5; $i++) {
            $orderDate = Carbon::yesterday()->setHour(rand(9, 15))->setMinute(rand(0, 59));
            
            // Randomly pick 1 to 3 items for this order
            $numItems = rand(1, 3);
            $selectedProducts = $products->random($numItems);

            $totalAmount = 0;
            $orderItems = [];

            foreach ($selectedProducts as $product) {
                $qty = rand(1, 2);
                $subtotal = $product->price * $qty;
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ];
            }

            // Create Order
            $order = Order::create([
                'user_id' => $kasir->id,
                'shift_id' => $shift->id,
                'total_amount' => $totalAmount,
                'payment_method' => 'cash',
                'amount_paid' => $totalAmount, // Assuming exact change
                'status' => 'completed',
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Save Order Items
            $order->orderItems()->createMany($orderItems);

            $totalShiftSales += $totalAmount;
        }

        // 5. Update expected_cash for the shift based on orders created
        $shift->update([
            'expected_cash' => $shift->starting_cash + $totalShiftSales,
            'actual_cash' => $shift->starting_cash + $totalShiftSales, // Making it balanced
        ]);
        
        // 6. Buat 1 Dummy Order Hari Ini (agar muncul di Dashboard "Penjualan Hari Ini")
        $todayShift = Shift::create([
            'user_id' => $kasir->id,
            'start_time' => Carbon::today()->setHour(8)->setMinute(0),
            'starting_cash' => 150000,
            'status' => 'open',
            'created_at' => Carbon::today()->setHour(8)->setMinute(0),
        ]);

        $todayOrderDate = Carbon::now();
        $randomProduct = $products->random();
        
        $todayOrder = Order::create([
            'user_id' => $kasir->id,
            'shift_id' => $todayShift->id,
            'total_amount' => $randomProduct->price * 2,
            'payment_method' => 'qris',
            'amount_paid' => $randomProduct->price * 2,
            'status' => 'completed',
            'created_at' => $todayOrderDate,
            'updated_at' => $todayOrderDate,
        ]);

        $todayOrder->orderItems()->create([
            'product_id' => $randomProduct->id,
            'quantity' => 2,
            'unit_price' => $randomProduct->price,
            'subtotal' => $randomProduct->price * 2,
            'created_at' => $todayOrderDate,
            'updated_at' => $todayOrderDate,
        ]);
    }
}
