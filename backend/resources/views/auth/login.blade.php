@extends('layouts.dashboard.app')

@push('css')
    <link href="{{asset('dashboard_assets/css/pages/login/login-1.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .button-container > * {
            width: 100%;
            margin-bottom: 5px;
        }
    </style>
@endpush

@push('js')
    <script>
        $(".login-form #login").on("click", function (e) {
            e.preventDefault();
            login();
        });

        function login() {
            var email = $('.login-form input[name="email"]').val();
            var password = $('.login-form input[name="password"]').val();

            $('button#login').html("{{__("Login in progress")}}");
            $('.login-form #u_email').html("");
            $('.login-form #u_password').html("");


            $.ajax({
                url: "{{ route('login') }}",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    email,
                    password
                },
                type: 'POST',
                success: function () {
                    window.location.replace("{{url("/")}}");
                },
                error(data) {
                    const keys = Object.keys(data.responseJSON);
                    keys.forEach(key => $('.login-form #u_' + key).html(data.responseJSON[key]).show());
                    $('button#login').html("{{ __('dashboard.login') }}")
                }
            });
        }

    </script>
@endpush
@section('page')
    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Aside-->
        <div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #8a72c2;">
            <!--begin::Aside Top-->
            <div class="d-flex flex-column-auto flex-column pt-lg-30 pt-15">
                <!--begin::Aside header-->
                <a href="{{url("/")}}" class="text-center mb-20">
                    <img src="{{asset('dashboard_assets/logo.svg')}}" class="max-h-60px" alt="">
{{--                    <img src="{{asset('dashboard_assets/logo.svg')}}" class="max-h-60px" alt="">--}}
                </a>
                <!--end::Aside header-->
                <div class="d-flex mt-5 flex-column justify-content-center text-center text-white-50">
                    <h3 class="display4 mb-1 font-weight-bolder">{{'Hawdaj'}}</h3>
                    <p class="font-weight-bolder font-size-h2-md font-size-lg opacity-70">
                        Hawdaj Mangment System
                    </p>
                </div>

                <!--begin::Aside title-->
                <h3 class="font-weight-bolder text-center font-size-h4 font-size-h1-lg" style="color: #986923;">

                </h3>
                <!--end::Aside title-->
            </div>
            <!--end::Aside Top-->
            <div
                class="content-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center"></div>
            {{--                 style="background-image: url('{{asset('dashboard_assets/media/svg/illustrations/login-visual-2.svg')}}');"></div>--}}
        </div>
        <!--begin::Aside-->
        <!--begin::Content-->
        <div
            class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-center">
                <!--begin::Signin-->
                <div class="login-form login-signin">
                    <!--begin::Form-->
                    <form class="form">
                        @csrf
                        <div class="pb-5 pb-lg-15">
                            <h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">@lang('dashboard.sign_in')</h3>
                        </div>

                        <div class="form-group">
                            <label class="font-size-h6 font-weight-bolder text-dark">@lang('dashboard.email')</label>
                            <input
                                class="form-control form-control-solid h-auto py-7 px-6 rounded-lg border-0  {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                type="email" value="{{ old('email')}}" name="email" autocomplete="off"/>
                            <p id="u_email" style="display: none;color: red">{{ __("Please enter the data for this field") }}</p>

                        </div>

                        <div class="form-group mt-10">
                            <div class="d-flex justify-content-between mt-n5">
                                <label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
                                <a href="{{url('password/reset')}}"
                                   class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5"
                                   id="kt_login_forgot">{{__("Forgot Password ?")}}</a>
                            </div>
                            <input class="form-control form-control-solid h-auto p-6 rounded-lg" type="password"
                                   value="{{ old('password')}}" name="password" autocomplete="off"/>
                            <p id="u_password" style="display: none;color: red">{{ __("Please enter the data for this field") }}</p>

                        </div>

                        <div class="pb-lg-0 pb-5 text-center button-container">
                            <button id="login" type="button"
                                    class=" btn font-weight-bolder font-size-h6 py-4 my-3 mr-3"
                                    style="background-color: #8a72c2; color: white">@lang('dashboard.sign_in')
                            </button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Signin-->
            </div>
            <!--end::Content body-->
        </div>
        <!--end::Content-->
    </div>
@endsection

