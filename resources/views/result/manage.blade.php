@inject('carbon', 'Carbon\Carbon')


<x-app-layout>

    <div class="py-3">
        <div class="5/6mx-auto sm:px-6 }lg:px-8">
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
                            <div class="flex justify-center">
                            <table id="result_table" class="table  border-collapse w-full my-10 mx-5">

                                @foreach ($results as $result)
                                    @if($current_race != $result->race_name)
                                        <tr>
                                            <td colspan="4" class="border bg-orange-600 text-3xl font-bold  text-center text-white py-2"><h2>{{ $result->race_name }}</h2></td>

                                        </tr>


                                        <tr class="text-center">
                                            <th class="border">Datum</th>
                                            <th class="border px-2">Čas</th>
                                            <th class="border px-2">Tempo</th>
                                            <th class="border px-2"></th>
                                        </tr>

                                        @endif



                                    <tr class="text-center odd:bg-gra-100 even:bg-whit">
                                        <td class="border">{{ $carbon::parse($result->finish_time_date)->format('j.n.') }}</td>
                                        <td class="border">{{ $result->finish_time }}</td>
                                        <td class="border">{{ $result->pace_km }}</td>
                                        <td class="border py-1">
                                            <a class="text-blue-600" href="{{ route('result.delete',['resultId' => $result->id]) }}">Smazat výsledek</a>
                                        </td>
                                    </tr>
                                    @php
                                        $current_race = $result->race_name;
                                    @endphp
                                @endforeach
                            </table>
                            </div>
                        @endif






            </div>
        </div>
    </div>
</x-app-layout>




