<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta property="og:url" content="https://virtual-charity-run" />
        <meta property="og:type" content="website" /> <!-- nebo "article", pokud jde o článek -->
        <meta property="og:title" content="Virtual Charity Run" />
        <meta property="og:description" content="Virtuální běhy pro charitatovní účely" />
        <meta property="og:image" content="https://virtual-charity.run/virtual-charity-logo.jpg" />
        <meta property="fb:app_id" content="501944065799712" />

        <title>Virtual Charity Run @yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://api.mapy.cz/loader.js"></script>
        <script type="text/javascript">Loader.load();</script>
        <script src="https://js.stripe.com/v3/"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-b from-sky-300 to-sky-500">
            @include('layouts.navigation')


            <!-- Page Content -->
            <main class="px-3 sm:px-0">
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
        </div>

    </body>
</html>
