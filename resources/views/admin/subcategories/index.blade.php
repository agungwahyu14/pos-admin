@extends('admin.layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
    #subcategoriesTable_wrapper .dataTables_filter input {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        padding: 6px 12px;
        font-size: 0.875rem;
        background-color: #ffffff;
    }
    #subcategoriesTable_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(5, 128, 140, 0.15);
    }
    #subcategoriesTable_wrapper .dataTables_length select {
        border-radius: 10px;
        border: 1px solid #dee2e6;
        padding: 6px 28px 6px 12px;
        font-size: 0.875rem;
        background-color: #ffffff;
    }
    #subcategoriesTable_wrapper .dataTables_info,
    #subcategoriesTable_wrapper .dataTables_length {
        font-size: 0.85rem;
        color: #6B7280;
    }
    #subcategoriesTable_wrapper .page-link {
        border-radius: 8px !important;
        margin: 0 2px;
        font-size: 0.85rem;
        color: var(--bs-primary);
    }
    #subcategoriesTable_wrapper .page-item.active .page-link {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: #fff;
    }
    /* Cursor pointer on sortable columns */
    #subcategoriesTable thead th.sorting,
    #subcategoriesTable thead th.sorting_asc,
    #subcategoriesTable thead th.sorting_desc {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Sub Categories</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sub Categories</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary rounded-3 fw-medium px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Add Sub Category
    </a>
</div>

<x-card>
    <div class="table-responsive">
        <table id="subcategoriesTable" class="table table-hover align-middle w-100">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Slug</th>
                    <th class="text-end no-sort">Actions</th>
                </tr>
            </thead>
            <tbody class="table-light">
                @foreach($subCategories as $subCategory)
                <tr>
                    <td>{{ $subCategory->id }}</td>
                    <td class="fw-medium">{{ $subCategory->name }}</td>
                    <td class="text-muted">{{ $subCategory->category->name ?? '-' }}</td>
                    <td class="text-muted">{{ $subCategory->slug }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.subcategories.edit', $subCategory->id) }}" class="btn btn-sm btn-light rounded-3 text-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.subcategories.destroy', $subCategory->id) }}" method="POST" class="d-inline-block delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light rounded-3 text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-card>
@endsection

@push('scripts')
{{-- DataTables JS — use standalone builds (include own jQuery) --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#subcategoriesTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            language: {
                search: "",
                searchPlaceholder: "Search subcategories...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ subcategories",
                infoEmpty: "No subcategories found",
                zeroRecords: "No matching subcategories found",
                paginate: {
                    previous: '<i class="bi bi-chevron-left"></i>',
                    next: '<i class="bi bi-chevron-right"></i>',
                }
            },
            columnDefs: [
                // Kolom ke-4 (index 3 = Actions) tidak bisa diurutkan
                { orderable: false, targets: -1 }
            ],
            order: [[0, 'desc']],
        });
    });
</script>
@endpush
