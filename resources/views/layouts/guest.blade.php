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
    </head>
    <body class="font-sans text-gray-900 antialiased">
        
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#fefdf9]">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>
            <div class="mt-3">
                         <svg  version="1.0" xmlns="http://www.w3.org/2000/svg"
                        width="160.000000pt" height="20.000000pt" viewBox="0 0 535.000000 67.000000"
                        preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,67.000000) scale(0.100000,-0.100000)"
                        fill="#000000" stroke="none">
                        <path d="M432 653 c-49 -5 -47 -23 3 -23 l33 0 -2 -47 c-1 -27 -3 -130 -4
                        -230 l-2 -183 -24 0 c-13 0 -26 -7 -30 -15 -4 -11 1 -15 19 -15 14 0 25 -4 25
                        -10 0 -12 74 -12 110 0 14 4 19 8 13 9 -7 1 -13 8 -13 17 0 9 -9 18 -20 21
                        -15 4 -20 16 -23 62 l-3 56 -2 -57 c-1 -32 -6 -58 -12 -58 -6 0 -10 41 -10
                        104 0 57 -2 125 -5 152 -4 48 -4 48 9 -11 l13 -60 2 118 1 118 31 11 c20 7 28
                        15 25 25 -3 8 -6 17 -6 19 0 4 -59 3 -128 -3z m45 -440 c-2 -21 -4 -6 -4 32 0
                        39 2 55 4 38 2 -18 2 -50 0 -70z"/>
                        <path d="M642 611 c-79 -61 -73 -53 -79 -92 -4 -29 -3 -31 11 -20 8 7 18 21
                        21 32 5 16 52 49 70 49 11 0 5 -216 -7 -228 -9 -9 -10 -17 -2 -30 6 -9 12 -53
                        13 -97 1 -44 5 -83 9 -87 4 -4 6 28 6 70 l-1 77 7 -70 c7 -76 15 -53 11 33 -2
                        33 1 52 8 52 7 0 11 -29 11 -82 0 -46 4 -77 8 -70 5 7 7 42 6 78 -5 107 12
                        117 56 31 19 -36 20 -39 3 -27 -10 8 -7 2 7 -13 13 -15 30 -25 37 -23 7 3 15
                        0 19 -6 5 -7 2 -8 -7 -3 -9 5 -10 5 -4 -1 6 -6 38 -23 72 -39 66 -30 95 -27
                        53 5 -17 13 -20 20 -10 20 8 0 -8 13 -35 29 -28 16 -74 50 -103 76 l-53 48 52
                        28 c28 16 58 34 66 40 20 17 78 119 87 153 7 30 -9 86 -26 86 -5 0 -2 -10 6
                        -22 9 -12 16 -27 16 -33 -1 -5 -8 4 -17 20 -10 17 -16 33 -15 38 4 15 -45 24
                        -138 25 l-95 0 -63 -47z m253 -24 c28 -14 35 -27 35 -65 0 -26 -10 -42 -45
                        -77 -42 -41 -140 -89 -157 -78 -5 2 -8 53 -8 113 0 95 2 109 18 113 30 9 136
                        5 157 -6z m-187 -197 c0 -22 -1 -22 -8 5 -4 17 -8 41 -8 55 0 22 1 22 8 -5 4
                        -16 8 -41 8 -55z"/>
                        <path d="M1210 643 c-14 -7 -55 -13 -92 -13 -37 0 -69 -4 -73 -9 -3 -6 10 -8
                        32 -6 34 4 35 4 8 -4 -16 -4 -46 -6 -65 -3 -29 4 -33 2 -22 -9 8 -9 42 -13
                        110 -14 l97 0 -1 -185 c-1 -102 1 -167 4 -145 5 38 6 36 18 -35 l13 -75 -5
                        125 c-2 69 -6 145 -9 170 l-4 45 10 -40 c6 -22 14 -71 19 -110 6 -52 7 -34 4
                        67 -7 191 -9 184 85 191 97 8 138 22 122 41 -9 11 -9 15 1 19 7 2 -41 4 -107
                        3 -73 0 -131 -6 -145 -13z"/>
                        <path d="M1955 607 c-13 -30 -40 -106 -59 -170 -26 -81 -42 -117 -53 -119 -14
                        -3 -17 -19 -19 -106 -1 -56 0 -102 1 -102 2 0 11 26 20 58 28 96 34 102 115
                        102 66 0 70 -1 71 -22 1 -13 3 -63 5 -112 4 -92 13 -129 15 -60 l1 39 14 -44
                        c8 -25 18 -49 24 -55 13 -13 13 83 0 184 -9 66 -8 76 7 88 15 11 15 12 0 12
                        -10 0 -18 11 -22 33 -5 31 -25 239 -25 264 0 13 -50 63 -63 63 -4 0 -19 -24
                        -32 -53z m47 -9 c-9 -9 -12 -7 -12 12 0 19 3 21 12 12 9 -9 9 -15 0 -24z m-37
                        -84 c-23 -61 -28 -51 -8 15 10 29 19 48 21 41 2 -6 -4 -32 -13 -56z m70 -104
                        c3 -41 3 -70 -2 -63 -6 8 -12 4 -20 -13 -8 -18 -8 -24 0 -24 7 0 5 -5 -3 -10
                        -8 -5 -28 -9 -45 -9 l-30 0 25 12 c24 11 24 11 -12 15 -21 2 -38 4 -38 6 0 11
                        63 173 71 181 5 5 9 -15 9 -55 0 -35 3 -60 7 -57 3 4 5 34 4 68 -1 34 2 71 6
                        83 6 18 8 15 14 -19 4 -22 10 -74 14 -115z"/>
                        <path d="M2198 638 c-9 -16 -14 -87 -16 -238 -3 -149 -7 -217 -15 -222 -9 -6
                        -8 -8 1 -8 7 0 12 -8 10 -17 -3 -19 0 -19 117 -13 l50 2 -40 13 -40 13 46 1
                        c25 1 61 -6 78 -15 22 -11 48 -15 77 -11 51 5 49 4 58 33 9 29 -26 35 -131 23
                        -45 -6 -97 -9 -115 -7 -32 3 -33 4 -39 58 l-6 55 -2 -60 c-4 -100 -18 -34 -23
                        110 -5 121 -4 126 7 70 12 -58 12 -56 14 88 1 147 -5 171 -31 125z m-1 -425
                        c-2 -16 -4 -3 -4 27 0 30 2 43 4 28 2 -16 2 -40 0 -55z"/>
                        <path d="M3062 611 c-79 -61 -73 -53 -79 -92 -4 -29 -3 -31 11 -20 8 7 18 21
                        21 32 5 16 52 49 70 49 11 0 5 -216 -7 -228 -9 -9 -10 -17 -2 -30 6 -9 12 -53
                        13 -97 1 -44 5 -83 9 -87 4 -4 6 28 6 70 l-1 77 7 -70 c7 -76 15 -53 11 33 -2
                        33 1 52 8 52 7 0 11 -29 11 -82 0 -46 4 -77 8 -70 5 7 7 42 6 78 -5 107 12
                        117 56 31 19 -36 20 -39 3 -27 -10 8 -7 2 7 -13 13 -15 30 -25 37 -23 7 3 15
                        0 19 -6 5 -7 2 -8 -7 -3 -9 5 -10 5 -4 -1 6 -6 38 -23 72 -39 66 -30 95 -27
                        53 5 -17 13 -20 20 -10 20 8 0 -8 13 -35 29 -28 16 -74 50 -103 76 l-53 48 52
                        28 c28 16 58 34 66 40 20 17 78 119 87 153 7 30 -9 86 -26 86 -5 0 -2 -10 6
                        -22 9 -12 16 -27 16 -33 -1 -5 -8 4 -17 20 -10 17 -16 33 -15 38 4 15 -45 24
                        -138 25 l-95 0 -63 -47z m253 -24 c28 -14 35 -27 35 -65 0 -26 -10 -42 -45
                        -77 -42 -41 -140 -89 -157 -78 -5 2 -8 53 -8 113 0 95 2 109 18 113 30 9 136
                        5 157 -6z m-187 -197 c0 -22 -1 -22 -8 5 -4 17 -8 41 -8 55 0 22 1 22 8 -5 4
                        -16 8 -41 8 -55z"/>
                        <path d="M4581 639 c-90 -55 -188 -196 -221 -318 -17 -64 -5 -147 26 -178 24
                        -24 40 -26 129 -13 6 1 1 5 -10 9 -17 7 -17 8 7 13 15 3 25 1 22 -3 -7 -13 -1
                        -11 48 10 56 25 168 142 168 176 0 5 -11 15 -25 22 -24 11 -27 9 -44 -25 -29
                        -57 -86 -105 -181 -154 -43 -23 -62 -23 -90 0 -18 14 -22 28 -22 69 0 27 3 61
                        7 74 8 23 8 23 16 4 7 -19 8 -19 15 5 14 46 83 175 114 213 59 70 120 83 120
                        26 0 -20 -12 -40 -41 -69 -38 -38 -41 -43 -26 -57 13 -14 20 -14 47 -3 17 7
                        35 20 40 30 5 9 34 30 65 46 30 16 55 32 55 37 0 10 -49 9 -89 -3 -27 -7 -32
                        -7 -23 2 17 17 15 74 -4 92 -21 22 -63 20 -103 -5z m13 -434 c-39 -31 -58 -35
                        -25 -6 19 17 37 28 40 26 2 -3 -4 -12 -15 -20z"/>
                        <path d="M5220 644 c-59 -15 -160 -16 -197 -3 -17 6 -25 4 -32 -10 -15 -27 25
                        -41 131 -48 48 -3 88 -9 88 -13 0 -17 -172 -228 -272 -333 -76 -80 -82 -89
                        -65 -99 22 -12 257 -2 332 14 82 17 81 16 56 39 -20 19 -27 20 -134 10 -175
                        -17 -166 -21 -113 47 25 32 46 62 46 66 0 10 112 138 167 191 53 51 113 124
                        113 137 0 6 -7 8 -15 4 -8 -3 -15 -1 -15 4 0 13 -23 11 -90 -6z m70 -57 c0 -2
                        -16 -19 -37 -38 -37 -35 -37 -23 0 19 18 19 37 29 37 19z"/>
                        <path d="M1492 598 c-20 -91 -24 -196 -12 -291 17 -139 31 -179 65 -196 34
                        -16 48 -9 76 39 12 20 13 25 3 12 -36 -48 -64 -13 -84 108 -9 51 -10 116 -5
                        208 5 96 4 132 -4 128 -7 -5 -11 3 -11 18 0 44 -16 29 -28 -26z m38 -312 c0
                        -3 -4 -8 -10 -11 -5 -3 -10 -1 -10 4 0 6 5 11 10 11 6 0 10 -2 10 -4z m19
                        -146 c0 -3 -6 6 -14 20 -8 14 -14 41 -14 60 1 31 2 29 14 -20 8 -30 14 -57 14
                        -60z"/>
                        <path d="M1796 613 c-3 -21 -10 -76 -16 -123 -6 -47 -14 -98 -17 -115 -7 -29
                        -7 -28 -13 14 -4 24 -3 65 1 90 22 121 22 116 6 103 -13 -12 -30 -90 -42 -205
                        -4 -38 -19 -81 -42 -125 -22 -42 -29 -61 -17 -52 16 14 16 13 6 -7 -7 -12 -12
                        -28 -11 -35 0 -7 11 9 25 37 37 74 44 77 45 15 0 -46 26 -148 28 -115 2 28 42
                        323 57 423 13 80 14 116 7 123 -8 8 -13 0 -17 -28z"/>
                        <path d="M3462 598 c-20 -91 -24 -196 -12 -291 17 -139 31 -179 65 -196 34
                        -16 48 -9 76 39 12 20 13 25 3 12 -36 -48 -64 -13 -84 108 -9 51 -10 116 -5
                        208 5 96 4 132 -4 128 -7 -5 -11 3 -11 18 0 44 -16 29 -28 -26z m38 -312 c0
                        -3 -4 -8 -10 -11 -5 -3 -10 -1 -10 4 0 6 5 11 10 11 6 0 10 -2 10 -4z m19
                        -146 c0 -3 -6 6 -14 20 -8 14 -14 41 -14 60 1 31 2 29 14 -20 8 -30 14 -57 14
                        -60z"/>
                        <path d="M3766 613 c-3 -21 -10 -76 -16 -123 -6 -47 -14 -98 -17 -115 -7 -29
                        -7 -28 -13 14 -4 24 -3 65 1 90 22 121 22 116 6 103 -13 -12 -30 -90 -42 -205
                        -4 -38 -19 -81 -42 -125 -22 -42 -29 -61 -17 -52 16 14 16 13 6 -7 -7 -12 -12
                        -28 -11 -35 0 -7 11 9 25 37 37 74 44 77 45 15 0 -46 26 -148 28 -115 2 28 42
                        323 57 423 13 80 14 116 7 123 -8 8 -13 0 -17 -28z"/>
                        <path d="M4138 618 l-11 -32 -11 25 c-8 20 -11 7 -17 -71 -17 -244 -22 -305
                        -24 -308 -6 -7 -188 346 -192 375 -6 36 -27 43 -42 14 -15 -26 -25 -425 -13
                        -481 9 -39 10 -32 7 45 l-4 90 12 -75 c16 -109 21 -45 8 114 -6 71 -9 132 -6
                        134 3 3 5 -8 5 -24 0 -16 5 -36 10 -44 7 -11 10 2 11 45 0 33 4 71 8 84 6 22
                        9 19 33 -35 14 -32 31 -77 38 -99 12 -43 75 -173 124 -258 25 -45 30 -49 37
                        -32 4 11 8 14 8 8 2 -22 17 -14 18 10 1 12 2 93 2 179 0 87 4 165 9 175 16 30
                        26 193 13 193 -7 0 -17 -14 -23 -32z"/>
                        <path d="M340 629 c0 -5 -4 -8 -9 -4 -10 6 -86 -183 -141 -349 -29 -88 -34
                        -97 -46 -81 -7 10 -16 38 -19 64 -14 106 -75 342 -94 363 -3 4 -12 3 -20 -1
                        -12 -8 -11 -25 8 -113 37 -177 79 -331 92 -338 6 -5 15 -24 18 -43 5 -31 8
                        -34 29 -26 31 12 61 81 138 323 68 213 69 216 54 216 -5 0 -10 -5 -10 -11z
                        m-60 -164 c-12 -36 -24 -64 -26 -62 -3 2 5 34 17 71 12 36 24 64 26 62 3 -3
                        -5 -35 -17 -71z"/>
                        <path d="M3871 289 c1 -35 5 -89 9 -119 6 -37 6 -18 3 60 -6 125 -14 163 -12
                        59z"/>
                        <path d="M2641 310 c-62 -9 -96 -42 -53 -52 33 -8 405 4 391 13 -8 5 -27 9
                        -44 10 -23 2 -19 3 14 8 25 3 46 12 49 19 3 9 -31 12 -150 11 -84 -1 -177 -5
                        -207 -9z"/>
                        <path d="M1263 195 c0 -33 2 -45 4 -27 2 18 2 45 0 60 -2 15 -4 0 -4 -33z"/>
                        <path d="M1212 160 c0 -19 2 -27 5 -17 2 9 2 25 0 35 -3 9 -5 1 -5 -18z"/>
                        <path d="M4244 152 c-50 -42 -37 -102 21 -102 59 0 79 54 37 103 -19 21 -31
                        21 -58 -1z"/>
                        </g>
                        </svg>
            </div>
            
            {{ $slot }}
        </body>
</html>
