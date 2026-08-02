<form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="POST" action="{{ route('dashboard.places.related', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="card-header">
        <div class="card-title">
            <strong class="text-muted">{{ __('dashboard.related') }}</strong>
        </div>
    </div>
    <div class="card-body">

        <div class="row mb-10">
            @foreach($all_related_items as $gallery)
            <div class="col-md-4 col-sm-12 mb-3">
                <div class="container place_gallery_file">
                    <img src="{{isset($places[$gallery]) ? asset($places[$gallery]->image) : resolvePhoto('')}}" alt="imageNotFound" class="img-thumbnail">
                    <div class="overlay"></div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row">

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.related')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="related_places[]" class="form-control select2" id="" multiple>
                            @foreach($places as $place)
                            <option value="{{$place->id }}" @if(is_array($data->related_places) && in_array($place->id ,$data->related_places )) selected @endif>{{ $place->title ?? '---' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('places') ? $errors->first('places') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="row m-2">
                    <button type="button" class="btn btn-success m-2" onclick="drawOnclick();">Draw Circle</button>
                    <button type="button" class="btn btn-default m-2" onclick="changeRadius(100);">+</button>
                    <button type="button" class="btn btn-default m-2" onclick="changeRadius(-100);">-</button>
                    <button type="button" class="btn btn-danger m-2" onclick="removeCircle();">Remove Circle</button>
                </div>
                <input type="hidden" value="" name="distance" id="distance" />
                <div id="googleMapRelated" style="width:100%;height:400px;"> </div>
            </div>

        </div>
    </div>
    <div class="card-footer">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
        </div>
    </div>
</form>