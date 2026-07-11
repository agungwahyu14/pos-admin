@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark">Settings</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
        </nav>
    </div>
</div>

<x-card>
    <h5 class="fw-bold mb-4">Store Information</h5>
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <x-input name="store_name" label="Store Name" value="{{ old('store_name', $settings->store_name) }}" />
        
        <div class="mb-3">
            <label class="form-label fw-medium">Address</label>
            <textarea name="store_address" class="form-control rounded-3 p-2 bg-white" rows="3">{{ old('store_address', $settings->store_address) }}</textarea>
        </div>
        
        <x-input name="phone" label="Phone Number" value="{{ old('phone', $settings->phone) }}" />
        
        <hr class="my-4 divider">
        
        <h5 class="fw-bold mb-4">Tax & Service</h5>
        
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="enableTax" name="tax_enabled" value="1" {{ old('tax_enabled', $settings->tax_enabled) ? 'checked' : '' }}>
            <label class="form-check-label fw-medium" for="enableTax">Enable Tax</label>
        </div>
        
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="enableService" name="service_enabled" value="1" {{ old('service_enabled', $settings->service_enabled) ? 'checked' : '' }}>
            <label class="form-check-label fw-medium" for="enableService">Enable Service Charge</label>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <x-input name="tax_value" label="Tax Percentage (%)" type="number" value="{{ old('tax_value', $settings->tax_value) }}" />
            </div>
            <div class="col-md-6">
                <x-input name="service_value" label="Service Charge (%)" type="number" value="{{ old('service_value', $settings->service_value) }}" />
            </div>
        </div>
        
        <div class="text-end mt-4">
            <x-button type="submit" variant="primary">Save Changes</x-button>
        </div>
    </form>
</x-card>
@endsection
