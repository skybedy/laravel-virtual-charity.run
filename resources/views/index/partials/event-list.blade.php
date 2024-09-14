
            <div class=" sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl mt-4">


                <div class="sm:py-8 xl:py-4 md:px-2 flex flex-col">
                    <div class="flex flex-col sm:flex-row justify-around items-center">
                        @php
                            $registration_status = 0; //tahle logika se musi dodelat, predtim to kontroloalo kazdy zavod, bo mohlo byt, ze na jeden je prihlaseny a na dalsi ne, ted je to tak, ze pokud je na jeden prihlaseny, tak je na vsechny
                        @endphp
                        @foreach ($events as $event)
                            <div class="w-full sm:w-44 md:w-56 lg:w-[19rem] xl:w-96  sm:rounded-2xl  text-white text-center font-black bg-gray-600 mt-10 sm:mt-0 pt-4 pb-6">

                                <div class="text-4xl sm:text-[3.4rem] md:text-7xl lg:text-8xl xl:text-9xl border-b border-white mx-3 lg:mx-7">{{ $event->name }}</div>


                                <div class="px-6">
                                <a class="w-full inline-block text-center  hover:bg-red-700 hover:text-white rounded  bg-red-400 text-white md:text-xl px-4 py-6 sm:py-2  mt-4 sm:mt-3" href="{{ route('event.result.index',$event->id) }}">Výsledky</a>
                                <a class="w-full inline-block text-center  hover:bg-red-700 hover:text-white  rounded  bg-yellow-500 text-white md:text-xl px-4 py-6 sm:py-2 mt-4 sm:mt-3" href="{{ route('event.startlist.index',$event->id) }}">Startovka</a>
                                @if($event->registration_status == 0)

                                    <span class="w-full inline-block text-center rounded  bg-green-400 text-green-500 md:text-xl px-4 py-6 sm:py-2  mt-5 sm:mt-3">Administrace</span>
                                @else
                                    @php
                                        $registration_status = 1;
                                    @endphp
                                    <a class="w-full inline-block text-center hover:bg-green-400 hover:text-white rounded  bg-red-400 text-white md:text-xl px-4 py-6 sm:py-2 mt-5 sm:mt-3" href="{{ route('event.show',$event->id) }}">Administrace</a>
                                @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($registration_status == 0)
                    <div class=" mt-5 sm:mt-4 px-2">
                        <a class="block text-center sm:rounded-xl  bg-gradient-to-b from-blue-400 to-blue-500  hover:text-yellow-500   text-white font-black text-4xl px-4 sm:py-8 xl:py-4 " href="{{ route('registration.create') }}">ZAREGISTROVAT SE</a>
                    </div>
                    @endif

                </div>
            </div>
