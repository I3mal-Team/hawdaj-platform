<div class="my-5 col-12 col-md-6 chart-cont">
    <div class="card card-custom card-stretch">
        <div class="card-header">
            <div class="card-title pt-1 d-flex justify-content-between">
                <h3 class="card-label">
                   <span class="d-block text-dark font-weight-bolder">
                        @php($title = \Str::snake($details['table']['title']))
                       {{ $title ? handleTrans($title,$details['table']['title']): __('dashboard.table_chart') }}
                        <i class="ml-1 fas fa-info-circle pointer" style="color: #666"
                            data-toggle="tooltip" data-placement="top"
                            title="{{$details['table']['description'] ?? __('dashboard.table_chart') }}"
                        ></i>
                    </span>
                </h3>
                @if($add_pinned ??false == true)
                    <div>
                        <button class="btn btn-sm btn-outline-success add_pinned" data-chart_id="{{$details['table']['id']}}">
                            <i class="fas fa-sm fa-plus"></i> {{__('dashboard.add_to_pinned')}}
                        </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table class="table ">
                <thead>
                <tr>
                    @foreach($data['table']['columns'] as $column)
                        <th class="th-sm">{{__("dashboard.$column")}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @for($i = 0,$iMax = count($data['table']['table']); $i < $iMax; $i++)
                    <tr style="cursor: pointer;" class="data-record" data-toggle="modal">
                        @foreach($data['table']['table'][$i] as $item)
                            <td>{{$item}}</td>
                        @endforeach
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
