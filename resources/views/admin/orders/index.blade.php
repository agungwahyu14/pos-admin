@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    #ordersTable_wrapper .dataTables_filter input,
    #ordersTable_wrapper .dataTables_length select {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
        background-color: #ffffff;
    }
    #ordersTable_wrapper .dataTables_filter input { padding: 6px 12px; }
    #ordersTable_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(5, 128, 140, 0.15);
    }
    #ordersTable_wrapper .dataTables_length select { padding: 6px 28px 6px 12px; }
    #ordersTable_wrapper .dataTables_info,
    #ordersTable_wrapper .dataTables_length { font-size: 0.85rem; color: #6B7280; }
    #ordersTable_wrapper .page-link {
        border-radius: 8px !important; margin: 0 2px;
        font-size: 0.85rem; color: var(--bs-primary);
    }
    #ordersTable_wrapper .page-item.active .page-link {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
    }
</style>
@endpush

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
    <div class="table-responsive">
        <table id="ordersTable" class="table table-hover align-middle w-100">
            <thead class="table-primary">
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Cashier</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="table-light">
                @foreach($orders as $order)
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
                @endforeach
            </tbody>
        </table>
    </div>
</x-card>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#ordersTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            language: {
                search: "",
                searchPlaceholder: "Search orders...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ orders",
                infoEmpty: "No orders found",
                zeroRecords: "No matching orders found",
                paginate: {
                    previous: '<i class="bi bi-chevron-left"></i>',
                    next: '<i class="bi bi-chevron-right"></i>',
                }
            },
            columnDefs: [
                { orderable: false, targets: -1 }
            ],
            order: [[1, 'desc']], // Sort by date descending by default
        });
    });
</script>
@endpush
