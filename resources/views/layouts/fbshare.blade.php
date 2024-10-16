<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta property="og:url" content="https://virtual-charity.run/about" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Virtual Charity Run test" />
        <meta property="og:description" content="virtualni zavodeni" />
        <meta property="og:image" content="https://virtual-charity.run/images/strava-logo.png" />

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://api.mapy.cz/loader.js"></script>
        <script type="text/javascript">Loader.load();</script>
    </head>
    <body class="font-sans antialiased">
         {{ $slot }}
    </body>
</html>
