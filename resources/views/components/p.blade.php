@php

    $styles = [
        'style-1' => 'text-base sm:text-lg md:text-xl lg:text-2xl px-2 sm:px-4 md:px-5   bordr-blue-500 text-gray-800 bg-blue-50 py-5',
    ];

    $style = $styles[$style] ?? '';

@endphp


<p class="{{ $style }}">
    {{ $slot }}
</p>
