            <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl mt-5 p-4">
                <h2 class="text-4xl text-blue-700 font-black">Autodistance upload</h2>


                <form action="{{ route('autodistance_upload') }}" method="post" enctype="multipart/form-data" class="mt-5">
                    @csrf
                         <div>
                            <x-input-label for="place" :value="__('Místo běhu (Kocourkov, Horní Dolní..) *')" />
                            <x-text-input id="place" name="place" type="text" class="mt-1 w-full sm:w-1/2" :value="old('place')"  />
                            <x-input-error class="mt-2" :messages="$errors->get('place')" />
                        </div>
                        <input type="file" name="file" class="mt-1 w-full sm:w-1/2 mt-2 border border-grey">
                        <div class="mt-2 p-2 w-full sm:w-1/2 border border-grey sm:rounded-xl">         
                            <x-primary-button class="m-2">Nahrát</x-primary-button>
                        </div>  
                </form>

            </div>