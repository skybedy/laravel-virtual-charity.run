@inject('carbon', 'Carbon\Carbon')


<x-app-layout>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 }lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="overflow-auto">

                        @php
                             $current_race = null;
                        @endphp

                        @if ($results->count() > 0)
                            <table id="result_table" class="hidden md:table table-auto border-collapse w-full mt-5">

                                @foreach ($results as $result)
                                    @if($current_race != $result->race_name)
                                        <tr class="text-center">
                                            <td class="border-none" colspan="4">
                                                <h2 class="text-2xl font-bold">{{ $result->race_name }}</h2>
                                            </td>
                                        </tr>
                                        <tr class="text-center">
                                            <th class="border-none">Datum</th>
                                            <th class="border-none px-2">Čas</th>
                                            <th class="border-none px-2">Tempo</th>
                                            <th class="border-none px-2"></th>
                                        </tr>

                                        @endif



                                    <tr class="text-center odd:bg-gra-100 even:bg-whit">
                                        <td class="border">{{ $carbon::parse($result->finish_time_date)->format('j.n.') }}</td>
                                        <td class="border">{{ $result->finish_time }}</td>
                                        <td class="border">{{ $result->pace_km }}</td>
                                        <td class="border py-1">
                                            <a class="text-red-600" href="{{ route('result.delete',['resultId' => $result->id]) }}">Smazat výsledek</a>
                                        </td>
                                    </tr>
                                    @php
                                        $current_race = $result->race_name;
                                    @endphp
                                @endforeach
                            </table>
                        @endif






            </div>
        </div>
    </div>
</x-app-layout>




