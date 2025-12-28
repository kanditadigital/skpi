@props([
    'title',
    'headerClass' => 'bg-light',
])

<div {{ $attributes->merge(['class' => 'card mb-4']) }}>
    <div class="card-header {{ $headerClass }}">
        <h6 class="mb-0">{{ $title }}</h6>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
