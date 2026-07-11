@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Add Category</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-light rounded-3 fw-medium px-4 py-2 border">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <x-card>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <x-input name="name" label="Category Name" required />
                <x-input name="slug" label="Slug (Optional, auto-generated if left blank)" />
                
                <div class="text-end mt-4">
                    <x-button type="submit" variant="primary">Save Category</x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection
