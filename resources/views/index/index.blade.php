@inject('carbon', 'Carbon\Carbon')

@section('title', '| Hlavní strana')

<x-app-layout>

    <div class="max-w-7xl mx-auto py-8 flex flex-col gap-y-6 sm:gap-y-8">

    <div class="homepage-box">


        <div class="homepage-inner-box-container gradient-tb-orange-400-600">
            <div>Běhy pro povodně<span class="hidden sm:inline">@</span><span class="block sm:inline">16.9 - 13.10.24</span></div>
        </div>


        <div class="flex flex-col sm:flex-row justify-between gap-x- ap-y-4 bg-white overflow-hidden shadow-md rounded-xl sm:rounded-2xl mt-2 sm:mt-4">

            <div class="homepage-inner-box-container gradient-tb-gray-500-700">
                <div>
                    Startovné na všechny 3 závody 111 Kč
                </div>
                <div>
                    Přímá platba vybrané charitativní organizaci
                </div>
            </div>


            <div class="shadow-md m-4 ms-0 p-1 w-full sm:w-44 md:w-56 lg:w-[19rem] xl:w-96 sm:rounded-xl border-2 border-gray-600 hidden sm:flex justify-center items-center">
                <img class="w-full h-auto"  src="{{ asset('images/neziskovky-logo.png') }}" alt="Obrázek">
            </div>

        </div>

        @include('index.partials.event-list',['serie_id' => 2])
    </div>

<hr>

        <div class="homepage-box">

            <div class="homepage-inner-box-container gradient-tb-orange-400-600">
                <div>Běhy pro mámy samoživitelky v N/nesnázi<span class="hidden sm:inline">@</span><span class="block sm:inline">1. 10 - 31. 12. 24</span></div>
            </div>




            <div class="flex flex-col sm:flex-row justify-between gap-x- ap-y-4 bg-white overflow-hidden shadow-sm rounded-xl sm:rounded-2xl mt-2 sm:mt-4">

                <div class="homepage-inner-box-container gradient-tb-gray-500-700">
                    <div>
                        Startovné na všechny 3 závody 111 Kč
                    </div>
                    <div>
                        Přímá platba dárcovské platformě Znesnáze
                    </div>
                </div>


                <a href="https://www.znesnaze21.cz" target="_blank" class="m-4 ms-0 p-4 w-full sm:w-44 md:w-56 lg:w-[19rem] xl:w-96 sm:rounded-xl border border-gray-600 hidden sm:flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60%" height="auto" viewBox="0 0 342.742 279.034"><path d="M31.059 247.826 13.86 268.22h17.563v10.232H0v-9.87l16.834-20.392H.142v-10.232h30.917v9.868Zm35.134 8.637c0-5.514-3.05-8.564-7.767-8.564s-7.762 3.05-7.762 8.564v21.99h-12.41v-40.495h12.41v5.37c2.467-3.41 6.821-5.808 12.262-5.808 9.36 0 15.606 6.387 15.606 17.274v23.658H66.193v-21.989Zm38.183 22.571c-11.899 0-20.465-7.985-20.465-20.829s8.42-20.829 20.465-20.829c11.832 0 20.176 7.838 20.176 20.177 0 1.16-.07 2.394-.217 3.628H96.249c.435 5.297 3.702 7.765 7.692 7.765 3.484 0 5.44-1.742 6.457-3.92h13.208c-1.957 7.912-9.142 14.008-19.23 14.008Zm-8.056-24.748h15.53c0-4.427-3.48-6.968-7.616-6.968-4.065 0-7.186 2.468-7.914 6.968Zm51.25 24.748c-10.816 0-18.072-6.025-18.654-13.863h12.268c.288 2.832 2.832 4.718 6.24 4.718 3.196 0 4.864-1.451 4.864-3.267 0-6.53-22.062-1.813-22.062-16.69 0-6.894 5.876-12.556 16.475-12.556 10.447 0 16.252 5.805 17.051 13.79h-11.464c-.364-2.758-2.467-4.574-5.951-4.574-2.903 0-4.5 1.163-4.5 3.123 0 6.457 21.915 1.886 22.133 16.98 0 7.04-6.24 12.339-16.4 12.339Zm51.169-22.571c0-5.514-3.044-8.564-7.762-8.564s-7.768 3.05-7.768 8.564v21.99H170.8v-40.495h12.41v5.37c2.467-3.41 6.821-5.808 12.267-5.808 9.36 0 15.6 6.387 15.6 17.274v23.658H198.74v-21.989Zm35.325-19.087c6.022 0 10.305 2.759 12.485 6.314v-5.732h12.41v40.495h-12.41v-5.735c-2.25 3.558-6.533 6.316-12.556 6.316-9.87 0-17.78-8.129-17.78-20.902s7.91-20.756 17.85-20.756Zm3.63 10.814c-4.647 0-8.854 3.484-8.854 9.942s4.207 10.088 8.854 10.088c4.718 0 8.855-3.557 8.855-10.015s-4.137-10.015-8.855-10.015Zm9.795-29.974v9.654l-17.491 7.33v-8.564l17.491-8.42Zm50.089 29.61L280.38 268.22h17.562v10.232h-31.423v-9.87l16.834-20.392h-16.692v-10.232h30.917v9.868Zm24.992 31.208c-11.903 0-20.464-7.985-20.464-20.829s8.414-20.829 20.465-20.829c11.827 0 20.171 7.838 20.171 20.177 0 1.16-.07 2.394-.217 3.628h-28.086c.435 5.297 3.702 7.765 7.697 7.765 3.479 0 5.44-1.742 6.457-3.92h13.208c-1.962 7.912-9.142 14.008-19.23 14.008Zm-8.055-24.748h15.53c0-4.427-3.485-6.968-7.621-6.968-4.066 0-7.185 2.468-7.909 6.968Z" style="stroke-width:0"/><path d="M198.887 165.09c-6.971-8.069-17.98-9.968-28.628-10.052-14.61.203-35.724 1.72-43.767 16.599-7.054 15.538 10.565 25.04 22.558 27.042 15.005 2.72 35.252 2.753 48.108-7.207 8.585-6.833 9.061-18.303 1.83-26.265l-.101-.116Z" style="fill:#dbb2e5;fill-rule:evenodd;stroke-width:0"/><path d="M259.24 72.45C258.566 29.538 220.363.55 181.215.092c-38.326-1.876-78.14 25.172-83.23 66.317-2.76 20.418 5.883 43.348 20.017 57.481 23.839 24.13 61.164 29.158 91.91 17.79 28.036-9.8 50.232-37.258 49.331-69.06l-.003-.17Z" style="fill:#fcd222;stroke-width:0"/><path d="M215.824 32.322c-11.412-12.555-26.626-19.075-42.809-18.054-34.859 2.192-53.673 19.252-55.924 50.706-1.389 19.438 12.771 42.025 20.38 54.161.91 1.452 1.725 2.75 2.498 4.03 5.993 9.908 9.295 15.369 9.528 48.024.532 12.466 4.366 14.226 12.99 15.269 1.53.185 2.833.277 3.978.277 7.41 0 11.168-3.945 12.56-13.098 4.973-25.251 11.157-33.506 17.708-42.25 1.142-1.524 2.298-3.066 3.465-4.712 1.913-2.7 4.502-5.505 7.498-8.754 8.921-9.664 21.135-22.897 23.161-44.079 1.353-14.154-4.269-29.675-15.033-41.52ZM173.281 18.52a45.491 45.491 0 0 1 2.994-.094c3.027 0 6.01.34 8.94.915l-7.147 33.744-7.013-34.363c.749-.064 1.461-.155 2.226-.202Zm16.053 115.798c-7.33 3.329-15.41 4.38-22.964 3.299l-1.078-3.646 12.702-59.974 12.094 59.248c-.252.352-.504.71-.754 1.073Zm-26.576-8.924L146.786 71.36l4.81-48.794c4.543-1.589 9.618-2.723 15.201-3.427l9.062 44.39-13.1 61.866Zm-15.623-101.05-3.562 36.148-8.358-28.278c3.368-3.158 7.32-5.794 11.92-7.87Zm-25.797 40.933c.876-12.261 4.383-22.033 10.509-29.45l10.617 35.93-4.028 40.87c-7.55-12.262-18.248-31.289-17.098-47.35Zm20.796 53.284 3.543-35.937 15.227 51.53-.455 2.143c-4.471-1.38-8.588-3.57-12.05-6.542-1.392-3.169-3.027-5.882-4.89-8.962a179.92 179.92 0 0 0-1.375-2.232Zm32.694 54.347c-1.344 8.848-4.585 9.57-8.363 9.57-.973 0-2.107-.082-3.468-.245-6.77-.82-8.815-1.067-9.245-11.15a402.096 402.096 0 0 0-.103-6.683l22.679 1.578a197.437 197.437 0 0 0-1.5 6.93Zm2.54-11.125-23.852-1.66c-.416-10.842-1.353-18.016-2.752-23.449 6.292 3.703 13.766 5.614 21.408 5.614 4.505 0 9.054-.727 13.467-2.077-2.941 5.24-5.724 11.87-8.272 21.572Zm19.354-37.57a198.01 198.01 0 0 1-3.235 4.403l-13.281-65.061 9.148-43.189a48.073 48.073 0 0 1 9.918 4.156l7.135 88.509c-.62.68-1.236 1.347-1.84 2.001-2.97 3.219-5.772 6.26-7.845 9.182Zm29.894-50.775c-1.497 15.662-8.835 26.512-16.289 35.188l-6.55-81.271a54.168 54.168 0 0 1 8.895 7.83c9.974 10.972 15.186 25.27 13.944 38.253Z" style="stroke-width:0"/></svg>
                </a>

            </div>

            @include('index.partials.event-list',['serie_id' => 1])


        </div>

        <hr>

        <div class="homepage-box">
            <div class="flex flex-col gap-y-2 sm:gap-y-4">

                <div class="homepage-inner-box-container gradient-tb-orange-400-600">
                    <div>Základní vlastnosti</div>
                </div>

                <div class="homepage-inner-box-container gradient-tb-gray-500-700">
                    <div>
                        Plně automatizovaný přenos dat prostřednictvím STRAVA
                    </div>
                    <div>
                        Jde to ale i bez STRAVY pomocí GPX souborů
                    </div>
                    <div>
                        Okamžitý náhled na výsledky
                    </div>
                    <div>
                        Libovolný počet běhů, do výsledků se započítává nejlepší
                    </div>
                    <div>
                        Věkové kategorie po 5 letech
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="homepage-box">
            <div class="flex flex-col gap-y-2 sm:gap-y-4">
                <div class="homepage-inner-box-container gradient-tb-orange-400-600">
                    <div>Jak začít</div>
                </div>
                <ol class="homepage-inner-box-container gradient-tb-gray-500-700 list-decimal list-inside">
                    <li>Pokud nejste, tak se <a class="underline text-red-500" href="{{ route('login') }}">přihlaste</a> přes FB, nebo Google..</li>
                    <li>Pokud nemáte, tak si <a class="underline text-red-500" href={{ route('strava.index') }}>povolte</a> STRAVU..</li>
                    <li>Pokud nejste, tak se registrujte se k závodu, nebo závodům..</li>
                    <li>A to je vše - i když možná si ještě raději přečtěte, <a class="underline text-red-500" href="{{ route('how_it_works.index') }}">jak na to..</a></li>
                </ol>
            </div>
        </div>




    </div>
</x-app-layout>
