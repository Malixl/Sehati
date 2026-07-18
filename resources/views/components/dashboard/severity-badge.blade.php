@props(['level' => null, 'label' => null])

@php
    $displayText = $label ?? $level ?? 'Tidak Diketahui';
    
    // Fallback normalization just in case
    $normalized = strtolower($displayText);
    
    if (str_contains($normalized, 'rendah') || str_contains($normalized, 'low')) {
        $color = 'emerald';
    } elseif (str_contains($normalized, 'sedang') || str_contains($normalized, 'moderate')) {
        $color = 'yellow';
    } elseif (str_contains($normalized, 'tinggi') || str_contains($normalized, 'high')) {
        $color = 'orange';
    } elseif (str_contains($normalized, 'terdiagnosa') || str_contains($normalized, 'kritis') || str_contains($normalized, 'critical')) {
        $color = 'red';
    } else {
        $color = 'gray';
    }
    
    // Tailwind classes mapping to avoid purge issues
    $classes = "bg-{$color}-100 text-{$color}-800";
    if($level === 'critical') {
        $classes .= " font-bold border border-red-200 animate-pulse";
    }
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $displayText }}
</span>
