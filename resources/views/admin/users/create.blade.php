@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Add User</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-3 fw-medium px-4 py-2 border">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-6">
        <x-card>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-12">
                        <x-input name="name" label="Full Name" required />
                    </div>
                    
                    <div class="col-md-12">
                        <x-input name="email" label="Email Address" type="email" required />
                    </div>
                    
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="role" class="form-label fw-medium">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select bg-white rounded-3 p-2 @error('role') is-invalid @enderror" required>
                                <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Cashier (Petugas)</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Owner)</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <x-input name="password" label="Password" type="password" required />
                    </div>
                    
                    <div class="col-md-6">
                        <x-input name="password_confirmation" label="Confirm Password" type="password" required />
                    </div>
                </div>
                
                <div class="text-end mt-4">
                    <x-button type="submit" variant="primary">Save User</x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection
