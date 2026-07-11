@props(['label' => '', 'name', 'type' => 'text', 'value' => ''])

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label fw-medium">{{ $label }}</label>
    @endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}" 
        {{ $attributes->merge(['class' => 'form-control bg-white rounded-3 p-2' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
