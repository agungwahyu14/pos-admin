@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    #usersTable_wrapper .dataTables_filter input,
    #usersTable_wrapper .dataTables_length select {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
        background-color: #ffffff;
    }
    #usersTable_wrapper .dataTables_filter input { padding: 6px 12px; }
    #usersTable_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(5, 128, 140, 0.15);
    }
    #usersTable_wrapper .dataTables_length select { padding: 6px 28px 6px 12px; }
    #usersTable_wrapper .dataTables_info,
    #usersTable_wrapper .dataTables_length { font-size: 0.85rem; color: #6B7280; }
    #usersTable_wrapper .page-link {
        border-radius: 8px !important; margin: 0 2px;
        font-size: 0.85rem; color: var(--bs-primary);
    }
    #usersTable_wrapper .page-item.active .page-link {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Users</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-3 fw-medium px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Add User
    </a>
</div>

<x-card>
    <div class="table-responsive">
        <table id="usersTable" class="table table-hover align-middle w-100">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="table-light">
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="fw-medium">{{ $user->name }}</div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">Admin</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">Cashier</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-light rounded-3 text-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if(auth()->id() !== $user->id)
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light rounded-3 text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
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
        $('#usersTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            language: {
                search: "",
                searchPlaceholder: "Search users...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ users",
                infoEmpty: "No users found",
                zeroRecords: "No matching users found",
                paginate: {
                    previous: '<i class="bi bi-chevron-left"></i>',
                    next: '<i class="bi bi-chevron-right"></i>',
                }
            },
            columnDefs: [
                { orderable: false, targets: -1 }
            ],
            order: [[0, 'asc']],
        });
    });
</script>
@endpush
