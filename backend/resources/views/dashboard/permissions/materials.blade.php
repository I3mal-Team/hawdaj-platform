@if(!empty($materialPermissions->first()))
    <table class="table" id="dataTable">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('dashboard.req') #</th>
            <th>@lang('dashboard.requester')</th>
            <th>@lang('dashboard.req_date')</th>
            <th>@lang('dashboard.type_and_date')</th>
            <th>@lang('dashboard.sending_info')</th>
            <th>@lang('dashboard.receiving_info')</th>
            <th>@lang('dashboard.status')</th>
            <th class="text-center">@lang('dashboard.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($materialPermissions as $key => $request)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $request->id }}</td>
                <td>{{ $request->requester->first_name }}</td>
                <td>{{ $request->created_at->format('Y-m-d - h:i a') }}</td>
                @if( in_array($request->type ,['inward_non-returnable','personal_request']))
                    <td>{{ __("dashboard.".$request->type) .' / '. $request->delivery_date??'---' }}</td>
                @else
                    <td>{{ __("dashboard.".$request->type) .' / '. $request->dispatch_date??'---' }}</td>
                @endif

                <td class="table-info-td">
                    @if($request->type == 'between_sites')
                        <b class="text-muted">{{__('dashboard.site')}}</b>: {{ optional($request->sender_site)->name??' ---'}}<br>
                        <b class="text-muted">{{__('dashboard.employee')}}</b>: {{ optional($request->sender_host)->full_name??' ---'}}
                    @elseif($request->type == 'personal_request')
                    @else
                        <b class="text-muted">{{__('dashboard.company')}}</b>: {{ $request->company??'---'}}<br>
                        <b class="text-muted">{{__('dashboard.contact_person_name')}}</b>: {{ $request->contact_person??'--'}}
                    @endif
                </td>

                <td class="table-info-td">
                    <b class="text-muted">{{__('dashboard.site')}}</b>: {{ $request->site->name}}<br>
                    <b class="text-muted">{{__('dashboard.employee')}}</b>: {{ $request->host->full_name}}
                </td>

                <td id="status{{$request->id}}">
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
                <td class="text-center" id="close_button{{$request->id}}">
                    @if($request->status == 'in_progress')
                        <a class="pointer status-button" title="Approve"
                           onclick="statusModal(event)">
                            <i data-url="{{ route('dashboard.materialRequests.approvedStatus', $request->id) }}"
                               data-status="approved" data-material_id="{{$request->id}}"
                               class="fas fa-qrcode font-size-h3"
                               style="color: #777;"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    @include('dashboard.includes._no-data-found')
@endif

@push('js')
    <script>
        let status_url;
        let item_status;
        let material_id;

        function statusModal(e) {
            e.preventDefault();
            status_url = e.target.dataset.url;
            item_status = e.target.dataset.status;
            material_id = e.target.dataset.material_id;
            $('#status_modal').modal();
        }

        $(document).on('click', '#status-button', function (e) {

            $("#status-button").prop('disabled', true).addClass('spinner spinner-white spinner-right').text(langs[LANG].please_wait);

            $(`#status${material_id}`).empty();
            $(`#status_loading${material_id}`).css('display', 'block');

            $.ajax({
                url: status_url,
                type: "POST",
                data: {
                    status: item_status,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#status_modal').modal('toggle');

                    $(`#status${material_id}`).html(`<span class="badge badge-success">${langs[LANG].approved}</span>`);
                    $(`#status_loading${material_id}`).css('display', 'none');
                    $(`#close_button${material_id}`).html('');
                    toastr[response.type](response.message);

                    setTimeout(() => {
                        $("#status-button").removeClass('spinner spinner-white spinner-right').prop('disabled', false).text('Yes');
                    }, 500);

                },
                error(data) {
                    $('#status_modal').modal('toggle');
                    toastr.error(data.responseJSON.message);
                    $("#status" + material_id).html(`<span class="badge badge-danger">${langs[LANG].error}</span>`);
                    $("#status_loading" + material_id).css('display', 'none');
                    setTimeout(() => {
                        $("#status-button").removeClass('spinner spinner-white spinner-right').prop('disabled', false).text('Yes');
                    }, 500);
                }
            });
        });
    </script>
@endpush
