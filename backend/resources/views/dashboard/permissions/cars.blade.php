@if(!empty($carsPermissions->first()))
    <table class="table table-bordered" id="dataTable">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('dashboard.req') #</th>
            <th>@lang('dashboard.requester')</th>
            <th>@lang('dashboard.req_date')</th>
            <th>@lang('dashboard.driver')</th>
            <th>@lang('dashboard.plate_en')</th>
            <th>@lang('dashboard.plate_ar')</th>
            <th>@lang('dashboard.receiving_info')</th>
            <th>@lang('dashboard.status')</th>
            <th class="text-center">@lang('dashboard.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($carsPermissions as $key => $car)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $car->id }}</td>
                <td>{{ $car->requester->first_name }}</td>
                <td>{{ $car->created_at->format('Y-m-d h:i a') }}</td>
                <td>{{ $car->driver->contact_person_name }}</td>
                <td>{{ $car->car->plate_en }}</td>
                <td>{{ $car->car->plate_ar }}</td>
                <td>{{ handleTrans($car->site->name)}}</td>
                <td id="status{{$car->id}}">
                    @if($car->status == 'approved')
                        <span class="badge badge-success">
                                            {{ __("dashboard.$car->status") }}
                                        </span>
                    @elseif($car->status == 'rejected' || $car->status == 'canceled')
                        <span class="badge badge-danger">
                                             {{ __("dashboard.$car->status") }}
                                        </span>
                    @else
                        <span class="badge badge-primary">
                                            {{ __("dashboard.$car->status") }}
                                        </span>
                    @endif
                </td>
                <td class="text-center" id="close_button{{$car->id}}">
                    @if($car->status == 'in_progress')
                        <a class="pointer status-button" title="Approve"
                           onclick="statusModal(event)">
                            <i data-url="{{ route('dashboard.carRequests.approvedStatus', $car->id) }}"
                               data-status="approved" data-car_id="{{$car->id}}"
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
        let car_id;

        function statusModal(e) {
            e.preventDefault();
            status_url = e.target.dataset.url;
            item_status = e.target.dataset.status;
            car_id = e.target.dataset.car_id;
            $('#status_modal').modal();
        }

        $(document).on('click', '#status-button', function (e) {

            $("#status-button").prop('disabled', true).addClass('spinner spinner-white spinner-right').text(langs[LANG].please_wait);

            $(`#status${car_id}`).empty();
            $(`#status_loading${car_id}`).css('display', 'block');

            $.ajax({
                url: status_url,
                type: "POST",
                data: {
                    status: item_status,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#status_modal').modal('toggle');

                    $(`#status${car_id}`).html(`<span class="badge badge-success">Approved</span>`);
                    $(`#status_loading${car_id}`).css('display', 'none');
                    $(`#close_button${car_id}`).html('');
                    toastr[response.type](response.message);

                    setTimeout(() => {
                        $("#status-button").removeClass('spinner spinner-white spinner-right').prop('disabled', false).text('Yes');
                    }, 500);

                },
                error(data) {
                    $('#status_modal').modal('toggle');
                    toastr.error(data.responseJSON.message);
                    $("#status" + car_id).html(`<span class="badge badge-danger">${langs[LANG].error}</span>`);
                    $("#status_loading" + car_id).css('display', 'none');
                    setTimeout(() => {
                        $("#status-button").removeClass('spinner spinner-white spinner-right').prop('disabled', false).text('Yes');
                    }, 500);
                }
            });
        });
    </script>
@endpush
