<form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
      action="{{ route('dashboard.events.update', $data->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.title')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input type="text" name="title" value="{{$data->translate(default_lang()) ? $data->translate(default_lang())->title : ''}}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.title') ">
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
                    <textarea class="form-control description {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" rows="5" placeholder="@lang('dashboard.enter') @lang('dashboard.description') ">{{ $data->translate(default_lang()) ? $data->translate(default_lang())->description : ''}}</textarea>
                    <div class="invalid-feedback">
                        <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                    </div>
                </div>
            </div>

{{--            {{ dd($data->addressType) }}--}}
            <div class="col-md-3">
                <div class="form-group validated">
                    <label>@lang('dashboard.address_type')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="type" id="place_type" class="form-control select2">
                            <option value="">{{ __('dashboard.select_type') }}</option>
                            <option value="link" {{ $data->addressType == 'link' ? 'selected' : '' }}> {{ __('dashboard.link') }}</option>
                            <option value="map" {{ $data->addressType == 'map' || $data->addressType == 'latlng' ? 'selected' : '' }}> {{ __('dashboard.map') }}</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('type') ? $errors->first('type') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6" id="link_address"
                 style="display: {{ $data->address_type == 'link' ? 'block' : 'none' }}">
                <div class="form-group validated">
                    <label>@lang('dashboard.link')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                            </span>
                        </div>
                        <input type="text" name="address" value="{{ $data->address ?? '' }}"
                               class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.link') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 map mb-4" style="display: {{ $data->address_type == 'map' ? 'block' : 'none' }}">
                <div class="form-group ">
                    <div class="input-group ">
                        <input type="hidden" id="lat" name="lat" value="{{ old('lat', $data->lat) ?? '' }}">
                        <input type="hidden" id="lng" name="long"
                               value="{{ old('long', $data->long) ?? '' }}">
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
                        <input type="url" name="facebook" value="{{ $data->facebook ?? '' }}"
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
                        <input type="url" name="x" value="{{ $data->x ?? '' }}"
                               class="form-control {{ $errors->has('x') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.x') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('x') ? $errors->first('x') : '' }}</strong>
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
                        <input type="text" name="whatsapp" value="{{ $data->whatsapp ?? '' }}"
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
                        <input type="url" name="instagram" value="{{ $data->instagram ?? '' }}"
                               class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.instagram') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('instagram') ? $errors->first('instagram') : '' }}</strong>
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
                        <input type="url" name="website" value="{{ $data->website ?? '' }}"
                               class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.website') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('website') ? $errors->first('website') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.video_url')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon2-world"></i>
                            </span>
                        </div>
                        <input type="url" name="video_url" value="{{ $data->video_url ?? '' }}"
                               class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.video_url') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('video_url') ? $errors->first('video_url') : '' }}</strong>
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
                        <input type="url" name="ticket_link" value="{{ $data->ticket_link ?? '' }}"
                               class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.ticket_link') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('ticket_link') ? $errors->first('ticket_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-4">

                <div class="form-group validated">
                    <label>@lang('dashboard.type')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select id="" class="form-control select2" name="display_type">
                            <option value="">{{ __('dashboard.select_type') }}</option>
                            @foreach (\App\Models\Event::$types as $k => $type)
                                <option value="{{ $k }}"
                                    {{ old('display_type', $data->display_type) == $k ? 'selected ' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach

                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('display_type') ? $errors->first('display_type') : '' }}</strong>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="form-group validated">
                    <label>@lang('dashboard.date_from')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-date_from-letter-logo"></i>
                            </span>
                        </div>
                        <input type="date" name="date_from" value="{{ $data->date_from ?? '' }}"
                               class="form-control {{ $errors->has('date_from') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.date_from') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('date_from') ? $errors->first('date_from') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group validated">
                    <label>@lang('dashboard.date_to')</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-exclamation-triangle flaticon-date_to-letter-logo"></i>
                            </span>
                        </div>
                        <input type="date" name="date_to" value="{{ $data->date_to ?? '' }}"
                               class="form-control {{ $errors->has('date_to') ? 'is-invalid' : '' }}"
                               placeholder="@lang('dashboard.enter') @lang('dashboard.date_to') "
                               aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('date_to') ? $errors->first('date_to') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
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

            <div class="col-md-4">
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
            <div class="col-4">
                <div class="form-group validated">
                    <label>@lang('dashboard.visited')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select id="" class="form-control select2" name="visited">
                            <option value="">{{ __('dashboard.select_type') }}</option>
                            <option value="1" {{ $data->visited == '1' ? 'selected' : '' }}>
                                {{ __('dashboard.yes') }}</option>
                            <option value="0" {{ $data->visited == '0' ? 'selected' : '' }}>
                                {{ __('dashboard.no') }}</option>
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
                        <label>{{ __('dashboard.image') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="flaticon2-image-file"></i>
                                </span>
                            </div>
                            <input type="file" name="image" accept=".png , .jpg, .jpeg"
                                   class="form-control file {{ $errors->has('image') ? 'is-invalid' : '' }}"
                                   placeholder="{{ __('dashboard.enter') }} {{ __('dashboard.image') }}">
                            <div class="invalid-feedback">
                                <strong>{{ $errors->has('image') ? $errors->first('image') : '' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 image">
                        <div class="image_prev_form thumb-output">
                            <img src="{{ asset($data->image) }}"/>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-3">
                <span class="switch switch-outline switch-icon switch-success">
                    <label style="margin:15px">@lang('dashboard.status') : </label>
                    <label>
                        <input type="checkbox" @if ($data->active) checked @endif name="active"
                               value="true"/>
                        <span></span>
                    </label>
                </span>
            </div>

            <div class="col-3">
                <span class="switch switch-outline switch-icon switch-success">
                    <label style="margin:15px">@lang('dashboard.featured') : </label>
                    <label>
                        <input type="checkbox" @if ($data->featured) checked @endif name="featured"
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
