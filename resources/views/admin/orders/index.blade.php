@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Orders</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
        </nav>
    </div>
</div>

<x-card>
    <div class="d-flex justify-content-between mb-3">
        <div class="w-25">
            <form action="{{ route('admin.orders.index') }}" method="GET">
                <input type="text" name="search" class="form-control bg-white rounded-3" placeholder="Search orders..." value="{{ request('search') }}">
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Cashier</th>
                    <th>Payment Method</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="table-light">
                @forelse($orders as $order)
                <tr>
                    <td class="fw-medium">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ $order->user->name ?? 'Unknown' }}</td>
                    <td>
                        @if($order->payment_method === 'cash')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Cash</span>
                        @elseif($order->payment_method === 'qris')
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">QRIS</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">{{ ucfirst($order->payment_method) }}</span>
                        @endif
                    </td>
                    <td class="fw-bold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>
                        @if($order->status === 'completed')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Completed</span>
                        @elseif($order->status === 'cancelled')
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Cancelled</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light rounded-3 text-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $orders->links() }}
    </div>
</x-card>
@endsection
