@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">My Profile</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</div>

<x-card>
    <form action="{{ route('admin.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <x-input name="name" label="Name" value="{{ old('name', $user->name) }}" />
            </div>
            <div class="col-md-6 mb-3">
                <x-input name="email" label="Email Address" type="email" value="{{ old('email', $user->email) }}" />
            </div>
        </div>

        <hr class="my-4 divider">
        <h5 class="fw-bold mb-4">Change Password</h5>
        <div class="alert alert-info py-2 rounded-3 border-0">
            <i class="bi bi-info-circle-fill me-2"></i> Leave the password fields blank if you do not want to change your password.
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <x-input name="password" label="New Password" type="password" />
            </div>
            <div class="col-md-6 mb-3">
                <x-input name="password_confirmation" label="Confirm New Password" type="password" />
            </div>
        </div>
        
        <div class="text-end mt-4">
            <x-button type="submit" variant="primary">Update Profile</x-button>
        </div>
    </form>
</x-card>
@endsection
