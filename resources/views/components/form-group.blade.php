@props([
    'label' => null,
    'for' => null,
    'class' => '',
])

@php
    $wrapperClass = trim('form-group ' . $class);
@endphp

<div {{ $attributes->merge(['class' => $wrapperClass]) }}>
    @if ($label)
        <label @if($for) for="{{ $for }}" @endif>{{ $label }}</label>
    @endif
    {{ $slot }}
</div>
