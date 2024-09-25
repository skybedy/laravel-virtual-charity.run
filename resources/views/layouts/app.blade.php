<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>Virtual Charity Run @yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://api.mapy.cz/loader.js"></script>
        <script type="text/javascript">Loader.load();</script>
        <script src="https://js.stripe.com/v3/"></script>
    </head>
    <body class="font-sans antialiased">

        <div class="min-h-screen flex flex-col bg-gradient-to-b from-sky-300 to-sky-500">
            @include('layouts.navigation')


            <!-- Page Content -->
            <main class="flex-grow px-3 sm:px-0">
                    @if (session('success'))
                        <x-flash-message type="success" :message="session('success')" />
                    @endif

                    @if (session('error'))
                        <x-flash-message type="error" :message="session('error')" />
                    @endif

                    @if (session('warning'))
                        <x-flash-message type="warning" :message="session('warning')" />
                    @endif

                    @if (session('info'))
                        <x-flash-message type="info" :message="session('info')" />
                    @endif


                {{ $slot }}
            </main>
             <!-- Footer -->
        <footer class="bg-[#fefdf9] text-blue text-center  h-24 border-t border-orange-400 flex flex-col justify-center">
            <div class="max-w-7xl  mx-auto">
                <p>&copy; 2024 Virtual Charity Run. All rights reserved.</p>
            </div>
        </footer>

        </div>
        @include('cookie-consent::index')
    </body>
</html>
