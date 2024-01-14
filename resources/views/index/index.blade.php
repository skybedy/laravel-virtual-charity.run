@inject('carbon', 'Carbon\Carbon')

@section('title', '| Hlavní strana')

<x-app-layout>
    <div class="smxxx:py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif


            <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl p-4 mt-5">
                <div class="w-full sm:rounded-2xl text-sky-600 text-center text-[100px] font-black bg-gray-600 mt-10 sm:mt-0 px-3 pt-4 pb-5 sm:py-5 md:pt-0 pb-3 lg:pb-0">
                    <span class="text-white  text-4xl font-black">100% FREE</span>
                </div>
            </div>

                @include('index.partials.event-list')
                   
           
                <ol class="text-4xl font-bold list-decimal list-inside">
                    <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl p-5 mt-5"><li class="text-orange-600">Pokud nejste, tak se <a class="underline" href="">přihlaste</a> přes FB, nebo Google..</li></div>
                    <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl p-5 mt-1"><li class="text-blue-500">Pokud nemáte, tak <a class="underline">povolte</a> STRAVU..</li></div>
                    <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl p-5 mt-1"><li class="text-green-600">Pokud nejste, tak se registrujte se k závodu/závodům..</li></div>
                    <div class="bg-gray-100 sm:bg-white overflow-hidden shadow-sm sm:rounded-2xl p-5 mt-1"><li class="text-gray-700">A to je vše..</li></div>

                </ol>

             
          

            





            
            
    
            
         





        </div>
    </div>
</x-app-layout>
