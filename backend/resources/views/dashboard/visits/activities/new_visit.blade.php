@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.requests')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@push('css')
    <link href="{{asset('dashboard_assets/css/pages/wizard/wizard-6.css')}}" rel="stylesheet" type="text/css"/>
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header">
                            <h3 class="card-title">@lang('dashboard.create_visit')</h3>
                            <div class="card-toolbar">
                                <a href="{{url('dashboard/visits')}}"
                                   class="btn btn-primary ">
                                    <i class="flaticon2-reply-1" style="font-size: 1rem;"></i> @lang('dashboard.back')
                                </a>
                            </div>
                        </div>
                        <form novalidate="novalidate" id="visitForm" class="kt-form kt-form--label-right" method="post"
                              action="{{ route('dashboard.visits-activities.storeVisit') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group validated">
                                            <label class="checkbox">
                                                <input type="checkbox" value="1" id="current_site" name="current_site"/>
                                                <span></span>
                                                <b class="ml-2">{{ __('dashboard.current_site') }}</b>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.meeting_location') }}</label>
                                            <span class="text-danger"> *</span>
                                            <select class="nice-select form-control row" name="site_id" id="site_id">
                                                <option value="">@lang('dashboard.select_site')</option>
                                                @foreach($data['sites'] as $site)
                                                    <option value="{{ $site->id }}"
                                                        {{session('site_id') == $site->id ? 'selected':''}}>{{ handleTrans($site->name) }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('site_id') ? $errors->first('site_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6"></div>

                                    <div class="col-6">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.search_host') }}</label>
                                            <span class="text-danger"> *</span>
                                            <select class="select2 form-control row" name="host_id">
                                                <option value="">@lang('dashboard.select_host')</option>
                                                @foreach($data['users'] as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{auth()->id() == $user->id ? 'selected':''}}>{{ $user->full_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('host_id') ? $errors->first('host_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.department') }}</label>
                                            <span class="text-danger"> *</span>
                                            <select name="department_id" class="nice-select form-control">
                                                <option value="">@lang('dashboard.select_department')</option>
                                                @foreach($data['departments'] as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{auth()->user()->department->id == $department->id ? 'selected':''}}>{{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('department_id') ? $errors->first('department_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.visit_reason') }}</label>
                                            <span class="text-danger"> *</span>
                                            <select name="reason_id" class="nice-select form-control">
                                                <option value="">@lang('dashboard.select_reason')</option>
                                                @foreach($data['reasons'] as $reason)
                                                    <option {{old('reason_id') == $reason->id ?'selected':'' }} value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('reason_id') ? $errors->first('reason_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.visitor_type') }}</label>
                                            <span class="text-danger"> *</span>
                                            <select name="visit_type_id" class="nice-select form-control">
                                                <option value="">{{ __('dashboard.select_visit_type') }}</option>
                                                @foreach($data['requests-type'] as $visitType)
                                                    <option {{old('visit_type_id') == $visitType->id ? 'selected':'' }} value="{{ $visitType->id }}">{{ $visitType->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('visit_type_id') ? $errors->first('visit_type_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12">
                                    <hr class="mb-6">
                                </div>

                                <div class="row mb-6">
                                    <div class="col-10">
                                        <h3>&nbsp; &nbsp; {{ __('dashboard.visitor')}}<small>(s)</small></h3>
                                    </div>
                                    <div class="col-2">
                                        <a href="#" id="AddVisitor" class="btn btn-block btn-outline-primary">
                                            <i class="flaticon-plus"></i> {{ __('dashboard.add_visitor') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-12">
                                        <table class="table">
                                            <thead id="visitors_head" class="thead-light" style="display: none;">
                                            <tr>
                                                <th>{{ __('dashboard.visitor') }}</th>
                                                <th>{{ __('dashboard.company') }}</th>
                                                <th>{{ __('dashboard.id_number') }}</th>
                                                <th>{{ __('dashboard.email') }}</th>
                                                <th>{{ __('dashboard.nationally') }}</th>
                                                <th>{{ __('dashboard.status') }}</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody id="visitorSelectedTable">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <select name="visitors_id[]" id="visitors_id" multiple hidden>
                            </select>
                            <div class="card-footer">
                                <button type="button" class="btn btn-success"
                                        id="submitVisitForm">@lang('dashboard.checkin')</button>
                                <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.visits.activities.extra._create_visitor')

@endsection

@php
    $old_visitor = array_map(function ($item){
        return (int)$item;
    },old('visitors_id')??[]);
@endphp

@push('js')
    <script src="{{asset('dashboard_assets/custom/js/activities.js')}}" type="text/javascript"></script>
    <script src="{{asset('dashboard_assets/custom/js/visit-activites.js')}}" type="text/javascript"></script>

    <script>
        var old_visitor = "@json($old_visitor)";
        var old_visitors = JSON.parse(old_visitor);

        $("#current_site").on('change', function (e) {
            if ($('input[name="current_site"]').is(':checked')) {
                $("#site_id").prop('disabled', true);
            } else {
                $("#site_id").prop('disabled', false);
            }
        });

        if (old_visitors.length >= 1) {
            old_visitors.forEach(item => {
                $("#visitors_id").append(
                    `<option value=${parseInt(item)} selected></option>`
                );
            });

            $.get({
                url: `${HOST_URL}/${LANG}/dashboard/visitors-by-id`,
                data: {ids: old_visitors},
                method: 'GET',
                success: function (visitors) {
                    if (visitors.length >= 1) {
                        $("#visitors_head").show();
                        $("#visitorSelectedTable").empty();
                        visitors.forEach(visitor => {
                            $("#visitorSelectedTable").append(
                                `<tr id="row-${visitor.id}">
                                <td>${visitor.full_name}</td>
                                <td>${visitor.company ?? '---'}</td>
                                <td>${visitor.id_number ?? '---'}</td>
                                <td>${visitor.email ?? '---'}</td>
                                <td>${visitor.nationality ?? '---'}</td>
                                <td><b style="color:green;">Approved</b></td>
                                <td style="cursor: pointer;" class="delete_visitor" data-visitor_id="${visitor.id}">
                                    <i class="fas fa-times-circle"></i>
                                </td>
                            </tr>`
                            );

                            $("#selectUsers").append(
                                "<option value='" + visitor.id + "' selected>" + visitor.full_name + "</option>"
                            );

                        });
                    }
                }
            });
        }
    </script>
@endpush
