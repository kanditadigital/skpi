@props([
    'variant' => 'primary',
    'size' => null,
    'block' => false,
    'type' => 'button',
])

@php
    $allowedVariants = ['primary', 'secondary', 'danger'];
    $variant = in_array($variant, $allowedVariants) ? $variant : 'primary';
    $btnClasses = 'btn btn-'.$variant;
    if ($size === 'sm') {
        $btnClasses .= ' btn-sm';
    }
    if ($block) {
        $btnClasses .= ' btn-block';
    }
    $hasHref = $attributes->has('href');
@endphp

@if ($hasHref)
    <a {{ $attributes->merge(['class' => $btnClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $btnClasses]) }}>
        {{ $slot }}
    </button>
@endif
