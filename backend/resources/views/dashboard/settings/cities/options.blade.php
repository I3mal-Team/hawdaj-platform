@if(count($cities) > 0)
    <option value="">{{ __('dashboard.select_city') }}</option>
    @foreach($cities as $city)
        <option value="{{ $city->id }}"> {{ $city->name ?? '---' }} </option>
    @endforeach
@else
    <option value="">{{ __('dashboard.no_data_found') }}</option>
@endif
