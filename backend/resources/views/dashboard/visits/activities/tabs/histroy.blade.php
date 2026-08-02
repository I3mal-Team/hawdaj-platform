<div class="card card-custom card-shadowless text-center">
    <div class="card-body">
    @if($visitorReq->histroy->isNotEmpty())
        <!--begin: Datatable-->
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>@lang('dashboard.date')</th>
                <th>@lang('dashboard.time')</th>
                <th>@lang('dashboard.type')</th>
                <th>@lang('dashboard.comment')</th>
            </tr>
            </thead>
            <tbody>

                @foreach($visitorReq->histroy as $index => $history)
                    <tr id="row-{{$history->id}}">
                        <td>{{$history->date}}</td>
                        <td>{{$history->time ?? '---'}}</td>
                        <td>{{handleTrans($history->activity_type) ?? '---'}}</td>
                        <td>{{$history->comment??'---'}}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        @else
            <div class="mt-5 text-center">
                <img
                    src="{{ asset('dashboard_assets/media/no-data.png') }}"
                    alt=""
                />
            </div>


        @endif
    </div>
</div>
