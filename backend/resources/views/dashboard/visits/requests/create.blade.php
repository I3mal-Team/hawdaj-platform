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
            <a href="javascript:;" class="text-muted">@lang('dashboard.create_visit')</a>
        </li>
    </ul>
@endsection

@push('css')
    <link href="{{asset('dashboard_assets/css/pages/wizard/wizard-6.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .invalid-feedback {
            padding-right: 4px
        }
    </style>
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
                              action="{{ route('dashboard.visits.store') }}"
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

                                    <div class=" col-6">
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
                                                        {{auth()->user()->department->id == $department->id ? 'selected':''}}>{{ handleTrans($department->name) }}</option>
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
                                                    <option
                                                        {{old('reason_id') == $reason->id ?'selected':'' }} value="{{ $reason->id }}">{{ $reason->reason }}</option>
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
                                                    <option
                                                        {{old('visit_type_id') == $visitType->id ? 'selected':'' }} value="{{ $visitType->id }}">{{ $visitType->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('visit_type_id') ? $errors->first('visit_type_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.purpose_description') }}</label>
                                            <input type="text" name="description"
                                                   class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                                   placeholder="{{ __('dashboard.purpose_description') }}"
                                                   value="{{old('description')}}"/>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.visit_requirements') }}</label>
                                            <span class="text-danger"> *</span>
                                            <input type="text" name="requirments" class="form-control
                                                {{ $errors->has('requirments') ? 'is-invalid' : '' }}"
                                                   value="{{old('requirments')}}"
                                                   placeholder="{{ __('dashboard.visit_requirements') }}"/>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('requirments') ? $errors->first('requirments') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="mb-6">
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group validated">
                                            <label class="checkbox">
                                                <input type="checkbox"
                                                       name="recurring_visit" {{old('recurring_visit ')?'checked':''}}/>
                                                <span></span>
                                                <b class="ml-2">{{ __('dashboard.recurring_visit') }}</b>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.visit_from_schedule') }}</label>
                                            <span class="text-danger"> *</span>
                                            <input type="date"
                                                   class="form-control {{ $errors->has('from_date') ? 'is-invalid' : '' }}"
                                                   name="from_date"
                                                   placeholder="{{ __('dashboard.visit_from_schedule') }}"
                                                   value="{{old('from_date')}}"
                                                   min="<?= date('Y-m-d') ?>"
                                            >
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('from_date') ? $errors->first('from_date') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.from') }}</label>
                                            <span class="text-danger"> *</span>
                                            <input name="from_fromtime" type="time" value="{{old('from_fromtime')}}"
                                                   class="form-control {{ $errors->has('from_fromtime') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('from_fromtime') ? $errors->first('from_fromtime') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.to') }}</label>
                                            <span class="text-danger"> *</span>
                                            <input type="time" name="from_totime" value="{{old('from_totime')}}"
                                                   class="form-control {{ $errors->has('from_totime') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('from_totime') ? $errors->first('from_totime') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.visit_to_schedule') }}</label>
                                            <span class="text-danger"> *</span>
                                            <input type="date"
                                                   class="form-control {{ $errors->has('to_date') ? 'is-invalid' : '' }}"
                                                   value="{{old('to_date')}}" name="to_date"
                                                   placeholder="{{ __('dashboard.visit_to_schedule') }}"
                                                   min="<?= date('Y-m-d') ?>"
                                            >
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('to_date') ? $errors->first('to_date') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.from') }}</label>
                                            <span class="text-danger"> *</span>
                                            <input name="to_fromtime" type="time" value="{{old('to_fromtime')}}"
                                                   class="form-control {{ $errors->has('to_fromtime') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('to_fromtime') ? $errors->first('to_fromtime') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.to') }}</label>
                                            <span class="text-danger"> *</span>
                                            <input type="time" name="to_totime" value="{{old('to_totime')}}"
                                                   class="form-control {{ $errors->has('to_totime') ? 'is-invalid' : '' }}">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('to_totime') ? $errors->first('to_totime') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="mb-6">
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.commit_for_reception') }}</label>
                                            <textarea name="comment" cols="30" rows="4"
                                                      class="form-control {{ $errors->has('comment') ? 'is-invalid' : '' }}"
                                                      placeholder="{{ __('dashboard.commit_for_reception') }}">{{old('comment')}}</textarea>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('comment') ? $errors->first('comment') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="mb-6">
                                </div>

                                <div class="row mb-6">
                                    <div class="col-10">
                                        <h3>&nbsp; &nbsp; {{ __('dashboard.visitor')}}
                                            <small>@lang('dashboard.s')</small></h3>
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
                                <button type="button" class="btn btn-primary" id="submitVisitForm">@lang('dashboard.submit')</button>
                                <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.visits.requests._create_visitor')

@endsection

@php
    $old_visitor = array_map(function ($item){return (int)$item;},old('visitors_id')??[]);
@endphp

@push('js')
    <script>
        var old_visitor = "@json($old_visitor)";
        var old_visitors = JSON.parse(old_visitor);
    </script>
    <script src="{{asset('dashboard_assets/custom/js/visitor.js')}}" type="text/javascript"></script>
@endpush
