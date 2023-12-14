@inject('carbon', 'Carbon\Carbon')


<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


                      



@foreach ($events as $event)
<div class="bg-gray-50 my-5 p-5 text-3xl rounded-lg border border-gray-200 flex justify-between flex-col sm:flex-row">


<div class="flex-1">
    <a class="block  sm:w-[250px] sm:inline-block border-solid border border-blue-500 hover:bg-red-700 hover:text-white text-red-700  px-2 rounded bg-blue-400 text-white text-xl px-4 py-2 mx-2 mb-5 sm:mb-0 text-center" href="{{ route('event.startlist.index',$event->id) }}">{{ $event->name }},  {{ $carbon::parse($event->date_start)->format('j') }}-{{ $carbon::parse($event->date_end)->format('j.n.y') }}</a>
</div> 
  
    
    @if($event->registration_status == 0)
        <a class="sm:w-[200px] sm:inline-block text-center border-solid border border-red-500 hover:bg-red-700 hover:text-white text-red-700  px-3 rounded  bg-red-400 text-white text-xl px-4 py-2 mx-2 mt-1 sm:mt-0" href="{{ route('registration.create',$event->id) }}">Zaregistrovat se</a>
    @else
        <a class="sm:w-[200px] sm:inline-block text-center border-solid border border-yellow-500 hover:bg-red-700 hover:text-white text-red-700  px-2 rounded  bg-yellow-400 text-white text-xl px-4 py-2 mx-2 mt-1 sm:mt-0" href="{{ route('event.upload.create',$event->id) }}">Nahrát běh</a>
    @endif
        <a class="border-solid border border-gray-500 hover:bg-red-700 hover:text-white text-red-700  px-2 rounded bg-gray-400 text-white text-xl px-4 py-2 mx-2 mt-1 sm:mt-0 text-center" href="{{ route('event.startlist.index',$event->id) }}">Startovka</a>
        <a class="border-solid border border-green-500 hover:bg-red-700 hover:text-white text-red-700  px-2 rounded bg-green-400 text-white text-xl px-4 py-2 mx-2 mt-1 sm:mt-0 text-center" href="{{ route('event.result.index',$event->id) }}">Výsledky</a>

           </div>
           @endforeach


 
  <!-- Další řádky -->



















                </div>
            </div>
        </div>
    </div>
</x-app-layout>




