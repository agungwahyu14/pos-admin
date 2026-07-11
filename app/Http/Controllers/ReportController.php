<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $dateParam = $request->query('date');
        $date = $dateParam ? Carbon::parse($dateParam) : Carbon::today();

        $dailySales = Order::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->sum('total_amount');

        $transactionCount = Order::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->count();

        // Top selling products today
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereDate('orders.created_at', $date)
            ->where('orders.status', 'completed')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return response()->json([
            'daily_sales' => $dailySales,
            'transaction_count' => $transactionCount,
            'top_products' => $topProducts,
        ]);
    }

    public function sales(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::today()->subDays(7)->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());

        $sales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('COUNT(id) as total_transactions')
            )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($sales);
    }

    public function inventory()
    {
        // Get products with stock less than 10 (or any threshold)
        $lowStockProducts = Product::where('stock', '<', 10)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json($lowStockProducts);
    }
}
