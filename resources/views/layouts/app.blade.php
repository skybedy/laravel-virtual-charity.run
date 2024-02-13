<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>virtual-run.cz @yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://api.mapy.cz/loader.js"></script>
        <script type="text/javascript">Loader.load();</script>

        <style>
            #m img {
                max-width: none;
            }

            input[type=file] {
  widh: 100%;
  mx-width: 100%;
  color: #444;
  padding: 5px;
  background: #fff;
  border-radius: 6px;
  bordr: 1px solid blue;
}

input[type=file]::file-selector-button {
  margin-right: 20px;
  border: none;
  background: #084cdf;
  padding: 4px 20px;
  border-radius: 6px;
  color: #fff;
  cursor: pointer;
  transition: background .2s ease-in-out;
}

input[type=file]::file-selector-button:hover {
  background: #0d45a5;
}
        </style>



    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen sm:bg-slate-200">
            @include('layouts.navigation')


            <!-- Page Content -->
            <main>
                <div class="bg-blue-400 text-white font-black py-2 text-center border-y  border-blue-500 mt-2 shadow-lg">Aplikace je zatím v testovacím režimu.</div>

                {{ $slot }}
            </main>
        </div>

    </body>
</html>
