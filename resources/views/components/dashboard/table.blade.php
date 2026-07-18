@props([
    'headers' => [],
])

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    @if (isset($toolbar))
        <div class="px-4 py-3 border-b border-gray-200 bg-white">
            {{ $toolbar }}
        </div>
    @endif
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    @if (isset($head) && $head->isNotEmpty())
                        {{ $head }}
                    @else
                        @foreach($headers as $header)
                            <th scope="col" class="px-4 py-3">{{ $header }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
