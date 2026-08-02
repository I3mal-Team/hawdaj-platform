<!DOCTYPE html>
<html lang="{{ \LaravelLocalization::getCurrentLocale() }}"
      dir="{{ \LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="Updates and statistics"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

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
        .btn-danger {
            color: #fff;
            background-color: #f64e60;
            border-color: #f64e60;
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
                                            <img class="mb-2 min-w-50px min-h-25px"
                                                 src="{{ asset('dashboard_assets/logo/new_logo.png') }}" alt=""
                                                 width="10%">
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="titels mb-4">
                                        <h5 class="text-cust-head mb-4">
                                            <b>{{ __('dashboard.welcome') }} {{ $data['contractor_name'] }}</b></h5>
                                        <div class="mt-3"></div>
                                        <p class="text-cust-head">
                                           @lang('dashboard.your_meeting_has_been') <strong>@lang('dashboard.confirmed')</strong> @lang('dashboard.with'):
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
                                            <p style="text-align: center"><b></b>@lang('dashboard.permission_validity')</b></p>
                                        </div>
                                        <div class="col-6">
                                            <p><b>@lang('dashboard.start'): </b>{{ dateFormat($data['contractRequest']->from_date) }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><b>@lang('dashboard.end'): </b>{{ dateFormat($data['contractRequest']->to_date) }}</p>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6" style="display: flex">
                                            <span><b>#{{ __('dashboard.invitation_id') }}</b></span> |
                                            <b>{{$data['contractor_request_id']}}</b>
                                            @php
                                                $image = $data['contractor_request_id'].time().'.svg';
                                                $qr_image = \QrCode::size(50)
                                                            ->format('svg')
                                                            ->generate($data['contractor_request_id'], $image);
                                                $base = svgToBase64(public_path($image));
                                                 unlink(public_path($image));
                                            @endphp
                                            <div class="col-md-6 col-sm-6">
                                                <br>
                                                <img src="{!! $base !!}"/>
                                            </div>
                                        </div>
                                        <hr>
                                        <div>
                                            @if(isset($data['contractor_request_id']))
                                                <a href="{{ route('dashboard.contract-meeting-cancel',$data['contractor_request_id']) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-danger">
                                                    @lang('dashboard.cancel_meeting')
                                                </a>
                                            @endif
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
</div>
</body>
</html>
