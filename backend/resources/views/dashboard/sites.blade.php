@extends('layouts.dashboard.app')

@push('js')
    <script>
        $(document).on('click', '#submitSite', function (e) {
            e.preventDefault();

            var selectVisitors = $('#site_id').val();
            if (selectVisitors == null) {
                toastr.error(`${langs[LANG].choose_site_first}`)

            } else if (selectVisitors.length >= 1) {
                $("#SiteForm").submit();
            } else {
                toastr.error(`${langs[LANG].choose_site_first}`)
            }
        });

        $(document).on('click', '#logoutButton', function (e) {
            e.preventDefault();
            $("#LogoutForm").submit();
        });

    </script>
@endpush

@section('page')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">

            @if(session()->has('message'))
                @include('dashboard.includes.alerts.toaster',[
                    'message' => session()->get('message'),
                    'alert_status' => session()->get('status')??'success'
                    ])
            @endif
            @error('site_id')
            @include('dashboard.includes.alerts.toaster',[
                'message' => $errors->first('site_id'),
                  'alert_status' => 'error'
                ])
            @endif

            <!--begin::Section-->
                <div class="text-center pt-15">
                    <div class="d-flex flex-column-auto flex-column pt-lg-20 pt-5">
                        <!--begin::Aside header-->
                        <a href="#" class="text-center mb-20">
                            <img src="{{asset('dashboard_assets/wakeb.png')}}" class="max-h-70px " alt="">
                        </a>
                        <!--end::Aside header-->
                    </div>
                </div>
                <!--end::Section-->
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class=" my-8">{{__('dashboard.welcome')}} {{auth()->user()->full_name}}</h3>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form id="SiteForm" class="form" action="{{route('dashboard.saveSite')}}" method="post">
                        @csrf
                        <div class="card-body ">
                            <div class="justify-content-center">
                                <div class="row">
                                    <div class="col-3"></div>
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group validated">
                                            <label>{{__('dashboard.your_current_site')}}</label>
                                            <select id="site_id" class="nice-select form-control row" name="site_id">
                                                <option value="">@lang('dashboard.select_site')</option>
                                                @foreach($sites as $site)
                                                    <option value="{{ $site->id }}"
                                                        {{session('site_id') == $site->id ? 'selected':''}}>{{ $site->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('site_id') ? $errors->first('site_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                @role('guard')
                                <div class="row">
                                    <div class="col-3"></div>

                                    <div class="col-lg-6 col-12">
                                        <div class="form-group validated">
                                            <label>{{__('dashboard.your_current_gate')}}</label>
                                            <select id="gate_id" class="nice-select form-control row" name="gate_id">
                                                <option value="">{{__('dashboard.select_gate')}}</option>
                                                @foreach($gates as $gate)
                                                    <option value="{{ $gate->id }}">{{ $gate->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('gate_id') ? $errors->first('gate_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endrole

                            </div>
                        </div>
                        <!--begin::Actions-->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-xl-3"></div>
                                <div class="col-xl-6">
                                    <button id="submitSite" type="submit" class="btn btn-primary font-weight-bold mr-2">
                                        {{__('dashboard.go_to_dashboard')}}
                                    </button>
                                    <button id="logoutButton" class="btn btn-danger font-weight-bold">{{__('dashboard.logout')}}</button>
                                </div>
                                <div class="col-xl-3"></div>
                            </div>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>

    <form id="LogoutForm" action="{{route('logout')}}" method="POST">
        @csrf
    </form>
@endsection



