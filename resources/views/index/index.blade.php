@inject('carbon', 'Carbon\Carbon')

@section('title', '| Hlavní strana')

<x-app-layout>
    <div class="smxxx:py-24 pb-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif


            <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl sm:p-4 mt-1 sm:mt-5">
                <div class="w-full sm:rounded-xl text-white  text-xl sm:text-4xl text-center font-black bg-red-500 sm:bg-gray-600 py-2 sm:py-5 ">
                    100% FREE
                </div>
            </div>

            @include('index.partials.event-list')

                <ol class="text-4xl font-bold list-decimal list-inside mt-4">

                    <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl sm:p-4 text-xl sm:text-4xl">
                        <div class="w-full sm:rounded-xl text-white text-xl sm:text-4xl text-center font-black bg-gray-600 py-5 ">
                            <li>Pokud nejste, tak se <a class="underline text-red-500" href="{{ route('login') }}">přihlaste</a> přes FB, nebo Google..</li>
                        </div>
                    </div>
                    <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl sm:p-4 mt-4 text-xl sm:text-4xl">
                        <div class="w-full sm:rounded-xl text-white  text-xl sm:text-4xl text-center font-black bg-gray-600 py-5 ">
                            <li>Pokud nemáte, tak si <a class="underline text-red-500" href={{ route('authorize_strava') }}>povolte</a> STRAVU..</li>
                        </div>
                    </div>
                    <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl sm:p-4 mt-4 text-xl sm:text-4xl">
                        <div class="w-full sm:rounded-xl text-white text-xl sm:text-4xl text-center font-black bg-gray-600 py-5 ">
                            <li>Pokud nejste, tak se registrujte se k závodu, nebo závodům..</li>
                        </div>
                    </div>
                    <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl sm:p-4 mt-4 text-xl sm:text-4xl">
                        <div class="w-full sm:rounded-xl text-white  text-xl sm:text-4xl text-center font-black bg-gray-600 py-5 ">
                            <li>A to je vše - i když možná si ještě raději přečtěte, <a class="underline text-red-500" href="{{ route('how_it_works.index') }}">jak na to..</a></li>
                        </div>
                    </div>


                </ol>





















        </div>
    </div>
</x-app-layout>
