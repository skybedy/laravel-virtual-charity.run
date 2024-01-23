<x-app-layout>
    <x-alert />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 bg-rd-500">

                   <x-event-navbar :event="$event" />

                    @if (session('error'))
                        @if(session('error') == 'registration_required')
                            @php $error = 'Nahrávat výsledky je možné až poté, co se k závodu <a class="underline" href="'.route('registration.create',$event->id).'">zaregistrujete</a>'; @endphp
                        @else
                            @php $error = session('error') @endphp
                        @endif

                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2">{!!$error!!}</div>
                    @endif




                    <div class="flex justify-between space-x-4 mt-5">

                        <div class="w-1/2">

                            <div>Buď vložte odkaz</div>

                            <form class="border  border-blue-400 rounded-md p-4 bg-slate-50" action="{{ route('event.upload.store.url',$event->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                    <div class="flex space-x-1">
                                        <input type="text" name="strava_url" class="w-full border border-blue-400 rounded-md py-[9px]">

                                        <button type="submit" class="flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-md">
                                            Nahrát
                                        </button>
                                    </div>
                                </form>

                                <div class="mt-5">
                                    <h3 class="text-gray-800 underline text-2xl">Příklady možných odkazů</h3>

                                    <div class="text-xl text-orange-700 font-black mt-4">1) https://www.strava.com/activities/123456789</div>
                                    <p> Toto je odkaz, který zkopírujete z adresního řádku prohlížeče (Chrome,Firefox,Edge, atd.), bez ohledu na to, jestli jste v počítači/notebooku, nebo na mobilu/tabletu a na stránce, na které je běh, který chcete nahrát do výsledků VirtualRunu  </p>
                                    <div class="mt-2 border"><img class="img-fluid" src="/strava-url-browser-example.png" /></div>
                                    <hr>
                                    <div class="text-orange-700 text-xl font-black mt-10">2) https://strava.app.link/abc123</div>
                                    <p>Narozdíl od prvního způsobu, kde se kopíruje odkaz z adesního řádku prohlížeče, tady se kopíruje z mobilní aplikace prostřednictvím sdílení do schránky, počemž pak vznikne odkaz ve tvaru uvedeném výše.<br>
                                     Vzhledem ke skutečnosti, že vývoj aplikací nejen od Stravy je rychlý, může se způsob sdílení do schránky lišit v závislosti na verzi aplikace, operačního systému, atd.<br> Zde je ve 2 krocích uveden příklad pro Android, pro iOS se to může v nějakých detailech lišit.</p>
                                    </p>
                                    <div class="mt-2"><img class="img-fluid" src="/strava-url-app-example.png" /></div>
                                </div>

                        </div>
                        <div class="w-1/2">
                            <div>Nebo nahrejte GPX Soubor</div>
                            <form class="border border-blue-400 rounded-md p-4 bg-slate-50" action="{{ route('event.upload.store',$event->id) }}" method="post" enctype="multipart/form-data" class="mt-5">
                                @csrf
                                <div class="flex space-x-2">

                                <input type="file" name="gpx_file" class="w-full border border-blue-400 rounded-md">

                            <button type="submit" class="flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-md">
                                Nahrát
                            </button>
                                        </div>


                    </form>




                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




