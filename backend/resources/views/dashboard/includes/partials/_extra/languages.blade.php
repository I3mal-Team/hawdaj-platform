<!--begin::Nav-->
<ul class="navi navi-hover py-4">
    @if (app()->getLocale() != 'ar')
        <li class="navi-item">
            <a hreflang="ar" href="{{ LaravelLocalization::getLocalizedURL('ar', null, [], true) }}"
               class="navi-link">
                <span class="symbol symbol-20 mr-3">
                    <img src="{{asset('dashboard_assets/media/svg/flags/008-saudi-arabia.svg')}}" alt="" />
                </span>
                <span class="navi-text">Arabic</span>
            </a>
        </li>
    @else
        <li class="navi-item">
            <a hreflang="ar" href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}"
               class="navi-link">
            <span class="symbol symbol-20 mr-3">
                <img src="{{asset('dashboard_assets/media/svg/flags/226-united-states.svg')}}" alt="" />
            </span>
                <span class="navi-text">English</span>
            </a>
        </li>
    @endif
</ul>
