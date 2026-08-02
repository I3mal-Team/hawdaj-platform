@push('js')
    <script>
        $(document).on("click", ".tasksModalActive", function (e) {
            var taskId = $(this).data('id');
            e.preventDefault();
            e.stopPropagation();
            //ajax
            var url = `${HOST_URL}/${LANG}/dashboard/visits-activities/${taskId}/visitorData`;
            $.ajax({
                url: url,
                type: 'GET',
                //before success
                beforeSend: function () {
                    $("#taskModal").html('')
                },
                success: function (data) {
                    $("#taskModal").html(data)
                    $("#taskModal").modal();
                },
            });
        });

    </script>

    <script>
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        function changeAction(data) {
            var visitorReq = $("#visitorReqID").val();
            var notes = $("#notes").val();

            $(".modal").modal('hide');
            $.ajax({
                url: `${HOST_URL}/${LANG}/dashboard/visits-activities/${visitorReq}/action`,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    '_method': 'PUT',
                    'status': data,
                    'notes': notes
                },
                success: function (data) {
                    toastr[data.status](data.message);
                    window.location.reload();
                }
            });
        }
    </script>
@endpush

@if(!empty($visitorsReq->first()))
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr class="bg-gray-100">
                <th>#</th>
                <th>PID. #</th>
                <th>@lang('dashboard.visitor')</th>
                <th>@lang('dashboard.company')</th>
                <th>@lang('dashboard.schedule')</th>
                <th>@lang('dashboard.type')</th>
                <th>@lang('dashboard.host')</th>
                <th class="text-center">@lang('dashboard.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($visitorsReq as $index => $visitorReq)
            <tr id="row-{{$visitorReq->id}}">
                <td>{{++$index}}</td>
                <td class="bold">{{$visitorReq->id}}</td>
                <td class="bold">
                    <a href="javascript:;" data-id="{{$visitorReq->id}}" class="text-dark tasksModalActive"
                       style="text-decoration: underline">
                        {{ $visitorReq->visitor->full_name ?? '---'}}
                    </a>
                </td>
                <td>{{ $visitorReq->visitor->company->name ?? '---'}}</td>
                <td>{{ dateFormat($visitorReq->visitRequest->from_date)
                    . ' ' . timeFormat($visitorReq->visitRequest->from_time) }}</td>
                <td>{{ $visitorReq->visitRequest->visitType->name ?? '---'}}</td>
                <td>{{ $visitorReq->visitRequest->host->full_name ?? '---'}}</td>
                <td class="text-center" id="close_button{{$visitorReq->id}}">

                    <a class="pointer mr-2 status-button btn btn-success tasksModalActive" data-id="{{$visitorReq->id}}"
                       title="@lang('dashboard.take_action')"
                       class="btn btn-block btn-outline-success">{{ __('dashboard.take_action') }}
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@else
    @include('dashboard.includes._no-data-found')
@endif
