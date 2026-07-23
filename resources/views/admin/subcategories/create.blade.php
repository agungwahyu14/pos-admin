@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Add Sub Category</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.subcategories.index') }}">Sub Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.subcategories.index') }}" class="btn btn-light rounded-3 fw-medium px-4 py-2 border">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.subcategories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-medium text-dark">Parent Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select bg-light rounded-3 px-3 py-2 @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="form-label fw-medium text-dark">Sub Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control bg-light rounded-3 px-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Coffee">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-3 py-2 fw-medium">
                            <i class="bi bi-save me-2"></i> Save Sub Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
