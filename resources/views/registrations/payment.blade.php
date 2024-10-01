<x-app-layout>
    <div class="py-12">
        <div class="5/6mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg boder p-6 boder-blue-300 shadow-lg">



                <x-h2 style="style-1">REGISTRACE A STARTOVNÉ</x-h2>
                <div class="flex flex-col space-y-8 my-10">

                @for ($i = 0; $i < count($payment_recepients) / 4; $i++)
                    <div class="flex flex-col sm:flex-row justify-evenly space-y-2 sm:space-y-0">

                        @for ($j = 0; $j < 4; $j++)
                            @php
                                // Vypočítáme index aktuální položky
                                $index = ($i * 4) + $j;
                            @endphp


                                <div class="sm:w-32 md:w-40 xl:w-52 flex flex-col justify-center items-center border-2 border-sky-400 rounded-xl p-2 md:p-3 shadow-lg">

                                    <div class="flex-grow flex items-center justify-center">
                                <a target="_blank" href="https://{{ $payment_recepients[$index]->url }}">
                                    <img class="w-2/3 h-auto mx-auto"  src="{{ asset('images/'.$payment_recepients[$index]->logo_name).'-logo.png' }}" alt="Obrázek">
                                </a>
                            </div>
                                <a class="text-center w-full  mt-4 bg-gradient-to-b from-blue-400 to-blue-500 text-white py-2 text-xs md:text-sm xl:text-base rounded-lg" href="{{ route('registration.checkout.stripe.payment_recipient',['event_id' => $event_id,'payment_recipient' => $payment_recepients[$index]->id]) }}">Zaregistrovat se<br> a poslat 111Kč</a>


                            </div>







                    @endfor
                </div>
                @endfor

            </div>


            <x-h2 style="style-1">DÍKY</x-h2>


            </div>

        </div>
    </div>

</x-app-layout>





