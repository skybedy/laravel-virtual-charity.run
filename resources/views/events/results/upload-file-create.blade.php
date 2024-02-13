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

                    <div class="mt-10">
                        <div>Zde nahrajte GPX Soubor:</div>
                        <form class="border border-blue-400 rounded-md p-4 bg-slate-50" action="{{ route('event.upload.store',$event->id) }}" method="post" enctype="multipart/form-data" class="mt-5">
                            @csrf
                            <div class="flex space-x-2">
                                <input type="file" name="gpx_file" class="w-full border border-blue-400 rounded-md">
                                <button type="submit" class="flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-md">Nahrát</button>
                            </div>
                        </form>
                    </div>
                    <p class="mt-5">Soubor GPX je standardizovaný formát, který lze získat u konkrétní aktivity jak na Stravě a Garmin Connectu, tak i u jiných služeb zabývajích se monitorováním sportovních aktivit</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




