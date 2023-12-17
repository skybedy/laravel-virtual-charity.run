<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="flex justify-center mt-5 sm:mt-0 sm:items-center">
        <div class="display-inline sm:max-w-md my-2 md:mr-5  px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border border-solid border-gray-200">
            <h3 class="bg-gray-100 -mt-4 -mx-6 p-3 text-base text-center font-bold border-b border-gray-200 border-solid text-gray-500">PŘIHLASTE SE</h3>
            <div class="mt-3"><a href="auth/facebook"><img class="img-fluid" src="facebook-login-icon.png" /></a></div>
            <div class="mt-3"><a href="auth/google"><img class="img-fluid" src="google-login-icon.png" /></a></div>
        </div>
    </div>
</x-guest-layout>

 
