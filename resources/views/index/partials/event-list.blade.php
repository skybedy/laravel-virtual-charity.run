
<div class="sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl sm:mt-4">
    <div class="py-4 md:px-2 flex flex-col sm:flex-row justify-around items-center mt-1 sm:mt-0">
        @foreach ($events as $event)
            <div class="w-full sm:w-44 md:w-56 lg:w-[19rem] xl:w-96  sm:rounded-2xl  text-white text-center font-black bg-gray-600 mt-8 sm:mt-0 pt-4 pb-6">
                <div class="text-4xl sm:text-[3.4rem] md:text-7xl lg:text-8xl xl:text-9xl  sm:mt-7 xl:mt-3 border-b border-white mx-3 lg:mx-7 mb-1">{{ $event->name }}</div>

                <div class="px-5 text-white text-center flex sm:flex-col justify-between space-x-2 sm:space-x-0 text-sm md:text-xl">
                    <a class="w-1/3 sm:w-full inline-block rounded  bg-blue-400 hover:bg-blue-500 px-4 py-6 sm:py-2  mt-4 sm:mt-3" href="{{ route('event.result.index',$event->id) }}">Výsledky</a>
                    <a class="w-1/3 sm:w-full inline-block rounded  bg-yellow-400 hover:bg-yellow-500  px-4 py-6 sm:py-2 mt-4 sm:mt-3" href="{{ route('event.startlist.index',$event->id) }}">Startovka</a>
                    @if($event->registration_status == 0)
                        <a class="w-1/3 sm:w-full inline-block rounded  bg-red-400 hover:bg-red-500  px-4 py-6 sm:py-2  mt-4 sm:mt-3" href="{{ route('registration.create',$event->id) }}">Zaregistrovat se</a>
                    @else
                        <a class="w-full sm:w-full inline-block rounded  bg-green-400 hover:bg-gray-500 md:text-xl px-4 py-6 sm:py-2 mt-4 sm:mt-3" href="{{ route('event.show',$event->id) }}">Administrace</a>
                    @endif
                </div>

            </div>
        @endforeach
    </div>
</div>
