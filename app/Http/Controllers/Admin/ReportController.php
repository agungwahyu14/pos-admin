<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateRange = $request->input('date_range', 'today'); // today, week, month, year, all

        $query = Order::query();

        if ($dateRange === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateRange === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($dateRange === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($dateRange === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $totalRevenue = (clone $query)->sum('total_amount');
        $totalOrders = (clone $query)->count();
        $totalDiscount = (clone $query)->sum('discount_amount');
        $totalTax = (clone $query)->sum('tax');

        // Payment Methods breakdown
        $paymentMethods = (clone $query)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Top Selling Products
        $topProductsQuery = OrderItem::with('product')
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(subtotal) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5);

        if ($dateRange !== 'all') {
            $topProductsQuery->whereHas('order', function ($q) use ($dateRange) {
                if ($dateRange === 'today') {
                    $q->whereDate('created_at', Carbon::today());
                } elseif ($dateRange === 'week') {
                    $q->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($dateRange === 'month') {
                    $q->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                } elseif ($dateRange === 'year') {
                    $q->whereYear('created_at', Carbon::now()->year);
                }
            });
        }

        $topProducts = $topProductsQuery->get();

        return view('admin.reports.index', compact(
            'totalRevenue', 'totalOrders', 'totalDiscount', 'totalTax',
            'paymentMethods', 'topProducts', 'dateRange'
        ));
    }

    public function export(Request $request)
    {
        $dateRange = $request->input('date_range', 'today');
        $fileName = 'Laporan_Penjualan_' . ucfirst($dateRange) . '_' . date('Ymd_His') . '.xlsx';
        
        return Excel::download(new ReportExport($dateRange), $fileName);
    }
}
