<!--begin::Header-->
<div class="d-flex align-items-center justify-content-between flex-wrap p-8 bgi-size-cover bgi-no-repeat rounded-top"
     style="background-image:url('{{asset('dashboard_assets/media/users/blank.png')}}');">
    <div class="d-flex align-items-center mr-2">

        <!--begin::Symbol-->
        <div class="symbol symbol-100 mr-5">
            <div class="symbol-label" style="background-image:url('{{resolvePhoto(auth()->user()->photo ?? '')}}');">
            </div>
            <i class="symbol-badge bg-success"></i>
        </div>

        <!--end::Symbol-->

        <!--begin::Text-->
        <div class="text-white m-0 flex-grow-1 mr-3 font-size-h5">{{auth()->user()->full_name??'---'}}</div>

        <!--end::Text-->
    </div>
    <span class="label label-success label-lg font-weight-bold label-inline">{{auth()->user()->roles()->first()->label ?? '' }}</span>
</div>
<!--end::Header-->

<!--begin::Nav-->
<div class="navi navi-spacer-x-0 pt-5">

    <!--begin::Item-->
    <a href="{{route('dashboard.profile.index')}}" class="navi-item px-8">
        <div class="navi-link">
            <div class="navi-icon mr-2">
                <i class="flaticon2-calendar-3 text-success"></i>
            </div>
            <div class="navi-text">
                <div class="font-weight-bold">@lang('dashboard.my_profile')</div>
                <div class="text-muted">@lang('dashboard.account_seetings')
                    <span class="label label-light-danger label-inline font-weight-bold">@lang('dashboard.update')</span>
                </div>
            </div>
        </div>
    </a>
    <!--end::Item-->

    <!--begin::Footer-->
    <div class="navi-separator mt-3"></div>
    <div class="navi-footer px-8 py-5">
        <span  onclick="logout()" class="btn btn-light-primary font-weight-bold">@lang('dashboard.logout')</span>
        <form action="{{route('logout')}}" id="LogoutForm" method="POST">@csrf</form>
    </div>
    <!--end::Footer-->

</div>
<!--end::Nav-->
