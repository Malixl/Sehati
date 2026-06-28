{{-- FAQ Partial --}}
{{--
    Usage:
    @include('partials.faq', ['items' => [
        ['q' => 'Pertanyaan?', 'a' => 'Jawaban.'],
    ]])
--}}

@props([
    'items' => [],
])

<div id="accordion-faq" data-accordion="collapse" data-active-classes="bg-blue-100 text-blue-900" data-inactive-classes="text-gray-700">
    @foreach($items as $index => $item)
        <h3 id="faq-heading-{{ $index }}">
            <button type="button"
                class="flex items-center justify-between w-full p-5 font-medium text-left border border-gray-200 {{ $index === 0 ? 'rounded-t-xl' : 'border-t-0' }} hover:bg-gray-50 focus:ring-4 focus:ring-blue-200"
                data-accordion-target="#faq-body-{{ $index }}"
                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                aria-controls="faq-body-{{ $index }}">
                <span class="text-sm sm:text-base">{{ $item['q'] }}</span>
                <svg data-accordion-icon class="w-4 h-4 shrink-0 ml-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </h3>
        <div id="faq-body-{{ $index }}" class="{{ $index === 0 ? '' : 'hidden' }}" aria-labelledby="faq-heading-{{ $index }}">
            <div class="p-5 border border-t-0 border-gray-200 {{ $loop->last ? 'rounded-b-xl' : '' }}">
                <p class="text-sm text-gray-500">{{ $item['a'] }}</p>
            </div>
        </div>
    @endforeach
</div>
