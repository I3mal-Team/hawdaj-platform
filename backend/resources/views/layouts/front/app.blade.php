<!DOCTYPE html>
<html lang="{{ \LaravelLocalization::getCurrentLocale() }}"
      dir="{{ \LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <base href="{{url('dashboard')}}">
    <meta charset="utf-8"/>
    <title>{{ isset($title) ? __('dashboard.main_title') .' | '.$title : __('dashboard.main_title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Updates and statistics"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" type="text/css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo:400,700"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="{{ asset('front_assets/imgs/fav.png') }}" type="image" sizes="16x16">
    <link href="{{ asset('dashboard_assets/plugins/global/plugins.bundle.'.getFileDir().'css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('dashboard_assets/plugins/custom/prismjs/prismjs.bundle.'.getFileDir().'css') }}" rel="stylesheet"/>
    <link href="{{ asset('dashboard_assets/css/style.bundle.'.getFileDir().'css') }}" rel="stylesheet"/>
    <link href="{{ asset('dashboard_assets/plugins/custom/datatables/datatables.bundle.'.getFileDir().'css') }}" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('dashboard_assets/custom/css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('front_assets/libs/select2/select2.min.css')}}" rel="stylesheet"/>
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
        <link href="{{ asset('dashboard_assets/custom/css/custom-ar.css') }}" rel="stylesheet" type="text/css"/>
    @endif
    <style>
        .place_gallery_file img {
            width: 500px;
            height: 300px;
            left: 0;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0);
            transition: background 0.5s ease;
        }

        .container:hover .overlay {
            display: block;
            background: rgba(0, 0, 0, .3);
        }

        .button {
            position: absolute;
            width: 500px;
            left: 0;
            top: 0px;
            text-align: left;
            opacity: 0;
            transition: opacity .35s ease;
        }

        .button a {
            width: 200px;
            padding: 12px 48px;
            text-align: center;
            color: white;
            /*border: solid 2px white;*/
            z-index: 1;
        }

        .container:hover .button {
            opacity: 1;
        }
    </style>
    @stack('css')

</head>

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed page-loading">

@include('dashboard.includes.partials._page-loader')

<!--begin::Master Page-->
@yield('page')
<!--end::Master Page-->

<script>
    var HOST_URL = "{{ \URL::to('/') }}";
    var LANG = "{{ app()->getLocale() }}";
    var AuthId = "{{auth()->id()}}"
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1400
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#84548E",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };
</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDDSTB8emANyFuYPdfvIGCq_3e0ZZ6lKJc&sensor=false&libraries=places"></script>
{{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4DDathvvwuwlwnUu7F4Sow3oU22y5T1Y&callback=myMap"></script>--}}
<script>
    function init() {
        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            center: {
                lat: 24.7136,
                lng: 46.6753
            },
            zoom: 12
        });
        var searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pac-input'));
        google.maps.event.addListener(searchBox, 'places_changed', function () {
            searchBox.set('map', null);

            var places = searchBox.getPlaces();
            var bounds = new google.maps.LatLngBounds();
            var i, place;
            for (i = 0; place = places[i]; i++) {
                (function (place) {
                    var marker = new google.maps.Marker({
                        position: place.geometry.location
                    });
                    marker.bindTo('map', searchBox, 'map');
                    google.maps.event.addListener(marker, 'map_changed', function () {
                        if (!this.getMap()) {
                            this.unbindAll();
                        }
                    });
                    bounds.extend(place.geometry.location);
                }(place));
            }
            map.fitBounds(bounds);
            searchBox.set('map', map);
            map.setZoom(Math.min(map.getZoom(), 12));
        });
    }

    init()
    // google.maps.event.addDomListener(window, 'load', init);
    /*
    function myMap() {
        var mapProp= {
            center:new google.maps.LatLng(51.508742,-0.120850),
            zoom:5,
        };
        var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
    }
     */
</script>
{{--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>--}}
{{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4DDathvvwuwlwnUu7F4Sow3oU22y5T1Y&callback=myMap"></script>--}}
<script src="{{asset('dashboard_assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('dashboard_assets/plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('dashboard_assets/js/scripts.bundle.js')}}"></script>
<script src="{{asset('dashboard_assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="{{asset('dashboard_assets/plugins/global/smooth-scrollbar.js')}}"></script>
@if(request()->segment(2) == 'dashboard' && request()->segment(3) == null)
@else
    <script src="{{asset('dashboard_assets/js/pages/widgets.js')}}"></script>
@endif
<script src="{{asset('dashboard_assets/js/pages/features/miscellaneous/toastr.js')}}"></script>
<script src="{{asset('dashboard_assets/js/pages/crud/forms/widgets/select2.js')}}"></script>
<script src="{{asset('dashboard_assets/custom/js/table.js')}}" type="text/javascript"></script>
<script src="{{asset('dashboard_assets/custom/js/translate.js')}}" type="text/javascript"></script>
<script src="{{asset('dashboard_assets/custom/js/custom.js')}}" type="text/javascript"></script>
<script src="{{asset('dashboard_assets/custom/js/validation.js')}}" type="text/javascript"></script>
<script src="{{asset('dashboard_assets/custom/js/delete-item.js')}}" type="text/javascript"></script>
<script src="{{asset('front_assets/libs/select2/select2.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
@if(env('REALTIME'))
    <script src="{{asset('dashboard_assets/custom/js/notifications.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/app.js')}}"></script>
@endif
<script>
    file_excel = "{{__('dashboard.file_excel')}}";
    file_pdf = "@lang('dashboard.file_pdf')";
    file_csv = "@lang('dashboard.file_csv')";
    copy_table = "@lang('dashboard.copy_table')";
    custom_column = "@lang('dashboard.custom_column')";
    action = "@lang('dashboard.action')";
    locale = "{{ app()->getLocale() == 'ar' ? 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json'
      :'https://cdn.datatables.net/plug-ins/1.10.21/i18n/English.json' }}";

    const SettingColor = {
        'primary_color': "#1f1e2e",
        'secondary_color': "#51bae7",
        'tertiary_color': "#90218a"
    };
    $(document).ready(function () {
        let Scrollbar = window.Scrollbar;
        if (jQuery('#kt_aside').length) {
            Scrollbar.init(document.querySelector('#kt_aside'), {continuousScrolling: false});
        }
    })
</script>
@stack('js')
</body>
</html>
