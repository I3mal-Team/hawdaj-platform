<!--begin::Header-->
<div id="kt_header" class="header header-fixed">

    <!--begin::Container-->
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <div>
            @if(session('site_name'))
                @if(auth()->user()->roles->contains('name','root'))
                    <span class="text-white my-5 badge badge-success" style="font-size: 14px">
                    Login as Root
                </span>
                @else
                    <span class="text-white my-5 badge badge-success" style="font-size: 14px">
                   @lang('dashboard.current_site') : {{handleTrans(session('site_name')) }}
                </span>
                @endif
            @endif
        </div>

        <!--begin::Topbar-->
        <div class="topbar">

            <!--begin::Languages-->
            <div class="dropdown">

                <!--begin::Toggle-->
                <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                    <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1">
                        @if (app()->getLocale() != 'ar')
                            <img class="h-20px w-20px rounded-sm"
                                 src="{{asset('dashboard_assets/media/svg/flags/226-united-states.svg')}}" alt=""/>
                        @else
                            <img class="h-20px w-20px rounded-sm"
                                 src="{{asset('dashboard_assets/media/svg/flags/008-saudi-arabia.svg')}}" alt=""/>
                        @endif
                    </div>
                </div>
                <!--end::Toggle-->

                <!--begin::Dropdown-->
                <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                    @include('dashboard.includes.partials._extra.languages')
                </div>
                <!--end::Dropdown-->

            </div>
            <!--end::Languages-->

            <!--begin::User-->
            <div class="dropdown">

                <!--begin::Toggle-->
                <div class="topbar-item" data-toggle="dropdown" data-offset="0px,0px">
                    <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2">
                        <span
                            class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">@lang('dashboard.hi')</span>
                        <span
                            class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{auth()->user()->full_name ?? ''}}</span>
                        <span class="symbol symbol-35 mr-5">
							<div class="symbol-label"
                                 style="background-image:url('{{resolvePhoto(auth()->user()->photo ?? '','user')}}');">
                            </div>
                        </span>
                    </div>
                </div>
                <!--end::Toggle-->

                <!--begin::Dropdown-->
                <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg p-0">
                    @include('dashboard.includes.partials._extra.user')
                </div>
                <!--end::Dropdown-->

            </div>
            <!--end::User-->
        </div>
        <!--end::Topbar-->

    </div>
    <!--end::Container-->

</div>
<!--end::Header-->
