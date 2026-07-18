@props([
    'title' => 'Tidak ada data',
    'message' => 'Belum ada data yang dapat ditampilkan di sini.',
    'icon' => null,
])

<div class="flex flex-col items-center justify-center p-8 text-center bg-white border border-gray-200 border-dashed rounded-xl py-12">
    @if($icon)
        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-400">
            {{ $icon }}
        </div>
    @else
        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-400">
            <svg class="w-8 h-8" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        </div>
    @endif
    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $title }}</h3>
    <p class="text-sm text-gray-500 mb-4 max-w-sm">{{ $message }}</p>
    
    @if(isset($action))
        <div>
            {{ $action }}
        </div>
    @endif
</div>
