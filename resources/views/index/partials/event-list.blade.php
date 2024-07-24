
            <div class=" sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl mt-4">

                <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl sm:px-4 mt-1 sm:mt-5">
                    <div class="w-full sm:rounded-xl text-white  text-xl sm:text-4xl text-center font-black bg-red-500 sm:bg-gray-600 py-2 sm:py-5 ">
                        1 - 31. 8. 2024
                    </div>
                </div>

                <div class="sm:py-8 xl:py-4 md:px-2 flex flex-col sm:flex-row justify-around items-center">

                    @foreach ($events as $event)
                        <div class="w-full sm:w-44 md:w-56 lg:w-[19rem] xl:w-96  lg:h-96 xl:h-96 sm:rounded-2xl  text-white text-center font-black bg-gray-600 mt-10 sm:mt-0 px-3 pt-4  sm:py-5 md:pt-0 pb-3 lg:pb-0">

                            <div class="text-4xl sm:text-[3.4rem] md:text-7xl lg:text-8xl xl:text-9xl  sm:mt-7 xl:mt-3 border-b border-white mx-3 lg:mx-7 mb-1">{{ $event->name }}</div>


                            @if($event->registration_status == 0)
                                <a class="w-full inline-block text-center hover:bg-red-700 hover:text-white rounded  bg-red-400 text-white md:text-xl px-4 py-6 sm:py-2  mt-5 sm:mt-5" href="{{ route('registration.create',$event->id) }}">Zaregistrovat se</a>
                            @else
                                <a class="w-full inline-block text-center hover:bg-red-700 hover:text-white rounded  bg-green-400 text-white md:text-xl px-4 py-6 sm:py-2 mt-5 sm:mt-5" href="{{ route('event.upload-url.create',$event->id) }}">Nahrát běh</a>
                            @endif

                            <a class="w-full inline-block text-center  hover:bg-red-700 hover:text-white rounded  bg-blue-400 text-white md:text-xl px-4 py-6 sm:py-2  mt-4 sm:mt-3" href="{{ route('event.result.index',$event->id) }}">Výsledky</a>
                            <a class="w-full inline-block text-center  hover:bg-red-700 hover:text-white  rounded  bg-yellow-400 text-white md:text-xl px-4 py-6 sm:py-2 mt-4 sm:mt-3" href="{{ route('event.startlist.index',$event->id) }}">Startovka</a>
                        </div>
                    @endforeach
                </div>
            </div>
