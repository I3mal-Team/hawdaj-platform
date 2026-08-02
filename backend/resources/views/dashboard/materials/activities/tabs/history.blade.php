<div class="card card-custom gutter-b mb-2 card-shadowless">
    <div class="card-body">
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
            @if(!empty($request->first()))
                @foreach($request->histories as $index => $history)
                    <tr id="row-{{$history->id}}">
                        <td>{{$history->date}}</td>
                        <td>{{$history->time ?? '---'}}</td>
                        <td>{{handleTrans($history->activity_type) ?? '---'}}</td>
                        <td>{{$history->comment??'---'}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
