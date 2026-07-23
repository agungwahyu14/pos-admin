<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Shift;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $dateRange;

    public function __construct($dateRange)
    {
        $this->dateRange = $dateRange;
    }

    public function collection()
    {
        $query = Order::with('user');

        if ($this->dateRange === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($this->dateRange === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($this->dateRange === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($this->dateRange === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $shiftsQuery = Shift::query();
        if ($this->dateRange === 'today') {
            $shiftsQuery->whereDate('created_at', Carbon::today());
        } elseif ($this->dateRange === 'week') {
            $shiftsQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($this->dateRange === 'month') {
            $shiftsQuery->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($this->dateRange === 'year') {
            $shiftsQuery->whereYear('created_at', Carbon::now()->year);
        }

        $totalTargetCups = $shiftsQuery->sum('target_cups');
        $totalActualCups = $shiftsQuery->sum('actual_cups');
        $totalTargetFoods = $shiftsQuery->sum('target_foods');
        $totalActualFoods = $shiftsQuery->sum('actual_foods');

        $orders = $query->get();

        $summary = (object)[
            'is_summary' => true,
            'order_count' => $orders->count(),
            'subtotal' => $orders->sum('subtotal'),
            'discount_amount' => $orders->sum('discount_amount'),
            'tax' => $orders->sum('tax'),
            'service_charge' => $orders->sum('service_charge'),
            'total_amount' => $orders->sum('total_amount'),
        ];

        $orders->push($summary);

        $orders->push((object)['is_empty' => true]);

        $orders->push((object)[
            'is_target_summary' => true,
            'label' => 'Total Cups (Actual vs Target)',
            'actual' => $totalActualCups,
            'target' => $totalTargetCups,
            'status' => $totalActualCups >= $totalTargetCups ? 'Tercapai' : 'Tidak Tercapai (Kurang ' . ($totalTargetCups - $totalActualCups) . ')'
        ]);

        $orders->push((object)[
            'is_target_summary' => true,
            'label' => 'Total Foods (Actual vs Target)',
            'actual' => $totalActualFoods,
            'target' => $totalTargetFoods,
            'status' => $totalActualFoods >= $totalTargetFoods ? 'Tercapai' : 'Tidak Tercapai (Kurang ' . ($totalTargetFoods - $totalActualFoods) . ')'
        ]);

        return $orders;
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Date',
            'Cashier',
            'Payment Method',
            'Subtotal',
            'Discount',
            'Tax',
            'Service Charge',
            'Total Amount',
            'Status'
        ];
    }

    public function map($order): array
    {
        if (isset($order->is_summary) && $order->is_summary) {
            return [
                'TOTAL (' . $order->order_count . ' Orders)',
                '',
                '',
                '',
                $order->subtotal,
                $order->discount_amount,
                $order->tax,
                $order->service_charge,
                $order->total_amount,
                '',
            ];
        }

        if (isset($order->is_empty)) {
            return ['', '', '', '', '', '', '', '', '', ''];
        }

        if (isset($order->is_target_summary)) {
            return [
                $order->label,
                '',
                '',
                '',
                $order->actual . ' (Actual)',
                $order->target . ' (Target)',
                '',
                '',
                '',
                $order->status,
            ];
        }

        return [
            '#ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
            $order->created_at->format('d M Y H:i:s'),
            $order->user->name ?? 'Unknown',
            strtoupper($order->payment_method),
            $order->subtotal,
            $order->discount_amount,
            $order->tax,
            $order->service_charge,
            $order->total_amount,
            ucfirst($order->status),
        ];
    }
}
