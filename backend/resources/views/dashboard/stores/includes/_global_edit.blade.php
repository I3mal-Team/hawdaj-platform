<form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
      action="{{ route('dashboard.stores.update', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.title')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input type="text" name="title" value="{{$data->translate(default_lang())->title}}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.title') ">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.description')</label>
                    <span class="text-danger"> * </span>
                    <textarea class="form-control description {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" rows="5" placeholder="@lang('dashboard.enter') @lang('dashboard.description') ">{{ $data->translate(default_lang())->description }}</textarea>
                    <div class="invalid-feedback">
                        <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.categories')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="categories[]" class="form-control select2" id="" multiple>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(is_array($data->categories) && in_array($category->id ,$data->categories)) selected @endif>{{ $category->name ?? '---' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('categories.*') ? $errors->first('categories.*') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="region_field">
                <div class="form-group validated">
                    <label>@lang('dashboard.region')</label>
                    <span class="text-danger region-required" style="display: {{ !$data->is_online ? 'inline' : 'none' }};"> * </span>
                    <div class="input-group">
                        <select name="region_id" id="region_id" class="form-control select2" {{ !$data->is_online ? 'required' : '' }}>
                            <option value="">{{ __('dashboard.select_region') }}</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}"
                                    {{ $data->region_id == $region->id ? 'selected ' : '' }}>
                                    {{ $region->name ?? '---' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('region_id') ? $errors->first('region_id') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6" id="city_field">
                <div class="form-group validated">
                    <label>@lang('dashboard.city')</label>
                    <span class="text-danger city-required" style="display: {{ !$data->is_online ? 'inline' : 'none' }};"> * </span>
                    <div id="ringle" class="lds-dual-ring" style="display:none;"></div>

                    <div class="input-group">
                        <select name="city_id" id="city_id" class="form-control select2" {{ !$data->is_online ? 'required' : '' }}>
                            <option value="">{{ __('dashboard.select_city') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ $data->city_id == $city->id ? 'selected ' : '' }}>
                                    {{ $city->name ?? '---' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('city_id') ? $errors->first('city_id') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{--------------add online or local-----------}}
            <div class="col-md-3">
                <div class="form-group validated">
                    <label>@lang('dashboard.InStatus')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select id="con_type" class="form-control select2" name="con_type">
                            <option value="">{{ __('dashboard.select_type') }}</option>
                            <option value="online" {{ ($data->is_online ?? false) ? 'selected' : '' }}>{{ __('dashboard.online') }}</option>
                            <option value="local" {{ !($data->is_online ?? false) ? 'selected' : '' }}>{{ __('dashboard.local') }}</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('con_type') ? $errors->first('con_type') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3" id="place_type" style="display: {{ !($data->is_online ?? false) ? '' : 'none' }}">
                <div class="form-group validated">
                    <label>@lang('dashboard.address_type')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="address_type" class="form-control select2">
                            <option value="">{{ __('dashboard.select_type') }}</option>
                            <option value="link"
                                    @if($data->address_type == 'link' || (!$data->is_online && $data->address_type != 'map')) selected @endif>{{ __('dashboard.link') }}</option>
                            <option value="map"
                                    @if($data->address_type == 'map' && !$data->is_online) selected @endif>{{ __('dashboard.map') }}</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('address_type') ? $errors->first('address_type/*') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6" id="link_address" style="display: {{ (($data->is_online ?? false) || (!$data->is_online && ($data->address_type == 'link' || !$data->address_type))) ? '' : 'none' }}">
                <div class="form-group validated">
                    <label>@lang('dashboard.link')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                            </span>
                        </div>
                        <input type="text" name="address" value="{{ $data->address ?? ''}}"
                               class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.link') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 map mb-4" style="display: {{ (!$data->is_online && $data->address_type == 'map') ? '' : 'none' }}">
                <div class="form-group ">
                    <div class="input-group ">
                        <input type="hidden" id="lat" name="lat" value="{{old('lat',$data->lat) ?? ''}}">
                        <input type="hidden" id="lng" name="long" value="{{old('long',$data->long) ?? ''}}">
                        <input type="text" id="pac-input" class="form-control " name="address_search"
                               value="{{ old('address_search', ($data->address_type == 'map' ? $data->address : '')) }}"
                               placeholder="Search Box" aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('lat') ? $errors->first('lat') : '' }}</strong>
                        </div>
                    </div>
                </div>
                <div id="googleMap" style="width:100%;height:400px;"></div>
            </div>

            {{-- <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.facebook')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-facebook-letter-logo"></i>
                            </span>
                        </div>
                        <input type="url" name="facebook_link" value="{{$data->facebook_link ?? ''}}"
                               class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.facebook') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('facebook') ? $errors->first('facebook') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.x')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-twitter-logo"></i>
                            </span>
                        </div>
                        <input type="url" name="x_link" value="{{$data->x_link ?? ''}}"
                               class="form-control {{ $errors->has('x_link') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.x') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('x_link') ? $errors->first('x_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.whatsapp')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-whatsapp"></i>
                            </span>
                        </div>
                        <input type="text" name="whatsapp" value="{{$data->whatsapp ?? ''}}"
                               class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.whatsapp') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('whatsapp') ? $errors->first('whatsapp') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.Instagram_link')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-instagram-logo"></i>
                            </span>
                        </div>
                        <input type="url" name="Instagram_link" value="{{$data->Instagram_link ?? ''}}"
                               class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.Instagram_link') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('Instagram_link') ? $errors->first('Instagram_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.website_link')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon2-world"></i>
                            </span>
                        </div>
                        <input type="url" name="website_link" value="{{$data->website_link ?? ''}}"
                               class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.website_link') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('website_link') ? $errors->first('website_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.visited')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select id="" class="form-control select2" name="visited">
                            <option value="">{{ __('dashboard.select_type') }}</option>
                            <option
                                value="1" {{ $data->visited == '1'? 'selected':""}}>{{ __('dashboard.yes') }}</option>
                            <option value="0" {{$data->visited == '0'? 'selected':""}}>{{ __('dashboard.no') }}</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('visited') ? $errors->first('visited') : '' }}</strong>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6 mt-4">
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
                            <img src="{{ $data->image ? asset($data->image) : asset('dashboard_assets/media/blank.png') }}" />
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-3">
                <span class="switch switch-outline switch-icon switch-success">
                    <label style="margin:15px">@lang('dashboard.status') : </label>
                    <label>
                        <input type="checkbox" @if($data->active) checked @endif name="active" value="true"/>
                        <span></span>
                    </label>
                </span>
            </div>

            <div class="col-3">
                <span class="switch switch-outline switch-icon switch-success">
                    <label style="margin:15px">@lang('dashboard.featured') : </label>
                    <label>
                        <input type="checkbox" @if($data->featured) checked @endif name="featured"
                               value="true"/>
                        <span></span>
                    </label>
                </span>
            </div>

            @include('dashboard.includes.partials._order_id_field', ['orderIdModel' => $data])

        </div>
    </div>
    <div class="card-footer">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
        </div>
    </div>

</form>
