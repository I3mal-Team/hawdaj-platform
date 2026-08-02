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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>


    <style>
        .card-head {
            margin: auto !important;
        }

        .layout {
            width: 50%;
            margin: auto;
            margin-top: 3%;
            text-align: center !important;
        }

        div {
            margin-bottom: .5vw;
            text-align: center !important;
        }
    </style>

</head>

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed page-loading">
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 bg-white">
                <div class="row justify-content-md-center">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="bg-white rounded p-10" style="margin-bottom: 34%">
                            <!--begin::Card-->
                            <div class="card card-custom card-border text-center mb-4 layout">
                                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                                    <div class="card-title">
                                        <div class="col-2">
                                            <img class="mb-2 min-w-50px min-h-25px"
                                                 src="{{ url('/dashboard_assets/logo/new_logo.png') }}" alt=""
                                                 width="10%">
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="titels mb-4">
                                        <h5 class="text-cust-head mb-4">
                                            <b>{{ __('dashboard.welcome') }} {{ $data['transporter_name'] }}</b></h5>
                                        <div class="mt-3"></div>
                                        <p class="text-cust-head">
                                            Your Material Request has been <strong>confirmed</strong> with:
                                        </p>
                                    </div>
                                    <div class="logo mb-4">
                                        <img class="mb-2" style="max-height: 120px; max-width: 120px;"
                                             src="/dashboard_assets/media/userCard.png" alt="" width="22%">
                                    </div>
                                    <div>
                                        <p><b>{{ $data['host']->full_name }}</b></p>
                                        <p><a href="'mailto:{{ $data['host']->email }}'">{{ $data['host']->email }}</a>
                                        </p>
                                        <p><b>{{ $data['host']->phone }}</b></p>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <p style="text-align: center"><b>Permission Validity</b></p>
                                        </div>
                                        <div class="col-6">
                                            <p><b>Start: </b>{{ dateFormat($data['materialRequest']->from_date) }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><b>From: </b>{{ timeFormat($data['materialRequest']->from_fromtime) }}
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <p><b>End: </b>{{ dateFormat($data['materialRequest']->to_date) }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><b>To: </b>{{ timeFormat($data['materialRequest']->to_totime) }}</p>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="col-md-6 col-sm-6">
                                                <span><b>#Transporter Request ID</b></span> |
                                                <b>{{$data['material_request_id']}}</b>
                                                @php
                                                    $image = $data['material_request_id'].time().'.svg';
                                                    $qr_image = \QrCode::size(50)
                                                                ->format('svg')
                                                                ->generate($data['material_request_id'], $image);

                                                    $base = svgToBase64(public_path($image));
                                                     unlink(public_path($image));
                                                @endphp
                                                <div class="col-md-6 col-sm-6">
                                                    <br>
                                                    <img src="{!! $base !!}"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
