@inject('carbon', 'Carbon\Carbon')


<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif


                      


<div class="grid grid-cols-3  gap-4">

@foreach ($events as $event)

 <div><a href="{{ route('event.show',$event->id) }}">{{ $event->name }}</a></div>
  <div><p>{{ $carbon::parse($event->date_start)->format('j') }}-{{ $carbon::parse($event->date_end)->format('j.n.y') }}</p></div>
  
  <div class="flex justify-end space-x-2">
      <a class="border-solid border border-red-600 hover:bg-red-700 hover:text-white text-red-700  px-3 rounded" href="{{ route('registration.create',$event->id) }}">Přihlásit se</a>
      <a class="border-solid border border-red-600 hover:bg-red-700 hover:text-white text-red-700  px-2 rounded" href="{{ route('registration.index',$event->id) }}">Seznam prihlasenych</a>
      <a class="border-solid border border-red-600 hover:bg-red-700 hover:text-white text-red-700  px-2 rounded" href="{{ route('registration.create',$event->id) }}">Nahrát běh</a>
      <a class="border-solid border border-red-600 hover:bg-red-700 hover:text-white text-red-700  px-2 rounded" href="{{ route('registration.create',$event->id) }}">Výsledky</a>
  </div>
 

           @endforeach


 
  <!-- Další řádky -->
</div>


















                </div>
            </div>
        </div>
    </div>
</x-app-layout>




