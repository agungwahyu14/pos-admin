@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    #shiftsTable_wrapper .dataTables_filter input,
    #shiftsTable_wrapper .dataTables_length select {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
        background-color: #ffffff;
    }
    #shiftsTable_wrapper .dataTables_filter input { padding: 6px 12px; }
    #shiftsTable_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(5, 128, 140, 0.15);
    }
    #shiftsTable_wrapper .dataTables_length select { padding: 6px 28px 6px 12px; }
    #shiftsTable_wrapper .dataTables_info,
    #shiftsTable_wrapper .dataTables_length { font-size: 0.85rem; color: #6B7280; }
    #shiftsTable_wrapper .page-link {
        border-radius: 8px !important; margin: 0 2px;
        font-size: 0.85rem; color: var(--bs-primary);
    }
    #shiftsTable_wrapper .page-item.active .page-link {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Shifts</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shifts</li>
            </ol>
        </nav>
    </div>
</div>

<x-card>
    <div class="table-responsive">
        <table id="shiftsTable" class="table table-hover align-middle w-100">
            <thead class="table-primary">
                <tr>
                    <th>Cashier</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Opening Cash</th>
                    <th>Expected Cash</th>
                    <th>Actual Cash</th>
                    <th>Expected QRIS</th>
                    <th>Actual QRIS</th>
                    <th>Total Expected</th>
                    <th>Total Actual</th>
                    <th>Cups (Target vs Actual)</th>
                    <th>Foods (Target vs Actual)</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="table-light">
                @foreach($shifts as $shift)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                {{ substr($shift->user->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="fw-medium">{{ $shift->user->name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td>{{ $shift->start_time->format('d M Y, H:i') }}</td>
                    <td>{{ $shift->end_time ? $shift->end_time->format('d M Y, H:i') : '-' }}</td>
                    <td>Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($shift->expected_cash, 0, ',', '.') }}</td>
                    <td>
                        @if($shift->actual_cash !== null)
                            Rp {{ number_format($shift->actual_cash, 0, ',', '.') }}
                        @else
                            <span class="text-muted">Not closed</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($shift->expected_qris, 0, ',', '.') }}</td>
                    <td>
                        @if($shift->actual_qris !== null)
                            Rp {{ number_format($shift->actual_qris, 0, ',', '.') }}
                        @else
                            <span class="text-muted">Not closed</span>
                        @endif
                    </td>
                    <td class="fw-bold text-primary">Rp {{ number_format($shift->expected_cash + $shift->expected_qris, 0, ',', '.') }}</td>
                    <td class="fw-bold {{ ($shift->actual_cash !== null) ? (($shift->actual_cash + $shift->actual_qris) >= ($shift->expected_cash + $shift->expected_qris) ? 'text-success' : 'text-danger') : '' }}">
                        @if($shift->actual_cash !== null && $shift->actual_qris !== null)
                            Rp {{ number_format($shift->actual_cash + $shift->actual_qris, 0, ',', '.') }}
                        @else
                            <span class="text-muted">Not closed</span>
                        @endif
                    </td>
                    <td>
                        @if($shift->status === 'closed')
                            @if(($shift->actual_cups ?? 0) >= ($shift->target_cups ?? 30))
                                <span class="text-success fw-bold">{{ $shift->actual_cups }}</span> / {{ $shift->target_cups }}
                            @else
                                <span class="text-danger fw-bold">{{ $shift->actual_cups ?? 0 }}</span> / {{ $shift->target_cups }}
                            @endif
                        @else
                            <span class="text-muted">-</span> / {{ $shift->target_cups ?? 30 }}
                        @endif
                    </td>
                    <td>
                        @if($shift->status === 'closed')
                            @if(($shift->actual_foods ?? 0) >= ($shift->target_foods ?? 30))
                                <span class="text-success fw-bold">{{ $shift->actual_foods }}</span> / {{ $shift->target_foods }}
                            @else
                                <span class="text-danger fw-bold">{{ $shift->actual_foods ?? 0 }}</span> / {{ $shift->target_foods }}
                            @endif
                        @else
                            <span class="text-muted">-</span> / {{ $shift->target_foods ?? 30 }}
                        @endif
                    </td>
                    <td>
                        @if($shift->status === 'open')
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Open</span>
                        @elseif($shift->status === 'closed')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Closed</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">{{ ucfirst($shift->status) }}</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.shifts.show', $shift->id) }}" class="btn btn-sm btn-light rounded-3 text-primary">
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
        $('#shiftsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            language: {
                search: "",
                searchPlaceholder: "Search shifts...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ shifts",
                infoEmpty: "No shifts found",
                zeroRecords: "No matching shifts found",
                paginate: {
                    previous: '<i class="bi bi-chevron-left"></i>',
                    next: '<i class="bi bi-chevron-right"></i>',
                }
            },
            columnDefs: [
                { orderable: false, targets: -1 }
            ],
            order: [[1, 'desc']], // Sort by start time descending
        });
    });
</script>
@endpush
