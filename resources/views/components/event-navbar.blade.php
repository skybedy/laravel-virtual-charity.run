<div class="flex justify-center sm:justify-between">
      <h2 class="mb-1 text-4xl font-extrabold leading-none tracking-tight text-orange-600 md:text-5xl lg:text-6xl dark:text-white pl-1">{{ $event->name }}</h2>
      <a class="bg-orange-600 text-xl text-white font-black rounded-lg " href="/"> Povolte si Stravu</a>

    </div>



<nav class="bg-white border-b border-t border-blue-400">
      <div class="max-w-7xl mx-auto">
            <div class="flex h-10  px-1 justify-center sm:justify-between">
                  <div class="flex">
                        <div class="space-x-2 sm:space-x-5  sm:-my-px flex justify-center">
                            <x-nav-link :href="route('event.startlist.index',$event->id)" :active="request()->routeIs('event.startlist.index')" class="text-blue-600 text-xs sm:text-sm">
                                {{ __('Startovka') }}
                            </x-nav-link>
                            <x-nav-link :href="route('event.result.index',$event->id)" :active="request()->routeIs('event.result.index')" class="text-blue-600 text-xs sm:text-sm">
                                {{ __('Výsledky') }}
                            </x-nav-link>
                            <x-nav-link :href="route('event.upload-url.create',$event->id)" :active="request()->routeIs('event.upload-url.create')" class="text-blue-600 text-xs sm:text-sm">
                                {{ __('Nahrát odkaz') }}
                            </x-nav-link>
                            <x-nav-link :href="route('event.upload-file.create',$event->id)" :active="request()->routeIs('event.upload-file.index')" class="text-blue-600 text-xs sm:text-sm">
                                {{ __('Nahrát soubor') }}
                            </x-nav-link>

                        </div>
                  </div>
            </div>
      </div>
</nav>
