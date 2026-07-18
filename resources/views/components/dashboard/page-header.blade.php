@props([
    'title' => 'Page Title',
    'subtitle' => null,
    'breadcrumb' => []
])

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
    <div>
        @if(count($breadcrumb) > 0)
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm text-gray-500">
                    <li class="inline-flex items-center">
                        <a href="/dashboard" class="inline-flex items-center hover:text-blue-600 transition-colors">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Home
                        </a>
                    </li>
                    @foreach($breadcrumb as $label => $link)
                        <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                @if(!$loop->last && $link)
                                    <a href="{{ $link }}" class="ms-1 text-sm font-medium hover:text-blue-600 md:ms-2">{{ $label }}</a>
                                @else
                                    <span class="ms-1 text-sm font-medium text-gray-400 md:ms-2">{{ $label }}</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
        
        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>

    @if(isset($action))
        <div class="mt-4 md:mt-0">
            {{ $action }}
        </div>
    @endif
</div>
