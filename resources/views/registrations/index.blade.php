<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full border-collapse">
                        @foreach ($categories as $category)
                            <tr class="border-b">
                                <td class="border">{{ $category->lastname }} {{ $category->firstname }}</td>
                                <td class="border">{{ $category->name}}</td>
                            </tr>
                        @endforeach
                    </table>



                 </div>
            </div>
        </div>
    </div>

</x-app-layout>




