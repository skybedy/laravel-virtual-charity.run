@inject('carbon', 'Carbon\Carbon')


<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                {{ $event->name }}, {{ $carbon::parse($event->date_start)->format('j') }}-{{ $carbon::parse($event->date_end)->format('j.n.y') }}

               
                <div><x-nav-link href="{{route('result.index',$event->id)}}">Výsledky</x-nav-link></div>

                <form action="{{ route('result.upload') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="file" name="file">
                    <x-primary-button>Upload</x-primary-button>
                </form>
                
                
                
                
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




