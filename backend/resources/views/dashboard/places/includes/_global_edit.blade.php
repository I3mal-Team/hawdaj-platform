<form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
      action="{{ route('dashboard.places.update', $data-> id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

{{--    <div class="row">--}}
{{--        <div class="col-md-12">--}}
{{--            <div class="d-flex flex-column-fluid">--}}
{{--                <div class="container-fluid ">--}}
{{--                    <div class="tab-danger pr-10">--}}
{{--                        <!-- Nav Tabs -->--}}
{{--                        <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">--}}
{{--                            @foreach(locales() as $locale)--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a class="nav-link {{$locale == "ar" ? "active" :""}}" id="tab-{{$locale}}"--}}
{{--                                       data-toggle="tab"--}}
{{--                                       href="#{{$locale}}">--}}
{{--                                        @lang("dashboard." . $locale)--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
{{--                        <!-- Tab Panels -->--}}
{{--                        <div class="tab-content p-4" id="myTabContent">--}}
{{--                            @foreach(locales() as $locale)--}}
{{--                                <div--}}
{{--                                    role="tabpanel"--}}
{{--                                    class="tab-pane fade {{$locale == 'ar' ? 'show active' : ''}}"--}}
{{--                                    aria-labelledby="tab-{{$locale}}"--}}
{{--                                    id="{{$locale}}"--}}
{{--                                >--}}
{{--                                    <div class="form-group validated">--}}
{{--                                        <label>@lang('dashboard.title')</label>--}}
{{--                                        <span class="text-danger"> * </span>--}}
{{--                                        <div class="input-group">--}}
{{--                                            <input type="text" name="{{$locale}}[title]"--}}
{{--                                                   value="{{$data->translate($locale)->title ?? ""}}"--}}
{{--                                                   class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"--}}
{{--                                                   placeholder="@lang('dashboard.enter') @lang('dashboard.title') ">--}}
{{--                                            <div class="invalid-feedback">--}}
{{--                                                <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group validated">--}}
{{--                                        <label>@lang('dashboard.description')</label>--}}
{{--                                        <span class="text-danger"> * </span>--}}
{{--                                        <input--}}
{{--                                            class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"--}}
{{--                                            name="{{$locale}}[description]"--}}
{{--                                            value="{{ $data->translate($locale)->description ?? '' }}">--}}
{{--                                        <div class="invalid-feedback">--}}
{{--                                            <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                        <!-- End Tab Panels -->--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}


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
                            <strong>{{ $errors->has('categories') ? $errors->first('categories') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.address_type')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="address_type" id="place_options" class="form-control select2">
                            <option value="">{{ __('dashboard.select_type') }}</option>
                            <option value="link"
                                    @if($data->address_type == 'link') selected @endif>{{ __('dashboard.link') }}</option>
                            <option value="map"
                                    @if($data->address_type == 'map') selected @endif>{{ __('dashboard.map') }}</option>
                            <option value="latlong"
                                    @if($data->address_type == 'latlong') selected @endif>{{ __('dashboard.latlong') }}</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('address_type') ? $errors->first('address_type') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" id="link_related" style="display: {{ $data->address_type == 'link' ? '' : 'none' }}">
                <div class="form-group validated">
                    <label>@lang('dashboard.link')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                            </span>
                        </div>
                        <input type="text" name="link" value="{{ $data->address ?? ''}}"
                               class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.link') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 latlong_related" style="display: {{ $data->address_type == 'latlong' ? '' : 'none' }}">
                <div class="form-group validated">
                    <label>@lang('dashboard.latitude')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                            </span>
                        </div>
                        <input type="number" id="lat" name="lat" value="{{$data->lat ?? ''}}"
                               class="form-control {{ $errors->has('lat') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.lat') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('lat') ? $errors->first('lat') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 latlong_related" style="display: {{ $data->address_type == 'latlong' ? '' : 'none' }}">
                <div class="form-group validated">
                    <label>@lang('dashboard.longitude')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                            </span>
                        </div>
                        <input type="number" name="long" id="lng" value="{{$data->long ?? ''}}"
                               class="form-control {{ $errors->has('long') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.long') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('long') ? $errors->first('long') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12" id="map_related" style="display: {{ $data->address_type == 'map' ? '' : 'none' }}">
                <div class="form-group ">
                    <div class="input-group ">
                        <input type="text" id="pac-input" class="form-control " name="address" 
                               value="{{ old('address', ($data->address_type == 'map' ? $data->address : '')) }}"
                               placeholder="Search Box"
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                        </div>
                    </div>
                </div>

                <div id="googleMap" style="width:100%;height:400px;"></div>
                <input type="hidden" name="place_icon" id="place_icon">
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
                        <input type="url" name="facebook_link" value="{{$data->facebook_link}}"
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
                        <input type="text" name="whatsapp" value="{{$data->whatsapp}}"
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
                        <input type="url" name="instagram_link" value="{{$data->instagram_link}}"
                               class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.Instagram_link') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('instagram_link') ? $errors->first('instagram_link') : '' }}</strong>
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
                        <input type="url" name="website_link" value="{{$data->website_link}}"
                               class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.website_link') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('website_link') ? $errors->first('website_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.ticket_link')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="la la-exclamation-triangle flaticon2-world"></i>
                                                </span>
                        </div>
                        <input type="url" name="ticket_link" value="{{$data->ticket_link}}"
                               class="form-control {{ $errors->has('ticket_link') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.ticket_link') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('ticket_link') ? $errors->first('ticket_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.region')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="region_id" id="region_id" class="form-control">
                            <option value="">{{ __('dashboard.select_region') }}</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}"
                                        @if($region->id == $data->region_id) selected @endif>{{ $region->name ?? '---' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('region_id') ? $errors->first('region_id') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.city')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="city_id" id="city_id" class="form-control">
                            @foreach($mycities as $city)
                                <option
                                    value="{{$city->id }}" {{($city->id == $data->city_id) ? 'selected' :'' }}>{{ $city->name ?? '---' }}</option>
                            @endforeach

                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('city_id') ? $errors->first('city_id') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.prices')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="price_id" class="form-control select2" id="">
                            <option value="">{{ __('dashboard.select_price') }}</option>
                            @foreach($prices as $price)
                                <option value="{{ $price->id }}"
                                        @if($price->id == $data->price_id) selected @endif> {{ $price->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('price_id') ? $errors->first('price_id') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.seasons')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="seasons[]" class="form-control select2" id="" multiple>
                            <option
                                value="all_year" {{ in_array(__('dashboard.all_year' , [] , 'ar'), $data->seasons) ? 'selected ': '' }}> {{ __('dashboard.all_year') }}</option>
                            <option
                                value="spring" {{ in_array(__('dashboard.spring_season' , [] , 'ar'),$data->seasons) ? 'selected'  : '' }}>{{ __('dashboard.spring_season') }}</option>
                            <option
                                value="summer" {{ in_array(__('dashboard.summer_season' , [] , 'ar'),$data->seasons) ? 'selected'  : '' }}>{{ __('dashboard.summer_season') }}</option>
                            <option
                                value="fall" {{ in_array(__('dashboard.fall_season' , [] , 'ar'),$data->seasons) ? 'selected'  : '' }}>{{ __('dashboard.fall_season') }}</option>
                            <option
                                value="winter" {{ in_array(__('dashboard.winter_season' , [] , 'ar'),$data->seasons) ? 'selected'  : '' }}>{{ __('dashboard.winter_season') }}</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('seasons') ? $errors->first('seasons') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group row validated">
                    <div class="col-md-10">
                        <label>{{__('dashboard.image')}}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="flaticon2-image-file"></i>
                                </span>
                            </div>
                            <input type="file" name="image" accept=".png , .jpg, .jpeg"
                                   class="form-control file {{ $errors->has('image') ? 'is-invalid' : '' }}"
                                   placeholder="{{__('dashboard.enter')}} {{__('dashboard.image')}}">
                            <div class="invalid-feedback">
                                <strong>{{ $errors->has('image') ? $errors->first('image') : '' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 image">
                        <div class="image_prev_form thumb-output">
                            <img src="{{$data->image }}"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">

                <div class="form-group validated">
                    <label>@lang('dashboard.visited')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select id="" class="form-control" name="visited">
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

            <div class="col-3">
                <span class="switch switch-outline switch-icon switch-success">
                    <label style="margin:15px">@lang('dashboard.status') : </label>
                    <label>
                        <input type="checkbox" @if($data->active) checked @endif name="active"
                               value="true"/>
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

            <div class="col-3">
                <span class="switch switch-outline switch-icon switch-success">
                    <label style="margin:15px">@lang('dashboard.prefered') : </label>
                    <label>
                        <input type="checkbox" @if($data->prefered) checked @endif name="prefered"
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
