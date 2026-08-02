@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.events')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.events')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="{{ url('events') }}" class="text-muted">@lang('dashboard.events')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{ $title }}</a>
        </li>
    </ul>
@endsection

@push('js')
    <script>
        $("#con_type").change(
            function() {
                var getSort = $("#con_type option:selected").val();

                if (getSort == 'online') {
                    $('#place_type').css('display', 'none');
                    $('#link_address').css('display', 'block');
                    $('#map').css('display', 'none');

                } else if (getSort == 'local') {
                    $('#place_type').css('display', 'block');
                    $('#link_address').css('display', 'none');

                } else {
                    $('#place_type').css('display', 'none');
                    $('#link_address').css('display', 'none');
                }
            });


        $("#place_type").change(
            function() {
                var getSort = $("#place_type option:selected").val();

                if (getSort == 'link') {
                    $('#map').css('display', 'none');
                    $('#link_address').css('display', 'block');

                } else {
                    $('#map').css('display', 'block');
                    $('#link_address').css('display', 'none');
                }
            });
    </script>
    <script>
        // parameter when you first load the API. For example:
        var map
        var myLatLng

        function initMap() {

            myLatLng = {
                lat: {{ old('lat') ?? '24.774265' }},
                lng: {{ old('long') ?? '46.738586' }}
            };

            map = new google.maps.Map(document.getElementById('googleMap'), {
                center: myLatLng,
                zoom: 16,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            });

            var input = /** @type {!HTMLInputElement} */ (
                document.getElementById('pac-input'));

            // var types = document.getElementById('type-selector');
            // map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // map.controls[google.maps.ControlPosition.TOP_CENTER].push(types);

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();



            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                draggable: true,
            });

            // Set initial values for lat and long
            $("#lat").val(myLatLng.lat.toString());
            $("input[name='long']").val(myLatLng.lng.toString());

            // Update lat and long when marker is dragged
            google.maps.event.addListener(marker, 'dragend', function(evt) {
                var lat = evt.latLng.lat();
                var lng = evt.latLng.lng();
                $("#lat").val(lat);
                $("input[name='long']").val(lng);
            });

            autocomplete.addListener('place_changed', function() {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();

                if (!place.geometry) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17); // Why 17? Because it looks good.
                }
                marker.setIcon( /** @type {google.maps.Icon} */ ({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                var item_Lat = place.geometry.location.lat()
                var item_Lng = place.geometry.location.lng()
                var item_Location = place.formatted_address;


                //alert("Lat= "+item_Lat+"__Lang="+item_Lng+"__Location="+item_Location);
                $("#lat").val(item_Lat);
                $("input[name='long']").val(item_Lng);
                $("#pac-input").val(item_Location);

                // Remove old dragend listener and add new one
                google.maps.event.clearListeners(marker, 'dragend');
                google.maps.event.addListener(marker, 'dragend', function(evt) {
                    var lat = evt.latLng.lat();
                    var lng = evt.latLng.lng();
                    $("#lat").val(lat);
                    $("input[name='long']").val(lng);
                });
                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);


            });

            // Sets a listener on a radio button to change the filter type on Places
            // Autocomplete.
            function setupClickListener(id, types) {
                var radioButton = document.getElementById(id);
                radioButton.addEventListener('click', function() {
                    autocomplete.setTypes(types);
                });
            }

            setupClickListener('changetype-all', []);
            setupClickListener('changetype-address', ['address']);
            setupClickListener('changetype-establishment', ['establishment']);
            setupClickListener('changetype-geocode', ['geocode']);

        }
    </script>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDSTB8emANyFuYPdfvIGCq_3e0ZZ6lKJc&libraries=places&callback=initMap" async defer></script> --}}
@endpush

@section('content')

    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
{{--                @if($errors->count() > 0)--}}
{{--                    @foreach ($errors->all() as $error)--}}
{{--                        <div>{{ $error }}</div>--}}
{{--                    @endforeach--}}
{{--                @endif--}}
                <div class="col-md-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                </div>
                            </div>
                        </div>
                        <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
                            action="{{ route('dashboard.events.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.title')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <input type="text" name="title" value="{{ old('title') ?? '' }}"
                                                    class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.title') "
                                                    aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.categories')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <select name="categories[]" class="form-control select2" id=""
                                                    multiple>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ in_array($category->id, old('categories') ?: []) ? 'selected' : '' }}>
                                                            {{ $category->name ?? '---' }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('categories.*') ? $errors->first('categories.*') : '' }}</strong>
                                                    <strong>{{ $errors->has('categories') ? $errors->first('categories') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}


                                    <div class="col-md-12">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.description')</label>
                                            <span class="text-danger"> * </span>
                                            <textarea class="form-control description {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" rows="5">{{ old('description') }}</textarea>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ------------add online or local--------- --}}



                                    <div class="col-md-3" id="place_type">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.type')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <select class="form-control select2" name="type">
                                                    <option value="">{{ __('dashboard.select_type') }}</option>
                                                    <option value="link" {{ old('type') == 'link' ? 'selected ' : '' }}>
                                                        {{ __('dashboard.link') }}</option>
                                                    <option value="map" {{ old('type') == 'map' ? 'selected ' : '' }}>
                                                        {{ __('dashboard.map') }}</option>
                                                    <option value="latlng"
                                                        {{ old('type') == 'latlng' ? 'selected ' : '' }}>
                                                        {{ __('dashboard.latlong') }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('type') ? $errors->first('type') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-md-6">
                                        <div class="form-group validated " id="link_address" style="display: none">
                                            <label>@lang('dashboard.link')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="address"
                                                    class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.link') "
                                                    aria-describedby="basic-addon1" value="{{ old('address') }}">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 map mb-6" id="map" style="display: none">

                                        <div class="form-group ">
                                            <div class="input-group ">
                                                <input type="hidden" id="lat" name="lat" value="{{ old('lat', '24.774265') }}">
                                                <input type="hidden" id="lng" name="long" value="{{ old('long', '46.738586') }}">
                                                <input type="text" id="pac-input" class="form-control "
                                                    name="address_place" placeholder="Search Box"
                                                    aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('lat') ? $errors->first('lat') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="googleMap" style="width:100%;height:400px;"> </div>
                                    </div>


                                    {{-- <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.facebook')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i
                                                            class="la la-exclamation-triangle flaticon-facebook-letter-logo"></i>
                                                    </span>
                                                </div>
                                                <input type="url" name="facebook" value="{{ old('facebook') }}"
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
                                                        <i
                                                            class="la la-exclamation-triangle flaticon-twitter-logo"></i>
                                                    </span>
                                                </div>
                                                <input type="url" name="x" value="{{ old('x') }}"
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
                                                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}"
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
                                                <input type="url" name="instagram" value="{{ old('instagram') }}"
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
                                                <input type="url" name="website" value="{{ old('website') }}"
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
                                                <input type="url" name="video_url" value="{{ old('video_url') }}"
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
                                                <input type="url" name="ticket_link"
                                                    value="{{ old('ticket_link') }}"
                                                    class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.ticket_link') "
                                                    aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('ticket_link') ? $errors->first('ticket_link') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">

                                        <div class="form-group validated">
                                            <label>@lang('dashboard.type')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <select id="" class="form-control select2" name="display_type">
                                                    <option value="">{{ __('dashboard.select_type') }}</option>
                                                    @foreach (\App\Models\Event::$types as $k => $type)
                                                        <option value="{{ $k }}"
                                                            {{ old('display_type') == $k ? 'selected ' : '' }}>{{ $type }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('display_type') ? $errors->first('display_type') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.date_from')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon2-world"></i>
                                                    </span>
                                                </div>
                                                <input type="date" name="date_from" value="{{ old('date_from') }}"
                                                    class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.date_from') "
                                                    aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('date_from') ? $errors->first('date_from') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.date_to')</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon2-world"></i>
                                                    </span>
                                                </div>
                                                <input type="date" name="date_to" value="{{ old('date_to') }}"
                                                    class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                                                    placeholder="@lang('dashboard.enter') @lang('dashboard.date_to') "
                                                    aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('date_to') ? $errors->first('date_to') : '' }}</strong>
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
                                                    <option value="1" {{ old('visited') == '1' ? 'selected ' : '' }}>
                                                        {{ __('dashboard.yes') }}</option>
                                                    <option value="0" {{ old('visited') == '0' ? 'selected' : '' }}>
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
                                                    <img src="{{ asset('dashboard_assets/media/blank.png') }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.region')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <select name="region_id" id="region_id" class="form-control select2">
                                                    <option value="">{{ __('dashboard.select_region') }}</option>
                                                    @foreach ($regions as $region)
                                                        <option value="{{ $region->id }}"
                                                            {{ old('region_id') == $region->id ? 'selected ' : '' }}>
                                                            {{ $region->name ?? '---' }}</option>
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
                                            <div id="ringle" class="lds-dual-ring" style="display:none;"></div>

                                            <div class="input-group">
                                                <select name="city_id" id="city_id" class="form-control select2"
                                                    required>
                                                    <option value="">{{ __('dashboard.select_city') }}</option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            {{ old('city_id') == $city->id ? 'selected ' : '' }}>
                                                            {{ $city->name ?? '---' }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('city_id') ? $errors->first('city_id') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <span class="switch switch-outline switch-icon switch-success">
                                            <label style="margin:15px">@lang('dashboard.status') : </label>

                                            <label>
                                                <input type="checkbox" checked="checked" name="active"
                                                    value="true" />
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>


                                    <div class="col-3">
                                        <span class="switch switch-outline switch-icon switch-success">
                                            <label style="margin:15px">@lang('dashboard.featured') : </label>

                                            <label>
                                                <input type="checkbox" checked="checked" name="featured"
                                                    value="true" />
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>

                                    @include('dashboard.includes.partials._order_id_field')

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="kt-form__actions">
                                    <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                                    <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
