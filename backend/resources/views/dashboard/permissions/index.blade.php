@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.permissions')</h5>
    <ul class="breadcrumb breadcrumb-transpart breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.permissions')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.overview')</a>
        </li>
    </ul>
@endsection

@push('js')
    <script src="{{asset('dashboard_assets/custom/js/activities.js')}}" type="text/javascript"></script>
    <script src="{{asset('dashboard_assets/custom/js/activities.js')}}" type="text/javascript"></script>
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

                    $(`#status${visit_id}`).html(`<span class="badge badge-danger">Canceled</span>`);
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

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
           <div class="card card-custom">
               <div class="card-body">
                   <div class="example-preview  custom-nav  mb-15">
                       <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">
                           <li class="nav-item">
                               <a class="nav-link {{in_array(request('type'),['visitors',null]) ? 'active' : ''}}"
                                  href="{{route('dashboard.permissionMeetings',['type'=>'visitors'])}}">
                                    <span class="nav-icon">
                                        <i class="flaticon-users-1"></i>
                                    </span>
                                   <span class="nav-text">{{ __('dashboard.visitors') }}</span>
                               </a>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link {{request('type') == 'contracts'? 'active' : ''}}" href="{{route('dashboard.permissionMeetings',['type'=>'contracts'])}}">
                               <span class="nav-icon">
                                   <i class="flaticon-list"></i>
                               </span>
                                   <span class="nav-text">{{ __('dashboard.contracts') }}</span>
                               </a>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link {{request('type') == 'materials'? 'active' : ''}}" href="{{route('dashboard.permissionMeetings',['type'=>'materials'])}}">
                                   <span class="nav-icon">
                                       <i class="flaticon2-box-1"></i>
                                   </span>
                                   <span class="nav-text">{{ __('dashboard.materials') }}</span>
                               </a>
                           </li>
                           <li class="nav-item">
                               <a class="nav-link {{request('type') == 'cars'? 'active' : ''}}" href="{{route('dashboard.permissionMeetings',['type'=>'cars'])}}">
                                   <span class="nav-icon">
                                       <i class="flaticon2-delivery-truck"></i>
                                   </span>
                                   <span class="nav-text">{{ __('dashboard.cars') }}</span>
                               </a>
                           </li>
                       </ul>
                   </div>

                   <div class="tab-content mt-5" id="myTabContent1">
                       <div class="tab-pane fade show active">
                           @if(request('type') == null || request('type') == "visitors")
                               @include('dashboard.permissions.visitors')
                           @elseif(request('type') == "materials")
                               @include('dashboard.permissions.materials')
                           @elseif(request('type') == "contracts")
                               @include('dashboard.permissions.contracts')
                           @elseif(request('type') == "cars")
                               @include('dashboard.permissions.cars')
                           @else
                               @include('dashboard.includes._no-data-found')
                           @endif
                       </div>
                   </div>
               </div>
           </div>

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

            <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            </div>

            <div class="modal fade" id="activitiesModal" tabindex="-1" role="dialog" aria-hidden="true">

            </div>
        </div>
    </div>
@endsection
