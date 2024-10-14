<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap');

            .indie-flower-regular {
            font-family: "Indie Flower", cursive;
            font-weight: 400;
            font-style: normal;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#fefdf9]">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500"  width="80px" height="80px" />
                </a>
            </div>
            <div class="mb-1">
                     <div class="indie-flower-regular text-4xl">Virtual Charity Run</div>
            </div>

                {{ $slot }}
        </body>
</html>
