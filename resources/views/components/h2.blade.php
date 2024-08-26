@php

    $styles = [
        'style-1' => 'text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-3xl text-white bg-orange-400 text-center font-black my-5 py-2 shadow-md',
    ];

    $style = $styles[$style] ?? '';

@endphp


<h2 class="{{ $style }}">
    {{ $slot }}
</h2>
