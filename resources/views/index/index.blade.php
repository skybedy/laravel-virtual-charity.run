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






                <div class="sm:py-8 xl:py-4 md:px-4 flex flex-col sm:flex-row justify-around items-cente sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl mt-4"">

                    <div class="w-full   bg-black test flex flex-col space-y-3">

                        <div class="w-full  text-white text-center font-black bg-gray-600 rounded-xl text-4xl p-3">

                            Startovné 111 Kč

                        </div>


                        <div class="w-full  text-white text-center font-black bg-gray-600 rounded-xl text-4xl p-3">

                            Startovné 111 Kč

                        </div>

                    </div>



                    <div class="sm:py- xl:py- md:px- flx flex-co sm:flex-ow justify-arund iems-center bg-red-400">
                        <div class="w-full sm:w-44 md:w-56 lg:w-[19rem] xl:w-96  lg:h-96 xl:h-96 sm:rounded-2xl  text-white text-center font-black bg-gray-600 mt-100000 sm:mt-0 px-3 pt-4  sm:py-5 md:pt-0 pb-3 lg:pb-0">
                            <div class="text-4xl sm:text-[3.4rem] md:text-7xl lg:text-8xl xl:text-9xl  sm:mt-7 xl:mt- border-b border-white mx-3 lg:mx-7 mb-1">bla</div>
                        </div>
                    </div>

                </div>





















            @include('index.partials.event-list')

            <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl sm:p-4 mt-1 sm:mt-5">
                <div class="w-full sm:rounded-xl text-white  text-xl sm:text-4xl text-center font-black bg-red-500 sm:bg-gray-600 py-2 sm:py-5 ">
                    Startovné pro všechny 3 závody je 110 Kč a celé jde na účet charitativní organizace Z nesnáze 21
                </div>
            </div>

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
