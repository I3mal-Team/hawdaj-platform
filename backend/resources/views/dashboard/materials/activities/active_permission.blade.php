@if(!empty($requests->first()))
    <table class="table table-bordered" id="dataTable">
        <thead>
        <tr>
            <th>PID</th>
            <th>@lang('dashboard.permission_date')</th>
            <th>@lang('dashboard.type_and_date')</th>
            <th>@lang('dashboard.sending_info')</th>
            <th>@lang('dashboard.receiving_info')</th>
            <th>@lang('dashboard.status')</th>
            <th class="text-center">@lang('dashboard.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($requests as $index => $request)
            <tr id="row-{{$request->id}}">
                <td>{{$request->id}}</td>
                <td>{{ dateFormat($request->created_at) ?? '---' }}</td>
                @if( in_array($request->type ,['inward_non-returnable','personal_request']))
                    <td>{{ __("dashboard.".$request->type) .' / '. $request->delivery_date??'---' }}</td>
                @else
                    <td>{{ __("dashboard.".$request->type) .' / '. $request->dispatch_date??'---' }}</td>
                @endif

                <td>
                    @if($request->type == 'between_sites')
                        <small>{{__('dashboard.site')}}</small>: {{ optional($request->sender_site)->name}}<br>
                        <small>{{__('dashboard.employee')}}</small>: {{ optional($request->sender_host)->full_name}}
                    @elseif($request->type == 'personal_request')
                    @else
                        <small>{{__('dashboard.company')}}</small>: {{ $request->company??'---'}}<br>
                        <small>{{__('dashboard.contact_person_name')}}</small>: {{ $request->contact_person??'--'}}
                    @endif
                </td>

                <td>
                    <small>{{__('dashboard.site')}}</small>: {{ $request->site->name}}<br>
                    <small>{{__('dashboard.employee')}}</small>: {{ $request->host->full_name}}
                </td>
                <td>
                @if($request->status == 'approved')
                    <span class="badge badge-success">
                        {{ handleTrans($request->status) }}
                    </span>
                @elseif($request->status == 'rejected' || $request->status == 'canceled')
                    <span class="badge badge-danger">
                        {{ handleTrans($request->status) }}
                    </span>
                @else
                    <span class="badge badge-primary">
                        {{ handleTrans($request->status) }}
                    </span>
                @endif
                </td>
                <td>
                    <a type="button" id="activitiesModalActive" data-id="{{ $request['id'] }}"
                       class="btn btn-block btn-outline-success">{{ __('dashboard.take_action') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    @include('dashboard.includes._no-data-found')
@endif

