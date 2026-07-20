@props(['level' => null, 'label' => null])

@php
    $displayText = $label ?? $level ?? 'Tidak Diketahui';
    
    // Fallback normalization just in case
    $normalized = strtolower($displayText);
    
    if (str_contains($normalized, 'rendah') || str_contains($normalized, 'low')) {
        $classes = 'bg-emerald-100 text-emerald-800';
    } elseif (str_contains($normalized, 'sedang') || str_contains($normalized, 'moderate')) {
        $classes = 'bg-yellow-100 text-yellow-800';
    } elseif (str_contains($normalized, 'tinggi') || str_contains($normalized, 'high')) {
        $classes = 'bg-orange-100 text-orange-800';
    } elseif (str_contains($normalized, 'terdiagnosa') || str_contains($normalized, 'kritis') || str_contains($normalized, 'critical')) {
        $classes = 'bg-red-100 text-red-800';
    } else {
        $classes = 'bg-gray-100 text-gray-800';
    }
    
    if($level === 'critical') {
        $classes .= " font-bold border border-red-200 animate-pulse";
    }
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $displayText }}
</span>
