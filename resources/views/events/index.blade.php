@inject('carbon', 'Carbon\Carbon')


<x-app-layout>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                <div class="px-3 pt-3 text-gray-900">


                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif






@foreach ($events as $event)
    <div class="bg-gray-600  p-5 mb-3 text-3xl rounded-lg border border-gray-700 flex justify-between flex-col sm:flex-row shadow-lg">


    <div class="flex-1">
        <a class="block  sm:w-[250px] sm:inline-block border-solid border border-white hover:bg-red-700 hover:text-white rounded bg-blue-400 text-white text-xl px-4 py-2 mx-2 mb-5 sm:mb-0 text-center" href="{{ route('event.startlist.index',$event->id) }}">{{ $event->name }},  {{ $carbon::parse($event->date_start)->format('j.n') }}-{{ $carbon::parse($event->date_end)->format('j.n') }}</a>
    </div>


    @if($event->registration_status == 0)
        <a class="sm:w-[200px] sm:inline-block text-center border-solid border border-white hover:bg-red-700 hover:text-white  rounded  bg-red-400 text-white text-xl px-4 py-2 mx-2 mt-1 sm:mt-0" href="{{ route('registration.create',$event->id) }}">Zaregistrovat se</a>
    @else
        <a class="sm:w-[200px] sm:inline-block text-center border-solid border border-white hover:bg-red-700 hover:text-white rounded  bg-yellow-600 text-white text-xl px-4 py-2 mx-2 mt-1 sm:mt-0" href="{{ route('event.upload-url.create',$event->id) }}">Nahrát běh</a>
    @endif
        <a class="border-solid border border-white hover:bg-red-700 hover:text-white rounded bg-gray-500 text-white text-xl px-4 py-2 mx-2 mt-1 sm:mt-0 text-center" href="{{ route('event.startlist.index',$event->id) }}">Startovka</a>
        <a class="border-solid border border-white hover:bg-red-700 hover:text-white  rounded bg-green-500 text-white text-xl px-4 py-2 mx-2 mt-1 sm:mt-0 text-center" href="{{ route('event.result.index',$event->id) }}">Výsledky</a>

           </div>
           @endforeach



  <!-- Další řádky -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>




