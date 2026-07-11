@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Edit User</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-12">
                        <x-input name="name" label="Full Name" :value="$user->name" required />
                    </div>
                    
                    <div class="col-md-12">
                        <x-input name="email" label="Email Address" type="email" :value="$user->email" required />
                    </div>
                    
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="role" class="form-label fw-medium">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select bg-white rounded-3 p-2 @error('role') is-invalid @enderror" required {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                <option value="petugas" {{ old('role', $user->role) === 'petugas' ? 'selected' : '' }}>Cashier (Petugas)</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Owner)</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(auth()->id() === $user->id)
                                <div class="form-text">You cannot change your own role.</div>
                                <input type="hidden" name="role" value="{{ $user->role }}">
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-12 mt-4">
                        <h6 class="fw-bold border-bottom pb-2">Change Password (Optional)</h6>
                    </div>
                    
                    <div class="col-md-6">
                        <x-input name="password" label="New Password" type="password" />
                    </div>
                    
                    <div class="col-md-6">
                        <x-input name="password_confirmation" label="Confirm Password" type="password" />
                    </div>
                </div>
                
                <div class="text-end mt-4">
                    <x-button type="submit" variant="primary">Update User</x-button>
                </div>
            </form>
        </x-card>
    </div>
</div>
@endsection
