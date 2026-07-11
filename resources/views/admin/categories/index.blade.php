@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Categories</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-3 fw-medium px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Add Category
    </a>
</div>

<x-card>
    <div class="d-flex justify-content-between mb-3">
        <div class="w-25">
            <form action="{{ route('admin.categories.index') }}" method="GET">
                <input type="text" name="search" class="form-control        bg-white rounded-3" placeholder="Search categories..." value="{{ request('search') }}">
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="table-light">
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td class="fw-medium">{{ $category->name }}</td>
                    <td class="text-muted">{{ $category->slug }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-light rounded-3 text-primary me-2">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline-block delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light rounded-3 text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $categories->links() }}
    </div>
</x-card>
@endsection
