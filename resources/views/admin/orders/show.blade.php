@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Order Details #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Details</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-light rounded-3 fw-medium px-4 py-2 border">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <x-card>
            <h5 class="fw-bold mb-4 border-bottom pb-2">Order Items</h5>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $item->product->name ?? 'Unknown Product' }}</div>
                                @if($item->product && $item->product->sku)
                                    <div class="small text-muted">{{ $item->product->sku }}</div>
                                @endif
                            </td>
                            <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end fw-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="row justify-content-end mt-4">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless bg-white">
                        <tbody>
                            <tr>
                                <td class="text-muted">Subtotal</td>
                                <td class="text-end fw-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td class="text-muted">Discount</td>
                                <td class="text-end text-danger fw-medium">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($order->tax > 0)
                            <tr>
                                <td class="text-muted">Tax</td>
                                <td class="text-end fw-medium">Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($order->service_charge > 0)
                            <tr>
                                <td class="text-muted">Service Charge</td>
                                <td class="text-end fw-medium">Rp {{ number_format($order->service_charge, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr class="border-top">
                                <td class="fw-bold fs-5 pt-2">Total</td>
                                <td class="text-end fw-bold fs-5 pt-2 text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card>
    </div>
    
    <div class="col-lg-4">
        <x-card class="mb-4">
            <h5 class="fw-bold mb-4 border-bottom pb-2">Order Info</h5>
            
            <div class="mb-3">
                <div class="text-muted small">Date & Time</div>
                <div class="fw-medium">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </div>
            
            <div class="mb-3">
                <div class="text-muted small">Cashier</div>
                <div class="fw-medium d-flex align-items-center mt-1">
                    <i class="bi bi-person-circle text-primary me-2 fs-5"></i>
                    {{ $order->user->name ?? 'Unknown' }}
                </div>
            </div>
            
            <div class="mb-3">
                <div class="text-muted small">Shift ID</div>
                <div class="fw-medium">
                    <a href="{{ route('admin.shifts.show', $order->shift_id) }}" class="text-decoration-none">
                        #SHIFT-{{ str_pad($order->shift_id, 5, '0', STR_PAD_LEFT) }}
                    </a>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="text-muted small">Status</div>
                <div class="mt-1">
                    @if($order->status === 'completed')
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Completed</span>
                    @elseif($order->status === 'cancelled')
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Cancelled</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">{{ ucfirst($order->status) }}</span>
                    @endif
                </div>
            </div>
        </x-card>
        
        <x-card>
            <h5 class="fw-bold mb-4 border-bottom pb-2">Payment Info</h5>
            
            <div class="mb-3">
                <div class="text-muted small">Method</div>
                <div class="fw-medium mt-1">
                    @if($order->payment_method === 'cash')
                        <i class="bi bi-cash text-success me-2"></i> Cash
                    @elseif($order->payment_method === 'qris')
                        <i class="bi bi-qr-code text-info me-2"></i> QRIS
                    @else
                        {{ ucfirst($order->payment_method) }}
                    @endif
                </div>
            </div>
            
            <div class="mb-3">
                <div class="text-muted small">Amount Paid</div>
                <div class="fw-bold fs-5">Rp {{ number_format($order->amount_paid, 0, ',', '.') }}</div>
            </div>
            
            <div>
                <div class="text-muted small">Change</div>
                <div class="fw-medium">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</div>
            </div>
        </x-card>
    </div>
</div>
@endsection
