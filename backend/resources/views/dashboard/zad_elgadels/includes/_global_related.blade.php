<form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="POST" action="{{ route('dashboard.zad_elgadels.related', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="card-header">
        <div class="card-title">
            <strong class="text-muted">{{ __('dashboard.related_stores') }}</strong>
        </div>
    </div>
    <div class="card-body">

        <div class="row mb-10">
            @foreach($all_related_items as $gallery)
            <div class="col-md-4 col-sm-12 mb-3">
                <div class="container place_gallery_file">
                    <img src="{{isset($stores[$gallery]) ? asset($stores[$gallery]->image) : resolvePhoto('')}}" alt="imageNotFound" class="img-thumbnail">
                    <div class="overlay"></div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row">

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.related_stores')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="related_stores[]" class="form-control select2" id="" multiple>
                            @foreach($stores as $store)
                            <option value="{{$store->id }}" @if(is_array($data->related_stores) && in_array($store->id ,$data->related_stores)) selected
                                @endif>
                                {{ $store->title ?? '---' }}
                            </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('related_stores') ? $errors->first('related_stores') : '' }}</strong>
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