<div class="dropdown-header bgi-size-cover bgi-no-repeat" style="background-image: url('{{asset('dashboard_assets/media/misc/bg-1.jpg')}}');">
    <div class="dropdown-title-cont">
        <h4 class="dropdown-title">@lang('dashboard.notifications')</h4>
        <span
            class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{$notification_count > 99 ? '99+' :$notification_count}} @lang('dashboard.new')</span>
    </div>
</div>
@if(!app('notifications')->isEmpty())
    <div class="dropdown-body">
        <div class="dropdown-items-container">
            @foreach(app('notifications') as $notifyMessage)
                @php $notify = json_decode($notifyMessage->data ?? []);
                     $notifyUrl = $notify->url ?? 'javascript:;';
                     $notifyUrl = $notify != 'javascript:;' ? url('/') .$notifyUrl: $notifyUrl;
                @endphp
                @if(isset($notify->message) && isset($notify->title))
                    <a href="{{$notifyUrl}}" class="dropdown-item">
                        <div class="content-cont">
                            <h4 class=" title">{{__($notify->title)??'---'}}</h4>
                            <p class="content">{{$notify->message??'--'}}</p>
                            <div class="time">
                                <span class="text-muted  mt-1">
                                    <i class="flaticon-event-calendar-symbol" ></i>
                                    {{dateFormat($notifyMessage->created_at??null)}}
                                </span>
                                <span class="text-muted  mt-1">
                                    <i class="fa fa-clock" aria-hidden="true"></i>
                                     {{timeFormat($notifyMessage->created_at??null)}}
                                </span>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
    <div class="dropdown-footer">
        <a href="{{round('dashboard.notifications')}}" class="text-center">
            <h4 class="">{{trans('dashboard.show_all_notifications')}}</h4>
        </a>
    </div>
@else
    <div class="dropdown-body">
        <div class="dropdown-items-container">
            <div class="d-flex flex-center text-center text-muted min-h-200px">@lang('dashboard.all_caught_up')
                <br/>@lang('dashboard.no_new_notification')
            </div>
        </div>
    </div>
    <div class="dropdown-footer" style="display: none">
        <a href="{{route('dashboard.notifications')}}" class="text-center">
            <h4 class="">{{trans('dashboard.show_all_notifications')}}</h4>
        </a>
    </div>
@endif


