@props([
    'title' => '',
    'value' => '',
    'icon' => null,
    'trend' => null, 
    'trendDirection' => 'up', // up or down
    'trendColor' => 'green' // green, red, gray
])

<div class="p-6 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $value }}</h3>
        </div>
        @if($icon)
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                {{ $icon }}
            </div>
        @endif
    </div>
    
    @if($trend)
        <div class="mt-4 flex items-center text-sm">
            @if($trendDirection === 'up')
                <svg class="w-4 h-4 mr-1 text-{{ $trendColor }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            @else
                <svg class="w-4 h-4 mr-1 text-{{ $trendColor }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            @endif
            <!-- <span class="font-medium text-{{ $trendColor }}-600">{{ $trend }}</span> -->
            <!-- <span class="text-gray-500 ml-2">dari bulan lalu</span> -->
        </div>
    @endif
</div>
