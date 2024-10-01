<x-app-layout>

    <div class="py-12">
        <div class="w-5/6 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-2 text-gray-900 bg-rd-500">
                    <div class="homepage-box">

                        <x-event-navbar :event="$event" :all_same_serie_events="$all_same_serie_events" />
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>




