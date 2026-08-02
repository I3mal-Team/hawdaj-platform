<style>
    .menu-icon-wrapper {
        display: inline-block;
        position: relative;
        line-height: 1;
    }
    
    .menu-icon-wrapper .menu-icon {
        position: relative;
        z-index: 1;
    }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #f1416c;
        color: white;
        border-radius: 50%;
        padding: 0;
        font-size: 9px;
        font-weight: bold;
        min-width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        border: 2px solid #8a72c2;
        z-index: 2;
        line-height: 1;
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        50% {
            transform: scale(1.15);
            box-shadow: 0 2px 8px rgba(241, 65, 108, 0.5);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
    }
</style>

<!--begin::Aside-->
<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">

    <!--begin::Brand-->
    <div class="brand flex-column-auto" id="kt_brand" style="background-color: #8a72c2;">
        <!--begin::Logo-->
        <a href="{{ url('/') }}" class="brand-logo">
{{--            <img alt="Logo" class="w-90px" src="{{ asset('dashboard_assets/logo/new_logo.png') }}"/>--}}
            <img alt="Logo" class="w-90px" src="{{ asset('dashboard_assets/new_logo/logo3.png') }}"/>
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
                <li class="menu-item {{ activeMenu(3, null) }}" aria-haspopup="true">
                    <a href="{{ url('/') }}" class="menu-link">
                        <i class="menu-icon flaticon2-architecture-and-city text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.home')</span>
                    </a>
                </li>

                @if(auth()->user()->can('read-placesCategory') || auth()->user()->can('read-places'))
                    <li class="menu-item menu-item-submenu {{ activeMenu(3, 'places', '/setting/categories') }}"
                        aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <i class="menu-icon flaticon2-location text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.places')</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                @if(auth()->user()->can('read-placesCategory'))
                                    <li class="menu-item {{ activeMenu(4, 'categories') }}" aria-haspopup="true">
                                        <a href="{{ route('dashboard.categories.index') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-line">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">@lang('dashboard.categories')</span>
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->can('read-places'))
                                    <li class="menu-item {{ activeMenu(4, 'places') }}" aria-haspopup="true">
                                        <a href="{{ route('dashboard.places.index') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-line">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">@lang('dashboard.places')</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if(auth()->user()->can('read-users_places'))
                    @php
                        $pendingRequestsCount = \App\Models\LandMark::where('active', 0)->count();
                    @endphp
                    <li class="menu-item {{ activeMenu(3, 'users_places') }}" aria-haspopup="true">
                        <a href="{{ route('dashboard.users_places.index') }}" class="menu-link">
                            <span class="menu-icon-wrapper position-relative">
                                <i class="menu-icon flaticon2-user text-light-white"></i>
                                @if($pendingRequestsCount > 0)
                                    <span class="notification-badge pulse-animation">{{ $pendingRequestsCount > 99 ? '99+' : $pendingRequestsCount }}</span>
                                @endif
                            </span>
                            <span class="menu-text text-light-white">@lang('dashboard.user_places')</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-storesCategory') || auth()->user()->can('read-store'))
                    <li class="menu-item menu-item-submenu {{ activeMenu(3, 'stores') }}" aria-haspopup="true"
                        data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <i class="menu-icon flaticon2-shopping-cart text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.stores')</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                @if(auth()->user()->can('read-storesCategory'))
                                    <li class="menu-item {{ activeMenu(4, 'store-categories') }}" aria-haspopup="true">
                                    <a href="{{ route('dashboard.store-categories.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-line">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">@lang('dashboard.categories')</span>
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()->can('read-store'))
                                    <li class="menu-item {{ activeMenu(4, 'stores') }}" aria-haspopup="true">
                                    <a href="{{ route('dashboard.stores.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-line">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">@lang('dashboard.stores')</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if(auth()->user()->can('read-zadElgadelCategory') || auth()->user()->can('read-zadElgadel') || auth()->user()->can('read-zadElgadelFood') || auth()->user()->can('read-zadElgadelOffer'))
                    <li class="menu-item menu-item-submenu {{ activeMenu(3, 'zad_elgadels') }}" aria-haspopup="true"
                    data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <i class="menu-icon flaticon2-shopping-cart text-light-white"></i>
                        <span class="menu-text text-light-white">@lang('dashboard.zad_elgadels')</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            @if(auth()->user()->can('read-zadElgadelCategory'))
                                <li class="menu-item {{ activeMenu(4, 'zad_elgadel-categories') }}" aria-haspopup="true">
                                <a href="{{ route('dashboard.zad_elgadel-categories.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.categories')</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->can('read-zadElgadelFood'))
                                <li class="menu-item {{ activeMenu(4, 'zad_elgadel-food-categories') }}" aria-haspopup="true">
                                <a href="{{ route('dashboard.zad_elgadel-food-categories.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.food-categories')</span>
                                </a>
                            </li>
                            @endif
{{--                            @if(auth()->user()->can('read-zadElgadelFood'))--}}
                                <li class="menu-item {{ activeMenu(4, 'menu') }}" aria-haspopup="true">
                                <a href="{{ route('dashboard.menu.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.menu')</span>
                                </a>
                            </li>
{{--                            @endif--}}
                            @if(auth()->user()->can('read-zadElgadel'))
                                <li class="menu-item {{ activeMenu(4, 'zad_elgadels') }}" aria-haspopup="true">
                                <a href="{{ route('dashboard.zad_elgadels.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.zad_elgadels')</span>
                                </a>
                            </li>
                            @endif
                            @if(auth()->user()->can('read-zadElgadelOffer'))
                                <li class="menu-item {{ activeMenu(5, 'offers') }}" aria-haspopup="true">
                                <a href="{{ route('dashboard.offer.index') }}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">@lang('dashboard.Offers')</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(auth()->user()->can('read-opinion'))
                    <li class="menu-item {{ activeMenu(3, 'opinions') }}" aria-haspopup="true">
                        <a href="{{ route('dashboard.opinions.index') }}" class="menu-link">
                            <i class="menu-icon flaticon2-group text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.opinions')</span>
                        </a>
                    </li>
                @endif


                @if(auth()->user()->can('read-swalefs') || auth()->user()->can('read-swalef-categories'))
                    <li class="menu-item menu-item-submenu {{ activeMenu(3, 'swalefs') }}" aria-haspopup="true"
                        data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <i class="menu-icon flaticon2-shopping-cart text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.swalefs')</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                @if(auth()->user()->can('read-swalef-categories'))
                                    <li class="menu-item {{ activeMenu(4, 'swalef-categories') }}" aria-haspopup="true">
                                        <a href="{{ route('dashboard.swalef-categories.index') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-line">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">@lang('dashboard.categories')</span>
                                        </a>
                                    </li>
                                @endif

                                @if(auth()->user()->can('read-swalefs'))
                                    <li class="menu-item {{ activeMenu(4, 'swalefs') }}" aria-haspopup="true">
                                        <a href="{{ route('dashboard.swalefs.index') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-line">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">@lang('dashboard.swalefs')</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if(auth()->user()->can('read-suggest'))
                    <li class="menu-item {{ activeMenu(3, 'suggests') }}" aria-haspopup="true">
                        <a href="{{ route('dashboard.suggests.index') }}" class="menu-link">
                            <i class="menu-icon flaticon-truck text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.suggests')</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-event'))
                    <li class="menu-item menu-item-submenu {{ activeMenu(3, 'events') }}" aria-haspopup="true"
                        data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <i class="menu-icon flaticon2-shopping-cart text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.events')</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                @if(auth()->user()->can('read-event'))
                                <li class="menu-item {{ activeMenu(4, 'events') }}" aria-haspopup="true">
                                    <a href="{{ route('dashboard.events.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-line">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">@lang('dashboard.events')</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

                @if(auth()->user()->can('read-application-categories') || auth()->user()->can('read-applications'))
                    <li class="menu-item menu-item-submenu {{ activeMenu(3, 'applications') }}" aria-haspopup="true"
                        data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            <i class="menu-icon flaticon2-shopping-cart text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.applications')</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">

                                @if(auth()->user()->can('read-application-categories'))
                                    <li class="menu-item {{ activeMenu(4, 'applications', '/setting/categories') }}" aria-haspopup="true">
                                        <a href="{{ route('dashboard.application-categories.index') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-line">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">@lang('dashboard.categories')</span>
                                        </a>
                                    </li>
                                @endif

                                @if(auth()->user()->can('read-applications'))
                                    <li class="menu-item {{ activeMenu(5, 'applications') }}" aria-haspopup="true">
                                        <a href="{{ route('dashboard.applications.index') }}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-line">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">@lang('dashboard.applications')</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif

           
                    <li class="menu-item {{ activeMenu(3, 'sliders') }}" aria-haspopup="true">
                        <a href="{{ route('dashboard.sliders.index') }}" class="menu-link">
                            <i class="menu-icon flaticon2-photo-camera text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.sliders')</span>
                        </a>
                    </li>
            

                @if(auth()->user()->can('read-guides'))
                    <li class="menu-item {{ activeMenu(3, 'guides') }}" aria-haspopup="true">
                        <a href="{{ route('dashboard.guides.index') }}" class="menu-link">
                            <i class="menu-icon flaticon2-mail-1 text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.guides')</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-region') ||auth()->user()->can('read-city') ||auth()->user()->can('read-price') ||auth()->user()->can('read-feature') || auth()->user()->can('read-setting') || auth()->user()->can('read-translationsSetting'))
                    <li class="menu-item menu-item-submenu {{ activeMenu(3, 'setting') }}" aria-haspopup="true"
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

                            @if(auth()->user()->can('read-region') ||auth()->user()->can('read-city') ||auth()->user()->can('read-price') ||auth()->user()->can('read-feature'))
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

                                        @if(auth()->user()->can('read-region'))
                                        <li class="menu-item {{ activeMenu(4, 'regions') }}" aria-haspopup="true">
                                            <a href="{{ route('dashboard.regions.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-line">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">@lang('dashboard.regions')</span>
                                            </a>
                                        </li>
                                        @endif

                                        @if(auth()->user()->can('read-city'))
                                        <li class="menu-item {{ activeMenu(4, 'cities') }}" aria-haspopup="true">
                                            <a href="{{ route('dashboard.cities.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-line">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">@lang('dashboard.cities')</span>
                                            </a>
                                        </li>
                                        @endif

                                        @if(auth()->user()->can('read-price'))
                                        <li class="menu-item {{ activeMenu(4, 'prices') }}" aria-haspopup="true">
                                            <a href="{{ route('dashboard.prices.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-line">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">@lang('dashboard.prices')</span>
                                            </a>
                                        </li>
                                        @endif

                                        @if(auth()->user()->can('read-feature'))
                                        <li class="menu-item {{ activeMenu(4, 'features') }}" aria-haspopup="true">
                                            <a href="{{ route('dashboard.features.index') }}" class="menu-link">
                                                <i class="menu-bullet menu-bullet-line">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">@lang('dashboard.features')</span>
                                            </a>
                                        </li>
                                        @endif

                                    </ul>
                                </div>
                            </li>
                            @endif

                            @if(auth()->user()->can('read-setting'))
                                <li class="menu-item {{ activeMenu(4, 'general setting') }}" aria-haspopup="true">
                                    <a href="{{ route('dashboard.settings.edit') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-line">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">@lang('dashboard.general_setting')</span>
                                    </a>
                                </li>
                            @endif

                            @if(auth()->user()->can('read-translationsSetting'))
                                <li class="menu-item {{ activeMenu(4, 'translation setting') }}" aria-haspopup="true">
                                    <a href="{{ url(app()->getLocale().'/dashboard/setting/translations') }}"
                                       class="menu-link">
                                        <i class="menu-bullet menu-bullet-line">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">@lang('dashboard.translation_setting')</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(auth()->user()->can('read-mailTemplate'))
                    <li class="menu-item {{ activeMenu(3, 'mail_template') }}" aria-haspopup="true">
                        <a href="{{ route('dashboard.mail_template.index') }}" class="menu-link">
                            <i class="menu-icon flaticon2-mail-1 text-light-white"></i>
                            <span class="menu-text text-light-white">@lang('dashboard.menu_templates')</span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('read-user') || auth()->user()->can('read-role') || auth()->user()->can('read-permission'))
                    <li class="menu-item menu-item-submenu {{ activeMenu(3, 'users', 'roles', 'permissions') }}"
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

                            @if(auth()->user()->can('read-user'))
                                <li class="menu-item {{ activeMenu(3, 'users') }}" aria-haspopup="true">
                                    <a href="{{ route('dashboard.users.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-line">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">@lang('dashboard.users')</span>
                                    </a>
                                </li>
                            @endif

                            @if(auth()->user()->can('read-role'))
                                <li class="menu-item {{ activeMenu(3, 'roles') }}" aria-haspopup="true">
                                    <a href="{{ route('dashboard.roles.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-line">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">@lang('dashboard.roles')</span>
                                    </a>
                                </li>
                            @endif

                            @if(auth()->user()->can('read-permission'))
                                <li class="menu-item {{ activeMenu(3, 'permissions') }}" aria-haspopup="true">
                                    <a href="{{ route('dashboard.permissions.index') }}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-line">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">@lang('dashboard.permissions')</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

            </ul>
            <!--end::Menu Nav-->
        </div>
        <!--end::Menu Container-->
    </div>
    <!--end::Aside Menu-->
</div>
<!--end::Aside-->
