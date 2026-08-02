<!DOCTYPE html>
<html lang="{{ \LaravelLocalization::getCurrentLocale() }}"
      dir="{{ \LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <base href="{{url('dashboard')}}">
    <meta charset="utf-8"/>
    <title>{{isset($title)? __('dashboard.main_title') .' | '.$title : __('dashboard.main_title')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Updates and statistics"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" type="text/css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo:400,700"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @stack('before_css')
    <link rel="icon" href="{{ asset('front_assets/imgs/fav.png') }}" type="image" sizes="16x16">
    <link href="{{asset('dashboard_assets/plugins/global/plugins.bundle.'.getFileDir().'css')}}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_o5hd5vvqpoqiwwmi.css">

    <link href="{{asset('dashboard_assets/plugins/custom/prismjs/prismjs.bundle.'.getFileDir().'css')}}"
          rel="stylesheet"/>
    <link href="{{asset('dashboard_assets/css/style.bundle.'.getFileDir().'css')}}" rel="stylesheet"/>
    <link href="{{asset('dashboard_assets/plugins/custom/datatables/datatables.bundle.'.getFileDir().'css')}}"
          rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="{{asset('dashboard_assets/custom/css/custom.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('front_assets/libs/select2/select2.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{asset('dashboard_assets/css/pages/file_uploads/dropzone.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('dashboard_assets/css/pages/file_uploads/dropzone.css')}}">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
          integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous"/>

          
    @if(LaravelLocalization::getCurrentLocale() == 'ar')
        <link href="{{asset('dashboard_assets/custom/css/custom-ar.css')}}" rel="stylesheet" type="text/css"/>
    @endif
    <style>

        .lds-dual-ring {
            display: inline-block;
            width: 20px;
            height: 20px;
        }

        .lds-dual-ring:after {
            content: " ";
            display: block;
            width: 20px;
            height: 20px;
            margin: 0 5px;
            border-radius: 50%;
            border: 6px solid #fff;
            border-color: #66428f transparent #66428f transparent;
            animation: lds-dual-ring 1.2s linear infinite;
        }

        @keyframes lds-dual-ring {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

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
<script src="{{asset('dashboard_assets/custom/js/delete-item.js')}}" type="text/javascript"></script>
<script src="{{asset('front_assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('dashboard_assets/custom/js/ckeditor-global.js')}}" type="text/javascript"></script>
<script src="{{asset('dashboard_assets/js/pages/crud/file-upload/dropzone.min.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"
        integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

<script>

    $(document).ready(function () {
        $('.select2').select2();

        $('#show-enteries').on('change' , function(e){
            let currentPath = window.location.href;
            if(currentPath.includes('?')){
                currentPath = currentPath + '&per_page=' + $(this).val();
            }else{
                currentPath = currentPath + '?per_page=' + $(this).val();                
            }             
            window.location.href = currentPath;
        });

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
    locale = "{{ app()->getLocale() == 'ar' ? 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json':'https://cdn.datatables.net/plug-ins/1.10.21/i18n/English.json' }}";

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

<script>
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    function initMap() {
        var map = new google.maps.Map(document.getElementById('googleMap'), {
            center: {lat: {{ old("latitude") ?? '24.774265' }}, lng: {{ old("longitude") ?? '46.738586' }}},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,

        });
        var input = /** @type {!HTMLInputElement} */(
            document.getElementById('pac-input'));

        // var types = document.getElementById('type-selector');
        // map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
        // map.controls[google.maps.ControlPosition.TOP_CENTER].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();

        var myLatLng = {lat: {{ old("lat") ?? '24.774265' }}, lng: {{ old("long") ?? '46.738586' }}};

        var marker = new google.maps.Marker({

            position: myLatLng,

            map: map,
            draggable: true,
        });


        autocomplete.addListener('place_changed', function () {
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
                map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setIcon(/** @type {google.maps.Icon} */({
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
            $("#lng").val(item_Lng);
            $("#location").val(item_Location);

            google.maps.event.addListener(marker, 'dragend', function (evt) {
                document.getElementById('current').innerHTML = '<p>Marker dropped: Current Lat: ' + evt.latLng.lat().toFixed(3) + ' Current Lng: ' + evt.latLng.lng().toFixed(3) + '</p>';
                item_Lat = evt.latLng.lat().toFixed(3);
                item_Lng = evt.latLng.lng().toFixed(3);
                item_Location = place.formatted_address;
                $("#lat").val(item_Lat);
                $("#lng").val(item_Lng);
                $("#pac-input").val(item_Location);

            });

            google.maps.event.addListener(marker, 'dragstart', function (evt) {
                document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
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
            radioButton.addEventListener('click', function () {
                autocomplete.setTypes(types);
            });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);
    }
</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDSTB8emANyFuYPdfvIGCq_3e0ZZ6lKJc&libraries=places&callback=initMap"
    async defer>

</script>
@stack('js')

</body>
</html>
