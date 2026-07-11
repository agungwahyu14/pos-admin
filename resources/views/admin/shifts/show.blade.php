@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Shift Details #SHIFT-{{ str_pad($shift->id, 5, '0', STR_PAD_LEFT) }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.shifts.index') }}">Shifts</a></li>
                <li class="breadcrumb-item active" aria-current="page">Details</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.shifts.index') }}" class="btn btn-light rounded-3 fw-medium px-4 py-2 border">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <x-card class="mb-4">
            <h5 class="fw-bold mb-4 border-bottom pb-2">Cashier Info</h5>
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">
                    {{ substr($shift->user->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <div class="fw-bold fs-5">{{ $shift->user->name ?? 'Unknown' }}</div>
                    <div class="text-muted small">{{ $shift->user->email ?? 'No email' }}</div>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-muted small">Status</div>
                <div class="mt-1">
                    @if($shift->status === 'active')
                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Active</span>
                    @elseif($shift->status === 'closed')
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Closed</span>
                    @endif
                </div>
            </div>
        </x-card>
        
        <x-card>
            <h5 class="fw-bold mb-4 border-bottom pb-2">Time Info</h5>
            <div class="mb-4">
                <div class="text-muted small mb-1">Started At</div>
                <div class="fw-medium fs-5"><i class="bi bi-play-circle text-primary me-2"></i>{{ $shift->start_time->format('d M Y, H:i') }}</div>
            </div>
            <div>
                <div class="text-muted small mb-1">Ended At</div>
                <div class="fw-medium fs-5">
                    @if($shift->end_time)
                        <i class="bi bi-stop-circle text-danger me-2"></i>{{ $shift->end_time->format('d M Y, H:i') }}
                    @else
                        <span class="text-muted fst-italic">Shift is currently active</span>
                    @endif
                </div>
            </div>
        </x-card>
    </div>
    
    <div class="col-lg-8">
        <x-card class="mb-4">
            <h5 class="fw-bold mb-4 border-bottom pb-2">Financial Summary</h5>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted small mb-1">Opening Cash</div>
                        <div class="fw-bold fs-4 text-dark">Rp{{ number_format($shift->starting_cash, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted small mb-1">Expected Cash</div>
                        <div class="fw-bold fs-4 text-primary">Rp{{ number_format($shift->expected_cash, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="text-muted small mb-1">Actual Cash</div>
                        <div class="fw-bold fs-4 {{ $shift->actual_cash == $shift->expected_cash ? 'text-success' : ($shift->actual_cash < $shift->expected_cash ? 'text-danger' : 'text-dark') }}">
                            @if($shift->actual_cash !== null)
                                Rp{{ number_format($shift->actual_cash, 0, ',', '.') }}
                            @else
                                <span class="fs-6 text-muted">Not closed yet</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            @if($shift->status === 'closed')
                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-bold mb-3">Discrepancy</h6>
                    @php
                        $diff = $shift->actual_cash - $shift->expected_cash;
                    @endphp
                    @if($diff == 0)
                        <div class="alert alert-success d-flex align-items-center mb-0 py-2 border-0">
                            <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                            <div>Perfect! Actual cash matches expected cash exactly.</div>
                        </div>
                    @elseif($diff < 0)
                        <div class="alert alert-danger d-flex align-items-center mb-0 py-2 border-0">
                            <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                            <div>
                                <strong>Shortage:</strong> Rp{{ number_format(abs($diff), 0, ',', '.') }}
                                <div class="small mt-1">Actual cash is less than expected.</div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning d-flex align-items-center mb-0 py-2 border-0">
                            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                            <div>
                                <strong>Overage:</strong> Rp{{ number_format($diff, 0, ',', '.') }}
                                <div class="small mt-1">Actual cash is more than expected.</div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </x-card>
        
        <x-card>
            <h5 class="fw-bold mb-4 border-bottom pb-2">Shift Orders ({{ $shift->orders->count() }})</h5>
            
            @if($shift->orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Order ID</th>
                                <th>Time</th>
                                <th>Payment Method</th>
                                <th class="text-end">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shift->orders as $order)
                            <tr>
                                <td class="fw-medium">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->created_at->format('H:i') }}</td>
                                <td>{{ ucfirst($order->payment_method) }}</td>
                                <td class="text-end fw-bold">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light text-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                    No orders were processed during this shift.
                </div>
            @endif
        </x-card>
    </div>
</div>
@endsection
