<!DOCTYPE html>
<html lang="{{ \LaravelLocalization::getCurrentLocale() }}"
      dir="{{ \LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="Updates and statistics"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

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

        .btn {
            display: inline-block;
            font-weight: 400;
            color: #3f4254;
            text-align: center;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: .65rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .42rem;
            -webkit-transition: color .3s ease-in-out, background-color .3s ease-in-out, border-color .3s ease-in-out, -webkit-box-shadow .3s ease-in-out;
            transition: color .3s ease-in-out, background-color .3s ease-in-out, border-color .3s ease-in-out, -webkit-box-shadow .3s ease-in-out;
            transition: color .3s ease-in-out, background-color .3s ease-in-out, border-color .3s ease-in-out, box-shadow .3s ease-in-out;
            transition: color .3s ease-in-out, background-color .3s ease-in-out, border-color .3s ease-in-out, box-shadow .3s ease-in-out, -webkit-box-shadow .3s ease-in-out
        }
        .btn-primary {
            color: #fff;
            background-color: #84548E;
            border-color: #84548E;
            -webkit-box-shadow: none;
            box-shadow: none
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
                                          @lang('dashboard.contractor_notifaction')
                                        </h5>
                                        <div class="mt-3"></div>
                                        <p class="text-cust-head">
                                            @lang('dashboard.your_contractor') <strong>{{ $data['contractor']->full_name }}</strong> @lang('dashboard.checked_in')
                                        </p>
                                    </div>
                                    <div class="logo mb-4">
                                        <img class="mb-2"  style="max-height: 120px; max-width: 120px;"
                                             src="{{asset('dashboard_assets/media/userCard.png')}}" alt="" width="22%">
                                    </div>
                                    <div>
                                        <p><strong>@lang('dashboard.company') : </strong><b>{{ $data['company'] }}</b></p>
                                        <p><strong>@lang('dashboard.date_time') : </strong><b>{{ $data['date'] }} {{ timeFormat($data['time']) }}</b></p>
                                    </div>
                                    <hr>
                                    <div>
                                        <a href="{{$data['url']}}" target="_blank" class="btn btn-sm btn-primary">
                                           @lang('dashboard.tab_here_to_view_metting_details')
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
