<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Strava login') }}
        </h2>

    </header>


    <x-nav-link href="https://www.strava.com/oauth/authorize?client_id=117954&response_type=code&redirect_uri=https://virtual-run.cz/redirect-strava/{{$user->id}}&approval_prompt=force&scope=activity:read_all" class="text-blue-600">
        {{ __('Povolení Strava') }}
    </x-nav-link>


</section>
