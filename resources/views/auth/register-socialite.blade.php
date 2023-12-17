<div class="bg-red-100 text-red-500 py-2 text-center border-b border-t border-red-300 mt-1">Pokud jste se ocitli na této stránce, tak tu jste patrně poprvé a v tom případě je jednou jedinkrát potřeba doplnit rok narození a pohlaví pro správné zařazení do věkové kategorie.</div>
<div class="bg-red-100 text-red-500 py-2 text-center border-b border-t border-red-300 mt-1">V případě, že nechcete uvádět rok narození, budete automaticky zařazeni do kategorie OPEN 23-39 let.</div>

<x-guest-layout>
    <div class="w-ful sm:max-w-md my-2 md:mr-5  px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border border-solid border-gray-200">
        <div class="mt-3">
            <img class="img-fluid" src="{{$provider}}-login-icon.png" />           
        </div>
        <form method="POST" action="{{ route('register-socialite') }}">
            @csrf 
             <x-text-input  id="provider_name" type="hidden" name="provider_name" :value="$provider" />
             <x-text-input  id="provider_id" type="hidden" name="provider_id" :value="$id" />
            <div class="mt-4">
                <x-input-label for="firstname" :value="__('Jméno')" />
                <x-text-input id="firstname" class="mt-1 block w-full" type="text" name="firstname" :value="$firstname" required autofocus autocomplete="firstname" />
                <x-input-error class="mt-2" :messages="$errors->get('firstname')" />
            </div>
            <div class="mt-4">
                <x-input-label for="lastname" :value="__('Příjmení')" />
                <x-text-input id="lastname" class="mt-1 block w-full" type="text" name="lastname" :value="$lastname" required autofocus autocomplete="lastname" />
                <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
            </div>
            <div class="mt-4">
                <x-input-label for="team" :value="__('Tým/Město/Obec - nepovinné')" />
                <x-text-input id="team" class="mt-1 block w-full" type="text" name="team"  autofocus autocomplete="team" />
                <x-input-error class="mt-2" :messages="$errors->get('team')" />
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
                    <x-input-label for="birth_year" value="Rok narození" />
                    <select id="birth_year" name="birth_year" v-model="form.birth_year" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" selected disabled></option>
                        <option value="1901" class="text-red-600">Nechci uvádět rok narození</option>
                        <x-birth-year-option :first_year="$first_year" :last_year="$last_year"></x-birth-year-option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('birthyear')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="$email" autocomplete="email" required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Už jste zaregistrováni?</a>
                    <x-primary-button class="ml-4">Registrovat</x-primary-button>
                </div>
            </form>
        </div>
</x-guest-layout>
