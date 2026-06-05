@props([
    'size' => 'md',
])

@php
    $heights = [
        'sm' => '40px',
        'md' => '52px',
        'lg' => '72px',
        'xl' => '96px',
        'sidebar' => '48px',
        'sidebar-expanded' => '64px',
    ];
    $height = $heights[$size] ?? $heights['md'];
@endphp

<img
    src="{{ asset('result.png') }}"
    alt="{{ config('app.name', 'GreenRoute') }}"
    {{ $attributes->merge(['class' => 'greenroute-logo']) }}
    style="max-height: {{ $height }}; width: auto; object-fit: contain;"
>
