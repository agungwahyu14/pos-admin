@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Reports</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reports</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <x-card class="bg-white">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="d-flex align-items-end gap-3">
                <div class="flex-grow-1">
                    <label class="form-label fw-medium text-muted">Filter by Date</label>
                    <select name="date_range" class="form-select border rounded-3 p-2 shadow-sm bg-white" onchange="this.form.submit()">
                        <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $dateRange === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $dateRange === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $dateRange === 'year' ? 'selected' : '' }}>This Year</option>
                        <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>All Time</option>
                    </select>
                </div>
            </form>
        </x-card>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <x-card class="bg-primary text-white text-center shadow-sm border-0 h-100">
            <div class="mb-2"><i class="bi bi-wallet2 fs-2 opacity-75"></i></div>
            <p class="mb-1 text-white-50 fw-medium">Total Revenue</p>
            <h3 class="fw-bold mb-0">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </x-card>
    </div>
    <div class="col-md-3">
        <x-card class="bg-success text-white text-center shadow-sm border-0 h-100">
            <div class="mb-2"><i class="bi bi-bag-check fs-2 opacity-75"></i></div>
            <p class="mb-1 text-white-50 fw-medium">Total Orders</p>
            <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
        </x-card>
    </div>
    <div class="col-md-3">
        <x-card class="bg-info text-white text-center shadow-sm border-0 h-100">
            <div class="mb-2"><i class="bi bi-tags fs-2 opacity-75"></i></div>
            <p class="mb-1 text-white-50 fw-medium">Total Discount</p>
            <h3 class="fw-bold mb-0">Rp{{ number_format($totalDiscount, 0, ',', '.') }}</h3>
        </x-card>
    </div>
    <div class="col-md-3">
        <x-card class="bg-warning text-white text-center shadow-sm border-0 h-100">
            <div class="mb-2"><i class="bi bi-receipt fs-2 opacity-75"></i></div>
            <p class="mb-1 text-white-50 fw-medium">Total Tax</p>
            <h3 class="fw-bold mb-0">Rp{{ number_format($totalTax, 0, ',', '.') }}</h3>
        </x-card>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <x-card class="h-100">
            <h5 class="fw-bold mb-4 border-bottom pb-2">Top Selling Products</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th class="text-muted fw-semibold">Product</th>
                            <th class="text-muted fw-semibold text-center">Qty Sold</th>
                            <th class="text-muted fw-semibold text-end">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        @forelse($topProducts as $item)
                        <tr>
                            <td class="fw-medium">{{ $item->product->name ?? 'Unknown' }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill px-3 py-2">{{ $item->total_quantity }}</span>
                            </td>
                            <td class="text-end text-success fw-medium">Rp{{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <div class="mb-2"><i class="bi bi-inbox fs-3"></i></div>
                                No sales data found for this period
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
    
    <div class="col-md-6">
        <x-card class="h-100">
            <h5 class="fw-bold mb-4 border-bottom pb-2">Payment Methods Breakdown</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th class="text-muted fw-semibold">Method</th>
                            <th class="text-muted fw-semibold text-center">Orders</th>
                            <th class="text-muted fw-semibold text-end">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        @forelse($paymentMethods as $pm)
                        <tr>
                            <td>
                                @if(strtolower($pm->payment_method) == 'cash')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 me-2">
                                        <i class="bi bi-cash me-1"></i> Cash
                                    </span>
                                @elseif(strtolower($pm->payment_method) == 'qris')
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 me-2">
                                        <i class="bi bi-qr-code me-1"></i> QRIS
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 me-2">
                                        {{ ucfirst($pm->payment_method) }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center fw-medium">{{ $pm->count }}</td>
                            <td class="text-end fw-medium">Rp{{ number_format($pm->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No payment data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</div>
@endsection
