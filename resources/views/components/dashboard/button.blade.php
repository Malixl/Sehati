@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, outline
    'size' => 'md', // sm, md, lg
    'href' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all focus:ring-4 focus:outline-none';
    
    $sizeClasses = [
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
    ][$size] ?? $sizeClasses['md'];

    $variantClasses = [
        'primary' => 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-300',
        'secondary' => 'text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 focus:ring-gray-100',
        'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-300',
        'outline' => 'text-blue-600 border border-blue-600 hover:bg-blue-50 focus:ring-blue-300'
    ][$variant] ?? $variantClasses['primary'];

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
