@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.cars_request')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.requests')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="javascript:;" class="">@lang('dashboard.cars_request')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.overview')</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-2 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">@lang('dashboard.car_request_table')</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#upload_modal">
                            <i class="la la-upload"></i>@lang('dashboard.upload_from_excel_sheet')
                        </button>
                        <a href="{{url('dashboard/car-requests/create')}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_req')
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table" id="dataTable">
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
                        @foreach ($cars as $key => $car)
                            <tr>
                            <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
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
                </div>
            </div>
        </div>
    </div>
    <!-- begin: Change Status Modal-->
    <div id="status_modal" class="modal fade" style="margin-top: 13%;">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="font-size: 16px">@lang('dashboard.confirm_request')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size: 16px;">
                        @lang('dashboard.are_you_sure')
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="status-button" class="btn btn-success">@lang('dashboard.yes')</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">@lang('dashboard.close')</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Change Status Modal-->

    <!-- begin: Upload Excel Sheet Modal-->
    <div id="upload_modal" class="modal fade" style="margin-top: 13%;">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="font-size: 16px">@lang('dashboard.upload_from_excel_sheet')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form method="post" action="{{ route('dashboard.carRequests.uploadExcel') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group">
                                <label>@lang('dashboard.file_browser')</label>
                                <div></div>
                                <div class="custom-file">
                                    <input type="file" name="car_excel" accept=".xlsx, .csv" class="custom-file-input" id="customFile"/>
                                    <label class="custom-file-label" for="customFile">@lang('dashboard.choice_file')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success add_loading">@lang('dashboard.submit')</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">@lang('dashboard.close')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end:: Upload Excel Sheet Modal-->
@endsection
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
