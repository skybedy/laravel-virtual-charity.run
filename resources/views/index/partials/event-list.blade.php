


<div class="bg-white overflow-hidden shadow-sm rounded-2xl mt-2 sm:mt-4 p-2 sm:p-4 flex flex-col gap-y-2 sm:gap-y-0 sm:flex-row justify-around items-center gap-x-4">

        @foreach ($events as $event)

            <div class="w-full sm:w-1/3 rounded-xl sm:rounded-2xl  text-white text-center font-black bg-gray-600  sm:mt-0 pt-4 pb-6 bg-gradient-to-b from-gray-500 to-gray-700">

                <div class="text-7xl sm:text-[3.4rem] md:text-6xl lg:text-7xl xl:text-9xl  sm:mt-7 xl:mt-3 border-b border-white mx-3 lg:mx-7 mb-1">{{ $event->name }}</div>

                <div class="event-box px-5 text-white text-center flex sm:flex-col justify-between space-x-2 sm:space-x-0 text-sm md:text-base lg:text-xl">
                    <a class="bg-blue-400 hover:bg-blue-500" href="{{ route('event.result.index',$event->id) }}">Výsledky</a>
                    <a class="bg-yellow-400 hover:bg-yellow-500" href="{{ route('event.startlist.index',$event->id) }}">Startovka</a>
                    @if($event->registration_status == 0)
                       <span class="bg-red-400">Registrace od 1.10</span>
                        <!--<a class="bg-red-400 hover:bg-red-500" href="{{ route('registration.create',$event->id) }}">Registrovat</a>-->
                    @else
                        <a class="bg-green-400 hover:bg-gray-500 md:text-xl" href="{{ route('event.show',$event->id) }}">Administrace</a>
                    @endif
                </div>

            </div>

        @endforeach



</div>
