@extends('admin.layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-dark">Dashboard</h3>
    <p class="text-muted">Overview of today's performance</p>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <!-- Today's Sales -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm p-3 bg-primary text-white" style="border-radius: 16px;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-white bg-opacity-25 text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-cash-stack" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div class="small fw-medium text-white-50">Today's Sales</div>
                    <div class="fs-4 fw-bold">Rp {{ number_format($todaysSales, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Today's Orders -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm p-3 bg-primary text-white" style="border-radius: 16px;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-white bg-opacity-25 text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-receipt" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div class="small fw-medium text-white-50">Today's Orders</div>
                    <div class="fs-4 fw-bold">{{ $ordersCount }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Average Order -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm p-3 bg-primary text-white" style="border-radius: 16px;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-white bg-opacity-25 text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-graph-up" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div class="small fw-medium text-white-50">Average Order</div>
                    <div class="fs-4 fw-bold">Rp {{ number_format($averageOrder, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Shifts -->
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm p-3 bg-primary text-white" style="border-radius: 16px;">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-white bg-opacity-25 text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-clock-history" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div class="small fw-medium text-white-50">Active Shifts</div>
                    <div class="fs-4 fw-bold">{{ $activeShifts }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Low Stock -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
            <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">Low Stock Alerts</h5>
            
            @if($lowStockProducts->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($lowStockProducts as $product)
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3 text-muted" style="width: 40px; height: 40px;">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <div class="fw-medium text-dark">{{ $product->name }}</div>
                                <div class="small text-muted">{{ $product->category->name ?? 'Uncategorized' }}</div>
                            </div>
                        </div>
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                            {{ $product->stock }} left
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center py-4">All products are well stocked.</p>
            @endif
        </div>
    </div>
</div>

<!-- Add Bootstrap Icons via CDN for icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
