<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-2 text-gray-900 bg-rd-500">
                    <div class="homepage-box">
                        <x-event-navbar :event="$event" :all_same_serie_events="$all_same_serie_events" />
                        <table class="border-collapse w-full mt-5 text-xs sm:text-base">
                            <tr>
                                <th class="border-none px-2">Stč</th>
                                <th class="border-none text-left px-2">Jméno</th>
                                <th class="border-none text-left px-2">Tým/Město/Obec</th>
                                <th class="border-none">Kategorie</th>
                            </tr>
                            @foreach ($startlists as $startlist )
                                <tr class="odd:bg-gray-100 even:bg-white">
                                    <td class="border px-2 py-1 text-center">{{ $startlist->ids }}</td>
                                    <td class="border px-2 py-1">{{ $startlist->lastname }} {{ $startlist->firstname }}</td>
                                    <td class="border px-2 py-1">{{ $startlist->team }}</td>
                                    <td class="border text-center">{{ $startlist->name }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>




 p
