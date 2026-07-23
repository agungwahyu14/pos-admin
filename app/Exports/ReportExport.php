<?php

namespace App\Exports;

use App\Models\Order;
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

        return $orders->push($summary);
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
