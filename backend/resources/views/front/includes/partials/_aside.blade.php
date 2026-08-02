<!--begin::Aside-->
<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">

    <!--begin::Brand-->
    <div class="brand flex-column-auto" id="kt_brand" style="background-color: #8a72c2;">
        <!--begin::Logo-->
        <a href="{{url('/')}}" class="brand-logo">
            <img alt="Logo" class="w-90px" src="{{asset('dashboard_assets/logo/new_logo.png')}}"/>
        </a>
        <!--end::Logo-->
    </div>

    <!--end::Brand-->

    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu my-4 aside-menu-dropdown" data-menu-vertical="1"
             data-menu-dropdown="1" data-menu-scroll="0" data-menu-dropdown-timeout="500">

            <!--begin::Menu Nav-->
            <ul class="menu-nav ">
                <li class="menu-item {{activeMenu(3,null)}}" aria-haspopup="true">
                    <a href="{{url('/')}}" class="menu-link">
                        <i class="menu-icon flaticon2-architecture-and-city text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.home')</span>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu {{activeMenu(3,'places','/setting/categories')}}"
                    aria-haspopup="true"
                    data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <i class="menu-icon flaticon2-location text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.places')</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{activeMenu(4,'categories')}}" aria-haspopup="true">
                                <a href="{{route('dashboard.categories.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.categories')</span>
                                </a>
                            </li>

                            <li class="menu-item {{activeMenu(4,'places')}}" aria-haspopup="true">
                                <a href="{{route('dashboard.places.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.places')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item menu-item-submenu {{activeMenu(3,'stores')}}" aria-haspopup="true"
                    data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <i class="menu-icon flaticon2-shopping-cart text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.stores')</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{activeMenu(4,'store-categories')}}" aria-haspopup="true">
                                <a href="{{route('dashboard.store-categories.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.categories')</span>
                                </a>
                            </li>
                            <li class="menu-item {{activeMenu(4,'stores')}}" aria-haspopup="true">
                                <a href="{{route('dashboard.stores.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.stores')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item {{activeMenu(3,'vendors')}}" aria-haspopup="true">
                    <a href="{{route('dashboard.vendors.index')}}" class="menu-link">
                        <i class="menu-icon flaticon2-group text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.vendors')</span>
                    </a>
                </li>

                <li class="menu-item {{activeMenu(3,'caravans')}}" aria-haspopup="true">
                    <a href="{{route('dashboard.caravans.index')}}" class="menu-link">
                        <i class="menu-icon flaticon-truck text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.caravans')</span>
                    </a>
                </li>

                {{--                <li class="menu-item {{activeMenu(3,'places')}}"--}}
                {{--                    aria-haspopup="true" data-menu-toggle="hover">--}}
                {{--                    <a href="{{route('dashboard.places.index')}}" class="menu-link">--}}
                {{--                        <i class="menu-icon flaticon2-location"></i>--}}
                {{--                        <span></span>--}}
                {{--                        <span class="menu-text">@lang('dashboard.places')</span>--}}
                {{--                    </a>--}}
                {{--                </li>--}}
                {{--                <li class="menu-item menu-item-submenu {{activeMenu(3,'requests','material-requests','car-requests','contract-requests')}}"--}}
                {{--                    aria-haspopup="true" data-menu-toggle="hover">--}}
                {{--                    <a href="javascript:;" class="menu-link menu-toggle">--}}
                {{--                        <i class="menu-icon flaticon2-calendar-6"></i>--}}
                {{--                        <span class="menu-text">@lang('dashboard.requests')</span>--}}
                {{--                        <i class="menu-arrow"></i>--}}
                {{--                    </a>--}}
                {{--                    <div class="menu-submenu">--}}
                {{--                        <i class="menu-arrow"></i>--}}
                {{--                        <ul class="menu-subnav">--}}
                {{--                            <li class="menu-item menu-item-parent" aria-haspopup="true">--}}
                {{--                                <span class="menu-link">--}}
                {{--										<span class="menu-text">@lang('dashboard.requests')</span>--}}
                {{--                                </span>--}}
                {{--                            </li>--}}
                {{--                            --}}{{--                            <li class="menu-item {{activeMenu(3,'requests')}}" aria-haspopup="true">--}}
                {{--                                <a href="{{route('dashboard.visits.index')}}" class="menu-link">--}}
                {{--                                    <i class="menu-bullet menu-bullet-line">--}}
                {{--                                        <span></span>--}}
                {{--                                    </i>--}}
                {{--                                    <span class="menu-text">@lang('dashboard.visits_request')</span>--}}
                {{--                                </a>--}}
                {{--                            </li>--}}
                {{--                            <li class="menu-item {{activeMenu(3,'materials')}}" aria-haspopup="true">--}}
                {{--                                <a href="{{route('dashboard.material-requests.index')}}" class="menu-link">--}}
                {{--                                    <i class="menu-bullet menu-bullet-line">--}}
                {{--                                        <span></span>--}}
                {{--                                    </i>--}}
                {{--                                    <span class="menu-text">@lang('dashboard.material_request')</span>--}}
                {{--                                </a>--}}
                {{--                            </li>--}}
                {{--                            <li class="menu-item {{activeMenu(3,'car-requests')}}" aria-haspopup="true">--}}
                {{--                                <a href="{{route('dashboard.car-requests.index')}}" class="menu-link">--}}
                {{--                                    <i class="menu-bullet menu-bullet-line">--}}
                {{--                                        <span></span>--}}
                {{--                                    </i>--}}
                {{--                                    <span class="menu-text">@lang('dashboard.car_request')</span>--}}
                {{--                                </a>--}}
                {{--                            </li>--}}
                {{--                            <li class="menu-item {{activeMenu(3,'contract-requests')}}" aria-haspopup="true">--}}
                {{--                                <a href="{{route('dashboard.contract-requests.index')}}" class="menu-link">--}}
                {{--                                    <i class="menu-bullet menu-bullet-line">--}}
                {{--                                        <span></span>--}}
                {{--                                    </i>--}}
                {{--                                    <span class="menu-text">@lang('dashboard.contract_request')</span>--}}
                {{--                                </a>--}}
                {{--                            </li>--}}

                {{--                        </ul>--}}
                {{--                    </div>--}}
                {{--                </li>--}}

                <li class="menu-item menu-item-submenu {{activeMenu(3,'setting')}}" aria-haspopup="true"
                    data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <i class="menu-icon menu-icon flaticon-settings-1 text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.setting')</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">@lang('dashboard.setting')</span>
                                </span>
                            </li>

                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.data_entry')</span>
                                    <i class="menu-arrow"></i>
                                </a>
                                <div class="menu-submenu">
                                    <i class="menu-arrow"></i>
                                    <ul class="menu-subnav">
                                        <li class="menu-item {{activeMenu(4,'regions')}}" aria-haspopup="true">
                                            <a href="{{route('dashboard.regions.index')}}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-line">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">@lang('dashboard.regions')</span>
                                            </a>
                                        </li>

                                        <li class="menu-item {{activeMenu(4,'cities')}}" aria-haspopup="true">
                                            <a href="{{route('dashboard.cities.index')}}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-line">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">@lang('dashboard.cities')</span>
                                            </a>
                                        </li>

                                        <li class="menu-item {{activeMenu(4,'prices')}}" aria-haspopup="true">
                                            <a href="{{route('dashboard.prices.index')}}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-line">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">@lang('dashboard.prices')</span>
                                            </a>
                                        </li>

                                        <li class="menu-item {{activeMenu(4,'features')}}" aria-haspopup="true">
                                            <a href="{{route('dashboard.features.index')}}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-line">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">@lang('dashboard.features')</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item {{activeMenu(4,'general setting')}}" aria-haspopup="true">
                                <a href="{{route('dashboard.settings.edit')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.general_setting')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item {{activeMenu(3,'mail_template')}}" aria-haspopup="true">
                    <a href="{{route('dashboard.mail_template.index')}}" class="menu-link">
                        <i class="menu-icon flaticon2-mail-1 text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.menu_templates')</span>
                    </a>
                </li>

                <li class="menu-item menu-item-submenu {{activeMenu(3,'users','roles','permissions')}}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <i class="menu-icon flaticon-users text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.users')</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
										<span class="menu-text">@lang('dashboard.user_managements')</span>
                                </span>
                            </li>

                            <li class="menu-item {{activeMenu(3 ,'users')}}" aria-haspopup="true">
                                <a href="{{route('dashboard.users.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.users')</span>
                                </a>
                            </li>
                            <li class="menu-item {{activeMenu(3,'roles')}}" aria-haspopup="true">
                                <a href="{{route('dashboard.roles.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.roles')</span>
                                </a>
                            </li>
                            <li class="menu-item {{activeMenu(3,'permissions')}}" aria-haspopup="true">
                                <a href="{{route('dashboard.permissions.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.permissions')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>


            </ul>

            <!--end::Menu Nav-->
        </div>

        <!--end::Menu Container-->
    </div>

    <!--end::Aside Menu-->
</div>
<!--end::Aside-->
