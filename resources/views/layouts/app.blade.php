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


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://api.mapy.cz/loader.js"></script>
        <script type="text/javascript">Loader.load();</script>
        <script src="https://js.stripe.com/v3/"></script>

        <style>

@import url('https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap');
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

.indie-flower-regular {
  font-family: "Indie Flower", cursive;
  font-weight: 400;
  font-style: normal;
}



        </style>



    </head>
    <body class="font-sans antialiased">
<<<<<<< HEAD
        <div class="min-h-screen  bg-blue-500 sm:bg-gradient-to-b sm:from-blue-200 sm:to-blue-400">
=======
        <div class="min-h-screen bg-sky-300 bg-none sm:bg-gradient-to-b sm:from-blue-200 sm:to-blue-400">
>>>>>>> 69e5517 (Uprava vrchnich boxu)
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
