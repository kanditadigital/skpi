@props([
    'status',
    'text' => null,
])

@php
    $statusMeta = [
        'pending' => ['class' => 'badge-warning', 'label' => 'Menunggu Approval'],
        'approved' => ['class' => 'badge-success', 'label' => 'Disetujui'],
        'rejected' => ['class' => 'badge-danger', 'label' => 'Ditolak'],
    ];
    $meta = $statusMeta[$status] ?? ['class' => 'badge-secondary', 'label' => ucfirst($status ?? 'status')];
    $content = $text ?? $meta['label'];
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $meta['class']]) }}>
    {{ $content }}
</span>
