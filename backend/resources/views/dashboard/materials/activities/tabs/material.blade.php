<div class="card card-custom gutter-b mb-2 card-shadowless">
    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>@lang('dashboard.name')</th>
                <th>@lang('dashboard.description')</th>
                <th>@lang('dashboard.qty')</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($request->first()))
                @foreach($request->requests as $index => $material)
                    <tr id="row-{{$request->id}}">
                        <td>{{$material->name}}</td>
                        <td>{{$material->description ?? '---'}}</td>
                        <td>{{$material->quantity}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
