<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg boder p-6 boder-blue-300 shadow-lg">
                <x-h2 style="style-1">STRAVA</x-h2>
                <x-p style="style-1">
                    Přestože to lze i bez pouze za pomocí <a class="underline" target="_blank" href="https://en.wikipedia.org/wiki/GPS_Exchange_Format">GPX souborů</a>, použití  <a class="underline" target="_blank" href="https://www.strava.com">STRAVY</a> významně snižuje určitou pracnost
                    nahrání výsledků jednotlivých běhů a v případě jejího použití je tento proces zcela zautomatizován.
                </x-p>
                <x-h2 style="style-1">Jaké možnosti povolení STRAVY přináší</x-h2>
                <div class="text-base sm:text-lg md:text-xl lg:text-2xl px-2 sm:px-4 md:px-5 text-gray-800 bg-blue-50 py-5">
                    <ul class="list-disc  mt-5 ms-5">
                        <li> V případě přopojení naší aplikace ke <a class="underline" target="_blank" href="https://www.strava.com">STRAVĚ</a> jsou nové běhy přeneseny do naší aplikace zcela automaticky a není již třeba dělat vůbec nic</li>
                        <li>V případě potřeby nahrát běhy z minulosti, lze zcela jednoduše zkopírovat odkaz na aktivitu buď z adresního řádku prohlížeče v případě použití webových stránek  <a class="underline" target="_blank" href="https://www.strava.com">STRAVY</a>,
                            nebo pomocí standardních sdílecích mechanismů v případě použití  <a class="underline" target="_blank" href="https://www.strava.com">STRAVY</a> jako aplikace, ať už na Androidu, nebo iOS </li>
                    </ul>
                </div>
                <x-h2 style="style-1">STRAVA JE ZDARMA</x-h2>
                <x-p style="style-1">
                    <a class="underline" target="_blank" href="https://www.strava.com">STRAVA</a> je skvělým pomocníkem pro každého běžce, umožňuje nejen sledovat vlastní výkony a porovnávat se s ostatním a v základní verzi, která je pro drtivou většinu amatérských sportovců zcela dostačující, je zcela zdarma.<br> Registrace je jednoduchá a můžete ji provést přes Facebook nebo Google, což usnadňuje začátek vaší běžecké cesty. <br>Pokud ji ještě nemáte, využijte výhod, které  <a class="underline" target="_blank" href="https://www.strava.com">STRAVA</a> přináší, a posuňte své běžecké cíle na novou úroveň!
                </x-p>

                <div class="bg-blue-50 mt-5 p-5">
                    <a class="block bg-gradient-to-b from-blue-400 to-blue-500 text-4xl text-center p-10 text-white font-black rounded-md shadow-lg" href="{{route('authorize_strava')}}">PŘIPOJTE VIRTUAL CHARITY RUN KE STRAVĚ </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
