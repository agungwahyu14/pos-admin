<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $todaysOrders = Order::whereDate('created_at', $today)->get();
        $todaysSales = $todaysOrders->sum('total_amount');
        $ordersCount = $todaysOrders->count();
        $averageOrder = $ordersCount > 0 ? $todaysSales / $ordersCount : 0;

        $activeShifts = Shift::where('status', 'open')->count();

        // Products with stock <= 10
        $lowStockProducts = Product::where('stock', '<=', 10)->get();

        // Note: For top selling, we might need to join order_items later.
        // For now, we will pass empty collection or basic top products.

        return view('admin.dashboard', compact(
            'todaysSales',
            'ordersCount',
            'averageOrder',
            'activeShifts',
            'lowStockProducts'
        ));
    }
}
