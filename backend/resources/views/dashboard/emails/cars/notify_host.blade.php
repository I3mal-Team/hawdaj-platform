<!DOCTYPE html>
<html lang="{{ \LaravelLocalization::getCurrentLocale() }}" dir="{{ \LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <base href="{{url('dashboard')}}">
    <meta charset="utf-8"/>
    <title>{{isset($title)? __('dashboard.main_title') .' | '.$title : __('dashboard.main_title')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Updates and statistics"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


    <style>
        .card-head{
            margin: auto !important;
        }
        .layout{
            width:50%;
            margin: auto;
            margin-top:3%;
            text-align: center !important;
        }
        div{
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
                                            <img class="mb-2 min-w-50px min-h-25px" src="{{ url('/dashboard_assets/logo/new_logo.png') }}" alt="" width="10%">
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="titels mb-4">
                                        <h5 class="text-cust-head mb-4">
                                            Driver Notifacition
                                        </h5>
                                        <div class="mt-3"></div>
                                        <p class="text-cust-head">
                                            Your Transporter <strong>{{ $data['driver_name'] }}</strong> checked in
                                        </p>
                                    </div>
                                    <div class="logo mb-4">
                                        <img class="mb-2"  style="max-height: 120px; max-width: 120px;"
                                             src="/dashboard_assets/media/userCard.png" alt="" width="22%">
                                    </div>
{{--                                    <div>--}}
{{--                                        <p><strong>Company : </strong><b>{{ $data['transporter_company'] }}</b></p>--}}
{{--                                    </div>--}}
                                    <hr>
                                    <div>
                                        <a href="{{$data['url']}}" target="_blank" class="btn btn-sm btn-primary">
                                            Tab Here To View Cars Details
                                        </a>
                                    </div>
                                    <hr>
                                    <div>
                                        <p><b>#{{ __('dashboard.invitation_id') }}</b></p>
                                        <b>{{$data['invitation_id']}}</b>
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
