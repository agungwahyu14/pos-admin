@props(['variant' => 'primary', 'type' => 'button'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn btn-' . $variant . ' rounded-3 fw-medium px-4 py-2']) }}>
    {{ $slot }}
</button>
