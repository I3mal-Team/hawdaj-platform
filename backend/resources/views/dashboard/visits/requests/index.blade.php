@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.visit_request')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.requests')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="javascript:;" class="">@lang('dashboard.visit_request')</a>
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
                        <h3 class="card-label">@lang('dashboard.visits_table')</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{url('dashboard/visits/create')}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_request')
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.req')</th>
                            <th>@lang('dashboard.requester')</th>
                            <th>@lang('dashboard.req_date')</th>
                            <th>@lang('dashboard.department')</th>
                            <th>@lang('dashboard.visitor')</th>
                            <th>@lang('dashboard.host')</th>
                            <th>@lang('dashboard.visit_from')</th>
                            <th>@lang('dashboard.visit_to')</th>
                            <th>@lang('dashboard.status')</th>
                            <th class="text-center">@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($visits->first()))
                            @foreach($visits as $index => $visit)
                                <tr id="row-{{$visit->id}}">
                                    <td>{{++$index}}</td>
                                    <td>{{$visit->id}}</td>
                                    <td>
                                        <strong>{{ $visit->requester->full_name ?? '---'}}</strong>
                                    </td>
                                    <td>{{ dateFormat($visit->created_at) ?? '---' }}</td>
                                    <td>{{ $visit->department->name ?? '---'}}</td>
                                    <td>
                                        @foreach($visit->visitors as $visitor)
                                            <strong>{{$visitor->full_name}}</strong>
                                            @if(!$loop->last),@endif
                                        @endforeach
                                    </td>
                                    <td>{{ $visit->host->full_name ?? '---'}}</td>
                                    <td>{{ dateFormat($visit->from_date) ?? '---' }}</td>
                                    <td>{{ dateFormat($visit->to_date) ?? '---' }}
                                        <div class="spinner spinner-danger mr-15 "
                                             style="display:none; right: -131%; top: -9px;"
                                             id="status_loading{{$visit->id}}">
                                        </div>
                                    </td>
                                    <td id="status{{$visit->id}}">
                                        @if($visit->status == 'active')
                                            <span class="badge badge-success">
                                              @lang('dashboard.active')
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                {{ handleTrans($visit->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center" id="close_button{{$visit->id}}">
                                        @if($visit->status == 'active')
                                            <a class="pointer status-button" title="{{__('dashboard.cancel_visit')}}"
                                               onclick="statusModal(event)">
                                                <i data-url="{{ route('dashboard.visits.status', $visit->id) }}"
                                                   data-status="canceled" data-visit_id="{{$visit->id}}"
                                                   class="fas fa-times-circle font-size-h3"
                                                   style="color: #777;"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
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
                    <h4 class="modal-title" style="font-size: 16px">@lang('dashboard.confirm_cancel_request')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p style="font-size: 16px;">
                        @lang('dashboard.are_you_sure_cancel')
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="status-button" class="btn btn-success">@lang('dashboard.yes')</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">@lang('dashboard.close')</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Delete Modal-->
@endsection

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
