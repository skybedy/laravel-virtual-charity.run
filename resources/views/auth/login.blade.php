<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
        <div class="block md:flex b-red-200">
            <div class="w-ful sm:max-w-md my-2 md:mr-5  px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border border-solid border-gray-200">
            <h3 class="bg-gray-100 -mt-4 -mx-6 p-3 text-base text-center font-bold border-b border-gray-200 border-solid text-gray-500">MÁTE-LI UŽ ÚČET, PŘIHLASTE SE</h3>
            <p class="mt-5 italic">Přes svou sociální síť..</p>
            <div class="mt-3"><a href="auth/facebook"><img class="img-fluid" src="{{  Vite::asset('resources/images/facebook-login-icon.png') }}" /></a></div>
            <div class="mt-1"><a href="auth/google"><img class="img-fluid" src="{{  Vite::asset('resources/images/google-login-icon.png') }}" /></a></div>
            <hr class="mt-5">
            <p class="mt-5 italic">Nebo klasicky..</p>
            
            <form class="mt-4" method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                   
                    <x-input-label for="password" value="Heslo" class="mt-2" />
                    <x-text-input
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="password"
                    />

                    <x-big-danger-button class="mt-3 bg-green-500 hover:bg-green-800">Přihlásit</x-big-danger-button>
                </div>
            </form>
        </div>
        <div class="w-ful sm:max-w-md my-10 md:ml-5 md:my-2 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border border-solid border-gray-200"> 
            <h3 class="bg-gray-100 -mt-4 -mx-6 p-3 text-base text-center font-bold border-b border-gray-200 border-solid text-gray-500">NEMÁTE-LI ÚČET, ZAREGISTRUJTE SE</h3>
            <a href="{{ route('register') }}" class="mt-5 md:mt-32 md:py-20 w-full inline-block items-center px-1 py-3 mb-1 border border-transparent rounded-md font-semibold text-xl text-white uppercase tracking-widest hover:bg-blue-800 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 text-center bg-red-500">Registrovat</a>
        </div>
    </div>
</x-guest-layout>