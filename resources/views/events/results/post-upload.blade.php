<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 bg-rd-500">
                    
                   <x-event-navbar :event="$event" />
                    
                    @if (session('error'))c
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2">{{ session('error') }}</div>
                    @endif

                  <div class="bg-blue-50 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mt-2">
                        @if($rank == 1)
                              Je to tvůj nejlepší čas v tomto závodě a jako takový se bude započítávat do celkových výsledků, na které se můžeš podívat <a href="{{ route('event.result.index',$event->id) }}">zde</a>.

                        @else
                              Je to celkově tvůj {{ $rank }}. čas v tomto závodě a do celkových výsledků se započítávat nebude, seznam všech časů je v tabulce a na celkové výsledky se můžeš podívat <a href="{{ route('event.result.index',$event->id) }}">zde</a>. 
                        @endif
                  </div>
                  
                  
                  <table id="result_table" class="hidden md:table table-auto border-collapse  mt-5">
                        <tr>
                           <th class="border-none text-left px-2">Poř</th>
                            <th class="border-none text-left px-2">Datum</th>
                            <th class="border-none text-left px-2">Tempo</th>
                            <th class="border-none px-1">Čas</th>
                        </tr>

                    @foreach ($results as $result)
                        @if($result->id == $last_id)
                               <tr class="odd:bg-gray-100 even:bg-white text-red-600" id="result_{{ $result->id }}">
                        @else
                              <tr class="odd:bg-gray-100 even:bg-white" id="result_{{ $result->id }}">
                        @endif
                        
                              <td class="border text-center">{{ $loop->iteration }}</td>
                              <td class="border px-2 text-center">{{ $result->date }}</td>
                              <td class="border px-2">{{ $result->pace }}</td>
                              <td class="border text-center px-2">{{ $result->finish_time }}</td>
                        </tr>   
                  @endforeach

                  </table>



               
                
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




