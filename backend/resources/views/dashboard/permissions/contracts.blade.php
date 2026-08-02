@if(!empty($contractsPermissions->first()))
    <table class="table" id="dataTable">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('dashboard.req')</th>
            <th>@lang('dashboard.company')</th>
            <th>@lang('dashboard.contract_name')</th>
            <th>@lang('dashboard.from_date')</th>
            <th>@lang('dashboard.to_date')</th>
            <th>@lang('dashboard.status')</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($contractsPermissions->first()))
            @foreach($contractsPermissions as $index => $request)
                <tr id="row-{{$request->id}}">
                    <td>{{++$index}}</td>
                    <td>{{$request->id}}</td>
                    <td>{{ $request->company->name ?? '---'}}</td>
                    <td>{{ $request->contract->name ?? '---'}}</td>
                    <td>{{ dateFormat($request->from_date) ?? '---' }}</td>
                    <td>{{ dateFormat($request->to_date) ?? '---' }}</td>
                    <td>
                        @if((date('Y-m-d') >= $request->from_date) && (date('Y-m-d')<= $request->to_date))
                            <span class="badge badge-success">
                              @lang('dashboard.active')
                            </span>
                        @else
                            <span class="badge badge-danger">
                                @lang('dashboard.not_active')
                            </span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
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
@push('js')
    <script>
        let status_url;
        let item_status;
        let visit_id;

        function statusModal(e) {
            e.preventDefault();
            status_url = e.target.dataset.url;
            item_status = e.target.dataset.status;
            visit_id = e.target.dataset.visit_id;
            $('#status_modal').modal();
        }
        $(document).on('click', '#status-button', function (e) {
            $("#status-button").prop('disabled', true).addClass('spinner spinner-white spinner-right').text(langs[LANG].please_wait);
            $(`#status${visit_id}`).empty();
            $(`#status_loading${visit_id}`).css('display', 'block');

            $.ajax({
                url: status_url,
                type: "POST",
                data: {
                    status: item_status,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#status_modal').modal('toggle');
                    $(`#status${visit_id}`).html(`<span class="badge badge-danger">${langs[LANG].canceled}</span>`);
                    $(`#status_loading${visit_id}`).css('display', 'none');
                    $(`#close_button${visit_id}`).html('');
                    toastr[response.type](response.message);

                    setTimeout(() => {
                        $("#status-button").removeClass('spinner spinner-white spinner-right').prop('disabled', false).text('Yes');
                    }, 500);

                },
                error(data) {
                    $('#status_modal').modal('toggle');
                    toastr.error(data.responseJSON.message);
                    $("#status" + visit_id).html(`<span class="badge badge-danger">${langs[LANG].error}</span>`);
                    $("#status_loading" + visit_id).css('display', 'none');
                    setTimeout(() => {
                        $("#status-button").removeClass('spinner spinner-white spinner-right').prop('disabled', false).text('Yes');
                    }, 500);
                }
            });
        });
    </script>
@endpush
