@if($add_pinned ??false == true)
    <div class="custom_add_pinned">
        <button class="btn btn-sm btn-outline-success add_pinned" data-chart_id="{{$details['card']['id']}}">
            <i class="fas fa-sm fa-plus"></i> {{__('dashboard.add_to_pinned')}}
        </button>
    </div>
@endif

@foreach($filter['columns']['data'] as $column)
    <div class="col-md-3 mb-4 mb-lg-0 mt-2">
        <div class="status-card card">
            <div class="card-header py-5">
                <div class="card-label">
                    <h4>{{__("dashboard.$column")}} [{{__("dashboard.{$filter['model_type']}")}}]</h4>
                    <p class="mb-0 text-muted">{{__('dashboard.total')}} {{__("dashboard.$column")}}</p>
                </div>
                <div class="card-icon">
                    <i class="fas fa-angle-double-up fa-2x" style="color: green"></i>
                </div>
            </div>
            <div class="card-body pb-8">
                <div class="hours">
                    <span class="num mr-3 bold text-dark-90">
                        @if($filter['unit'] == 'time')
                            <img src="{{url('/')}}/dashboard_assets/media/svg/icons/Home/Timer.svg"/>
                            {{handleTimeUnit($data['cards'][$column],$details['card']['time_unit']??null)}}
                        @elseif($filter['unit'] == 'mixed' && isExpectTime($filter['model_type'],$filter['report_list'],$column))
                            <img src="{{url('/')}}/dashboard_assets/media/svg/icons/Home/Timer.svg"/>
                            {{handleTimeUnit($data['cards'][$column],$details['card']['time_unit']??null)}}
                        @else
                            {{$data['cards'][$column]??0}}
                        @endif
                    </span>
                    <span>
                         @if($filter['unit'] == 'time')
                            {{ $details['card']['time_unit']??false
                              ? __("dashboard.{$details['card']['time_unit']}")
                              : __('dashboard.minutes')
                            }}
                        @elseif($filter['unit'] == 'mixed'  && isExpectTime($filter['model_type'],$filter['report_list'],$column))
                            {{ $details['card']['time_unit']??false
                             ? __("dashboard.{$details['card']['time_unit']}")
                             : __('dashboard.minutes')
                           }}
                        @else
                            @lang('dashboard.record')
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
@endforeach
