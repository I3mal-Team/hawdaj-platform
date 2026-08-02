<form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post" action="{{ route('dashboard.stores.update', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.categories')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="categories[]" class="form-control select2" id="" multiple>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if(is_array($data->categories) && in_array($category->id ,$data->categories)) selected @endif>{{ $category->name ?? '---' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('categories.*') ? $errors->first('categories.*') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.address_type')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="address_type" class="form-control">
                            <option value="">{{ __('dashboard.select_type') }}</option>
                            <option value="link" @if($data->address_type == 'link') selected @endif>{{ __('dashboard.link') }}</option>
                            <option value="map" @if($data->address_type == 'map') selected @endif>{{ __('dashboard.map') }}</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('address_type') ? $errors->first('address_type/') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            @if($data->address_type == 'link')
            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.link')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                            </span>
                        </div>
                        <input type="text" name="address" value="{{ $data->address ?? ''}}" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.link') " aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.description')</label>
                    <span class="text-danger"> * </span>
                    <textarea class="description form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description">{{ $data->description ?? '' }}</textarea>
                    <div class="invalid-feedback">
                        <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group row validated">
                    <div class="col-md-10">
                        <label>{{__('dashboard.image')}}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="flaticon2-image-file"></i>
                                </span>
                            </div>
                            <input type="file" name="image" accept=".png , .jpg, .jpeg" class="form-control file {{ $errors->has('image') ? 'is-invalid' : '' }}" placeholder="{{__('dashboard.enter')}} {{__('dashboard.image')}}">
                            <div class="invalid-feedback">
                                <strong>{{ $errors->has('image') ? $errors->first('image') : '' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 image">
                        <div class="image_prev_form thumb-output">
                            <img src="{{
    (\Illuminate\Support\Facades\Storage::disk('public')->exists($data->image)
? \Illuminate\Support\Facades\Storage::disk('public')->url($data->image)
 : asset('dashboard_assets/media/blank.png')) }}" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.facebook')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-facebook-letter-logo"></i>
                            </span>
                        </div>
                        <input type="text" name="facebook_link" value="{{$data->facebook_link ?? ''}}" class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.facebook') " aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('facebook') ? $errors->first('facebook') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.whatsapp')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-whatsapp"></i>
                            </span>
                        </div>
                        <input type="text" name="whatsapp" value="{{$data->whatsapp ?? ''}}" class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.whatsapp') " aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('whatsapp') ? $errors->first('whatsapp') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.Instagram_link')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-instagram-logo"></i>
                            </span>
                        </div>
                        <input type="text" name="Instagram_link" value="{{$data->Instagram_link ?? ''}}" class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.Instagram_link') " aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('Instagram_link') ? $errors->first('Instagram_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.website_link')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon2-world"></i>
                            </span>
                        </div>
                        <input type="text" name="website_link" value="{{$data->website_link ?? ''}}" class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.website_link') " aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('website_link') ? $errors->first('website_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-3">
                <span class="switch switch-outline switch-icon switch-success">
                    <label>
                        <input type="checkbox" checked="checked" name="active" value="true" />
                        <span></span>
                    </label>
                </span>
            </div>

        </div>
    </div>
    <div class="card-footer">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
        </div>
    </div>
</form>