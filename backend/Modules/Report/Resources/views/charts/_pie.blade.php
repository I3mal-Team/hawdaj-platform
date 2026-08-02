@foreach($filter['columns']['data'] as $column)
    <div class="my-5 col-12 col-md-6 chart-cont">
        <div class="card card-custom card-stretch">
            <div class="card-header">
                <div class="card-title pt-1 d-flex justify-content-between">
                    <h3 class="card-label">
                       <span class="d-block text-dark font-weight-bolder">
                        @php($title = \Str::snake($details['pie']['title']))
                           {{ $title ? handleTrans($title,$details['pie']['title']): __('dashboard.pie_chart') }}
                           ({{__("dashboard.$column")}})
                           <i
                               class="ml-1 fas fa-info-circle pointer" style="color: #666"
                               data-toggle="tooltip" data-placement="top"
                               title="{{$details['pie']['description'] ?? __('dashboard.pie_chart') }}"
                           ></i>
                       </span>
                    </h3>
                    @if($add_pinned ??false == true)
                        <div>
                            <button class="btn btn-sm btn-outline-success add_pinned" data-chart_id="{{$details['pie']['id']}}">
                                <i class="fas fa-sm fa-plus"></i> {{__('dashboard.add_to_pinned')}}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div @isset($chart_key) id="{{$column}}_pie_{{$chart_key}}" @else id="{{$column}}" @endif></div>
            </div>
        </div>
    </div>
@endforeach
