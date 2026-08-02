<div class="my-5 col-12 col-md-6 chart-cont">
    <div class="card card-custom card-stretch">
        <div class="card-header ">
            <div class="card-title pt-1 d-flex justify-content-between flex-wrap">
                <h3 class="card-label">
                    <span class="d-block text-dark font-weight-bolder">
                        @php($title = \Str::snake($details['line']['title']))
                        {{ $title ? handleTrans($title,$details['line']['title']): __('dashboard.line_chart') }}
                         <i class="ml-1 fas fa-info-circle pointer" style="color: #666"
                             data-toggle="tooltip" data-placement="top"
                             title="{{$details['line']['description'] ?? __('dashboard.line_chart') }}"
                         ></i>
                    </span>
                </h3>
                @if($add_pinned ??false == true)
                    <div>
                        <button class="btn btn-sm btn-outline-success add_pinned" data-chart_id="{{$details['line']['id']}}">
                            <i class="fas fa-sm fa-plus"></i> {{__('dashboard.add_to_pinned')}}
                        </button>
                    </div>
                @endif
                @if(!$filter['draft'])
                    <div class="card-toolbar justify-content-end">
                        <ul data-type="column"
                            class="chart-filter-cont nav nav-pills nav-pills-sm nav-dark-75">
                            <li class="nav-item">
                                <a class="nav-link py-2 px-4" data-value="17">
                                    <span class="nav-text font-size-sm">{{__("dashboard.this_year")}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2 px-4" data-value="16">
                                    <span class="nav-text font-size-sm">{{__("dashboard.this_month")}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2 px-4" data-value="14">
                                    <span class="nav-text font-size-sm">{{__("dashboard.this_week")}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-2 px-4 " data-value="today">
                                    <span class="nav-text font-size-sm">{{__("dashboard.today")}}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

        </div>
        <div class="card-body">
            <div @isset($chart_key) id="line_{{$chart_key}}" @else id="chart2" @endif></div>
        </div>
    </div>
</div>
