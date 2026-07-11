@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Products</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-3 fw-medium px-4 py-2">
        <i class="bi bi-plus-lg me-2"></i> Add Product
    </a>
</div>

<x-card>
    <div class="d-flex justify-content-between mb-3">
        <div class="w-25">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <input type="text" name="search" class="form-control bg-white rounded-3" placeholder="Search products..." value="{{ request('search') }}">
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Image</th>
                    <th>Name / SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody class="table-light">
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-medium">{{ $product->name }}</div>
                        <div class="small text-muted">{{ $product->sku }}</div>
                    </td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td class="fw-medium">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $product->stock <= 10 ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }} rounded-pill px-3 py-2">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        @if($product->status === 'active')
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-light rounded-3 text-primary me-2">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline-block delete-form">
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
                    <td colspan="7" class="text-center py-4 text-muted">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-end mt-4">
        {{ $products->links() }}
    </div>
</x-card>
@endsection
