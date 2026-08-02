<!--begin::Header-->
<div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top"
     style="background-image: url('{{asset('dashboard_assets/media/misc/bg-1.jpg')}}');">

    <!--begin::Title-->
    <h4 class="d-flex flex-center rounded-top">
        <span class="text-white">@lang('dashboard.notifications')</span>
        <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{$notification_count > 99 ? '99+' :$notification_count}} @lang('dashboard.new')</span>
    </h4>
    <!--end::Title-->

    <!--begin::Tabs-->
    <ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3 px-8"
        role="tablist">
        <li class="nav-item">
            <a class="nav-link active show" data-toggle="tab" href="#topbar_notifications_notifications">@lang('dashboard.alerts')</a>
        </li>
    </ul>
    <!--end::Tabs-->
</div>
<!--end::Header-->

<!--begin::Content-->
<div class="tab-content">

@if(!app('notifications')->isEmpty())
    <!--begin::Tabpane-->
        <div class="tab-pane active show p-8 notification-dropdown" id="topbar_notifications_notifications"
             role="tabpanel">
            <!--begin::Scroll-->
            <div class="scroll" data-scroll="true" data-height="300" data-mobile-height="200">
                <!--begin::Item-->
                <div class="d-flex align-items-center mb-6">
                    <div class="dropdown-items-container">
                    @foreach(app('notifications') as $notifyMessage)
                        @php $notify = json_decode($notifyMessage->data ?? []);
                             $notifyUrl = $notify->url ?? 'javascript:;';
                             $notifyUrl = $notify != 'javascript:;' ? url('/') .$notifyUrl: $notifyUrl;
                        @endphp
                        @if(isset($notify->message) && isset($notify->title))
                            <!--begin::Item-->
                                <div class="d-flex align-items-center mb-6">
                                    <!--begin::Text-->
                                    <div class="d-flex flex-column font-weight-bold" style="width: 100% !important;">
                                        <a href="{{$notifyUrl}}" class="text-dark text-hover-primary mb-1 font-size-lg">
                                            {{__($notify->title)??'---'}}
                                        </a>
                                        <span class="text-muted">{{$notify->message??'--'}}</span>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-dark-50 text-right mt-1">
                                                {{dateFormat($notifyMessage->created_at??null)}}
                                            </span>
                                            <span class="text-dark-50  text-left mt-1">
                                                 {{timeFormat($notifyMessage->created_at??null)}}
                                            </span>
                                        </div>
                                    </div>
                                    <!--end::Text-->
                                </div>
                                <!--end::Item-->
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <!--begin::Action-->
            <div class="d-flex flex-center pt-7">
                <a href="{{round('dashboard.notifications')}}"
                   class="btn btn-light-primary font-weight-bold text-center">@lang('dashboard.see_all')</a>
            </div>
            <!--end::Action-->
        </div>
@else
    <!--begin::Tabpane-->
        <div class="tab-pane active show p-8" id="topbar_notifications_notifications" role="tabpanel">
            <!--begin::Nav-->
            <div class="d-flex flex-center text-center text-muted min-h-200px">@lang('dashboard.all_caught_up')
                <br/>@lang('dashboard.no_new_notification')
            </div>
            <!--end::Nav-->
        </div>
@endif
<!--end::Tabpane-->
</div>
<!--end::Content-->
