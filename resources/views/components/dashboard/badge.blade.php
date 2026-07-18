@props([
    'color' => 'blue' // blue, red, green, yellow, gray, indigo, purple, pink
])

@php
    $classes = "bg-{$color}-100 text-{$color}-800";
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $slot }}
</span>
