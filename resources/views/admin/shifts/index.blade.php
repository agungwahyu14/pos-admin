@extends('admin.layouts.app')

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
    <div class="d-flex justify-content-between mb-3">
        <div class="w-25">
            <form action="{{ route('admin.shifts.index') }}" method="GET">
                <input type="text" name="search" class="form-control bg-white rounded-3" placeholder="Search shifts..." value="{{ request('search') }}">
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Cashier</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Opening Cash</th>
                    <th>Expected Cash</th>
                    <th>Actual Cash</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="table-light">
                @forelse($shifts as $shift)
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
                    <td>Rp{{ number_format($shift->starting_cash, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($shift->expected_cash, 0, ',', '.') }}</td>
                    <td>
                        @if($shift->actual_cash !== null)
                            Rp{{ number_format($shift->actual_cash, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($shift->status === 'active')
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">Active</span>
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
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No shifts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $shifts->links() }}
    </div>
</x-card>
@endsection
