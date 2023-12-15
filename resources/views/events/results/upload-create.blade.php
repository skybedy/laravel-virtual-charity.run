<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 bg-rd-500">
                    
                   <x-event-navbar :event="$event" />
                    
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2">{{ session('error') }}</div>
                    @endif
               
                    <form action="{{ route('event.upload.store',$event->id) }}" method="post" enctype="multipart/form-data" class="mt-5">
                        @csrf
                        <div>
                            <x-input-label for="place" :value="__('Místo běhu (Kocourkov, Horní Dolní..) *')" />
                            <x-text-input id="place" name="place" type="text" class="mt-1 w-full sm:w-1/2" :value="old('place')"  />
                            <x-input-error class="mt-2" :messages="$errors->get('place')" />
                        </div>




                       <div class="mt-4">
                            <x-input-label for="file" :value="__('Nahrejte soubor GPX')" />
                            <input type="file" class="mt-1" name="file" required><br>
                        </div>
                        <div class="mt-4">         
                            <x-primary-button class="mt-2">Nahrát</x-primary-button>
                        </div>    
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




