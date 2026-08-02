<form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="POST"
      action="{{ route('dashboard.places.near', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="card-header">
        <div class="card-title">
            <strong class="text-muted">{{ __('dashboard.near_stores') }}</strong>
        </div>
    </div>
    <div class="card-body">

        <div class="row mb-10">
            @if(is_countable($data->near_stores) && count($data->near_stores) > 0)
                @foreach($data->near_stores as $gallery)
                    <div class="col-md-4 col-sm-12 mb-3">
                        <div class="container place_gallery_file">
                            <img src="{{resolvePhoto(isset($stores[$gallery]->image) ? $stores[$gallery]->image : '') }}" alt="imageNotFound" class="img-thumbnail">
                            <div class="overlay"></div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="row">

            <div class="col-md-12">

                <div class="form-group validated">
                    <label>@lang('dashboard.near_stores')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="near_stores[]" class="form-control select2" id="" multiple>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}"
                                        @if(is_array($data->near_stores) && in_array($store->id ,$data->near_stores)) selected @endif >
                                    {{ $store->title}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('near_stores') ? $errors->first('near_stores') : '' }}</strong>
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
