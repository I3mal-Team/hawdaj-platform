<!DOCTYPE html>
<html lang="{{ \LaravelLocalization::getCurrentLocale() }}" dir="{{ \LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <base href="{{url('dashboard')}}">
    <meta charset="utf-8"/>
    <title>{{isset($title)? __('dashboard.main_title') .' | '.$title : __('dashboard.main_title')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Updates and statistics"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" type="text/css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo:400,700"/>
    <link rel="shortcut icon" href="{{asset('dashboard_assets/logo/Wakeb-icon.ico')}}"/>
    <link href="{{asset('dashboard_assets/css/style.bundle.'.getFileDir().'css')}}" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <style>
        .card-header{
            margin: auto !important;
        }
        .layout{
            width:50%;
            margin: auto;
            margin-top:3%;
        }
        div{
            margin-bottom: .5vw
        }
    </style>
</head>

<body>
<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 bg-white">
                <div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="">
                            <!--begin::Card-->
                            <div class="card card-custom card-border text-center mb-4 layout">
                                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                                    <div class="card-title">
                                        <div class="col-2">
                                            <img class="mb-2 min-w-50px min-h-25px" src="/dashboard_assets/logo/new_logo.png" alt="" width="10%">
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div>
                                        <h3><b>{{ __('dashboard.welcome') }} {{ $mail['name'] }}</b></h3>
                                        <div class="mt-3"></div>
                                        <p>{{ __('dashboard.meetingWith') }}</p>
                                    </div>
                                    <div>
                                        <img class="mb-2"  style="max-height: 120px; max-width: 120px;" src="/dashboard_assets/media/userCard.png" alt="" width="25%">
                                    </div>
                                    <div>
                                        <p><b>{{ $mail['hoster_name'] }}</b></p>
                                        <p><a href="{{ $mail['hoster_email'] }}">{{ $mail['hoster_email'] }}</a></p>
                                        <p><b>{{ $mail['hoster_phone'] }}</b></p>
                                    </div>
                                    <hr>
                                    <div>
                                        <a href="javascript:;">{{ __('dashboard.tabConf') }}</a>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6" style="border-right: 1px solid #888">
                                            <p><b>#{{ __('dashboard.invitation_id') }}</b></p>
                                            <b>{{ $mail['invitation_no'] }}</b>
                                        </div>
                                        <div class="col-md-6">
                                            {!! QrCode::size(50)->generate($mail['invitation_no']); !!}
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
