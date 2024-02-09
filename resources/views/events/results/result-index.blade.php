<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-event-navbar :event="$event" />
                    <div class="overflow-auto">
                        <table id="result_table" class="hidden md:table table-auto border-collapse w-full mt-5">
                            <tr class="">
                                <th class="border-none">Pořadí</th>
                                <th class="border-none text-left px-2">Jméno</th>
                                <th class="border-none text-left px-2">Tým||Město||Obec</th>
                                <th class="border-none text-left px-2">Kategorie</th>
                                <th class="border-none text-left px-2">Místo&&Mapa</th>
                                <th class="border-none">Tempo</th>
                                <th class="border-none">Čas</th>
                                <th class="border-none">Rozdíl</th>
                            </tr>
                            @foreach ($results as $result )
                                @if($loop->iteration == 1)
                                    @php
                                        $best_time = $result->best_finish_time_sec;
                                    @endphp
                                @endif
                                <tr class="odd:bg-gray-100 even:bg-white" id="result_{{ $result->id }}">
                                    <td class="border text-center">{{ $loop->iteration }}</td>
                                    <td class="border px-2">
                                        @if($result->count > 1)

                                            <a href="{{ route('result.user',$result->registration_id) }}" class="text-blue-700 underline">{{ $result->lastname }} {{ $result->firstname }}</a>
                                        @else
                                        {{ $result->lastname }} {{ $result->firstname }}
                                        @endif
                                    </td>
                                    <td class="border px-2">{{ $result->team }}</td>
                                    <td class="border px-2">{{ $result->category_name }}</td>
                                    <td class="border px-2">{{ $result->date }} <a class="test_tr underline text-blue-700" href="{{ route('result.map',$result->id) }}">mapa</a></td>
                                    <td class="border text-center">{{ $result->pace }}</td>
                                    <td class="border text-center">{{ $result->best_finish_time }}</td>
                                    <td class="border text-center">{{ dynamic_distance($loop->iteration,$result->best_finish_time_sec,$best_time) }}</td>
                                </tr>
                            @endforeach
                        </table>
                        <table id="result_table_sm" class="md:hidden table-auto border-collapse w-full mt-5 text-xs">
                            <tr>
                                <th class="border-none">#</th>
                                <th class="border-none text-left px-2">Jméno</th>
                                <th class="border-none text-left px-2">Datum/Mapa</th>
                                <th class="border-none">Čas</th>
                                <th class="border-none">Tempo</th>
                            </tr>
                            @foreach ($results as $result )
                                @if($loop->iteration == 1)
                                    @php
                                        $best_time = $result->best_finish_time_sec;
                                    @endphp
                                @endif
                                <tr class="odd:bg-gray-100 even:bg-white" id="result_{{ $result->id }}">
                                    <td class="border text-center">{{ $loop->iteration }}</td>
                                    <td class="border px-2">{{ $result->lastname }} {{ $result->firstname }}</td>
                                    <td class="border px-2">{{ $result->date }}, <a class="test_tr underline text-blue-700" href="{{ route('result.map',$result->id) }}">mapa</a></td>
                                    <td class="border text-center">{{ $result->best_finish_time }}</td>
                                    <td class="border text-center">{{ $result->pace }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>



        <script>

          $('a').on('click', function () {
      var value = gpx_test_file.trim();
      //console.log(value);
      //if (!value) { return alert("Vložte do textového pole obsah GPX souboru"); }
      var m = new SMap(JAK.gel("m"));
      var xmlDoc = JAK.XML.createDocument(value);

      var gpx = new SMap.Layer.GPX(xmlDoc, null, {maxPoints:500}); /* GPX vrstva */
      m.addDefaultLayer(SMap.DEF_BASE).enable();
      m.addLayer(gpx); /* Přidáme ji do mapy */
      m.addDefaultControls();
      gpx.enable();    /* Zapnout vrstvu */
      gpx.fit();



        return false;
    })






