<form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="POST"
      action="{{ route('dashboard.zad_elgadels.near', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="card-header">
        <div class="card-title">
            <strong class="text-muted">{{ __('dashboard.near_places') }}</strong>
        </div>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-md-12">

                <div class="form-group validated">
                    <label>@lang('dashboard.near_places')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="near_places[]" class="form-control select2" id="" multiple>
                            @foreach($places as $place)
                                <option value="{{ $place->id }}"
                                        @if(is_array($data->near_places) && in_array($place->id ,$data->near_places)) selected @endif >
                                    {{ $place->title}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('near_places') ? $errors->first('near_places') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="card-footer">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
        </div>
    </div>

</form>
