@props(['first_year', 'last_year'])

@for ($i = $first_year; $i <= $last_year ; $i++)
    <option value="{{ $i }}">{{ $i }}</option>
@endfor
