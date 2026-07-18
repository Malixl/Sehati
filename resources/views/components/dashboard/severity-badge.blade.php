@props(['level'])

@php
    $config = [
        'low' => ['color' => 'emerald', 'label' => 'Risiko Rendah'],
        'moderate' => ['color' => 'yellow', 'label' => 'Risiko Sedang'],
        'high' => ['color' => 'orange', 'label' => 'Risiko Tinggi'],
        'critical' => ['color' => 'red', 'label' => 'Kritis'],
    ];

    $conf = $config[$level] ?? ['color' => 'gray', 'label' => 'Tidak Diketahui'];
    $color = $conf['color'];
    $label = $conf['label'];
    
    // Tailwind classes mapping to avoid purge issues
    $classes = "bg-{$color}-100 text-{$color}-800";
    if($level === 'critical') {
        $classes .= " font-bold border border-red-200 animate-pulse";
    }
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>
