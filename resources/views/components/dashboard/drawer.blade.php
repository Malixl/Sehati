@props([
    'id' => '',
    'title' => 'Detail',
])

<div id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-label" class="fixed top-0 right-0 z-40 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white w-full sm:w-96 border-l border-gray-200"
     data-drawer-target="{{ $id }}"
     data-drawer-show="{{ $id }}"
     data-drawer-placement="right"
     aria-hidden="true"
>
    <div class="flex items-center justify-between mb-4">
        <h5 id="{{ $id }}-label" class="inline-flex items-center text-base font-semibold text-gray-900">
            {{ $title }}
        </h5>
        <button type="button" data-drawer-hide="{{ $id }}" aria-controls="{{ $id }}" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>
    </div>
    <div class="py-2">
        {{ $slot }}
    </div>
</div>
