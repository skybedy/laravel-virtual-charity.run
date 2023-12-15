<x-guest-layout>
    <div class="w-ful sm:max-w-md my-2 md:mr-5  px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border border-solid border-gray-200">
        <p class="mt-5 italic">Přes svou sociální síť..</p>
        <div class="mt-3"><a href="auth/facebook"><img class="img-fluid" src="{{  Vite::asset('resources/images/facebook-login-icon.png') }}" /></a></div>
        <div class="mt-1"><a href="auth/google"><img class="img-fluid" src="{{  Vite::asset('resources/images/google-login-icon.png') }}" /></a></div>
        <hr class="mt-5">
        <p class="mt-5 italic">Nebo klasicky..</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mt-4">
                <x-input-label for="firstname" :value="__('Jméno')" />
                <x-text-input id="firstname" class="mt-1 block w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="firstname" />
                <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
            </div>
            <div class="mt-4">
                <x-input-label for="lastname" :value="__('Příjmení')" />
                <x-text-input id="lastname" class="mt-1 block w-full" type="text" name="lastname" :value="old('lastname')" required autofocus autocomplete="lastname" />
                <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
            </div>
            <div class="mt-4">
                <x-input-label for="gender" value="Pohlaví" />
                <select id="gender" name="gender" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                    <option value="" selected disabled></option>
                    <option value="F">Žena</option>
                    <option value="M">Muž</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            <div class="mt-4">
                    <x-input-label for="birth_year" value="Ročník" />
                    <select id="birth_year" name="birth_year" v-model="form.birth_year" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" selected disabled></option>
                        <x-birth-year-option :first_year="$first_year" :last_year="$last_year"></x-birth-year-option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('birthyear')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" autocomplete="email" required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Heslo')" />
                    <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" autocomplete="new-password" required />
                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Už jste zaregistrováni?</a>
                    <x-primary-button class="ml-4">Registrovat</x-primary-button>
                </div>
            </form>
        </div>
</x-guest-layout>
