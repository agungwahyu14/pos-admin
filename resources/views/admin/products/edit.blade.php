@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Edit Product</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-light rounded-3 fw-medium px-4 py-2 border">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <div class="col-lg-8">
            <x-card>
                <h5 class="fw-bold mb-4">Product Details</h5>
                <div class="row g-3">
                    <div class="col-md-12">
                        <x-input name="name" label="Product Name" :value="$product->name" required />
                    </div>
                    
                    <div class="col-md-6">
                        <x-input name="sku" label="SKU (Optional)" :value="$product->sku" />
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-medium">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select bg-white rounded-3 p-2 @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label fw-medium">Description</label>
                            <textarea name="description" id="description" rows="4" class="form-control bg-white rounded-3 p-2 @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
        
        <div class="col-lg-4">
            <x-card class="mb-4">
                <h5 class="fw-bold mb-4">Pricing & Stock</h5>
                
                <x-input name="price" label="Price (Rp)" type="number" :value="$product->price" required />
                <x-input name="stock" label="Stock Quantity" type="number" :value="$product->stock" required />
                
                <div class="mb-3">
                    <label for="status" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select bg-white rounded-3 p-2 @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </x-card>
            
            <x-card>
                <h5 class="fw-bold mb-4">Product Image</h5>
                
                @if($product->image)
                    <div class="mb-3 text-center p-3 border rounded-3 bg-light">
                        <img src="{{ Storage::url($product->image) }}" alt="Current Image" class="img-fluid rounded" style="max-height: 150px;">
                    </div>
                @endif
                
                <div class="mb-3">
                    <input class="form-control bg-white rounded-3 @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                    <div class="form-text mt-2">Recommended size: 800x800px. Max: 2MB. Leave empty to keep current image.</div>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="text-end mt-4">
                    <x-button type="submit" variant="primary" class="w-100">Update Product</x-button>
                </div>
            </x-card>
        </div>
    </div>
</form>
@endsection
