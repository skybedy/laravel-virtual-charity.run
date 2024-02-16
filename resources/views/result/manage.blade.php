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

                        <table>

                        @foreach ($results as $result)
                            @if (count($result->results) > 0)
                                <tr><td colspan="3">{{ $result->name }}</td></tr>

                                @foreach ($result->results as $result)
                                    <tr>
                                        <td>{{ $carbon::parse($result['finish_time_date'])->format('j.n.') }}</td>
                                        <td>{{ $result['finish_time'] }}</td>
                                        <td>{{ $result['pace'] }}</td>
                                        <td>
                                            <form>
                                                <input type="hidden" name="result_id" value="{{ $result['id'] }}">
                                                <input type="submit" value="Smazat">
                                            </form>
                                        </td>
                                    </tr>

                                @endforeach
                            @endif


                        @endforeach

                        </table>




            </div>
        </div>
    </div>
</x-app-layout>




