@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.contract_request')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.requests')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="javascript:;" class="">@lang('dashboard.contract_request')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.create_contract_request')</a>
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

@push('js')
    <script>
        $('#kt_datetimepicker_1').datetimepicker();
        $('#kt_datetimepicker_2').datetimepicker();
    </script>
@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header">
                            <h3 class="card-title">@lang('dashboard.create_contract_request')</h3>
                            <div class="card-toolbar">
                                <a href="{{url('dashboard/contract-requests')}}"
                                   class="btn btn-primary ">
                                    <i class="flaticon2-reply-1" style="font-size: 1rem;"></i> @lang('dashboard.back')
                                </a>
                            </div>
                        </div>
                        <form novalidate="novalidate" id="visitForm" class="kt-form kt-form--label-right" method="post"
                              action="{{ route('dashboard.contract-requests.store') }}"
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

                                    <div class="col-4">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.site') }}</label>
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

                                    <div class=" col-4">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.company_name') }}</label>
                                            <span class="text-danger"> *</span>
                                            <select id="companyId" class="select2 form-control row" name="company_id">
                                                <option value="">@lang('dashboard.select_company')</option>
                                                @foreach($data['companies'] as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('company_id') ? $errors->first('company_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.contract_name') }}</label>
                                            <span class="text-danger"> *</span>
                                            <select id="selectContracts" class="select2 form-control row" name="contract_id">
                                                <option value="">@lang('dashboard.select_company_first')</option>
                                            </select>
                                            <div class="text-danger"
                                                 style="margin-right: 6px !important; margin-top: 11px; ">
                                                <strong>{{ $errors->has('contract_id') ? $errors->first('contract_id') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.notes') }}</label>
                                            <textarea type="text" class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}"
                                                      name="notes" cols="30" rows="7"
                                                      placeholder="@lang('dashboard.enter') @lang('dashboard.notes') ">{{old("notes") ?? ''}}</textarea>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('notes') ? $errors->first('notes') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.from_date') }}</label>
                                            <span class="text-danger"> *</span>
                                            <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                                <input type="text" name="from_date" value="{{old('from_date')}}" id="fromdate"
                                                       class="form-control datetimepicker-input" placeholder="{{ __('dashboard.Select_date_and_time') }}"
                                                       data-target="#kt_datetimepicker_2">
                                                <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
                                                  <span span class="input-group-text">
                                                      <i class="ki ki-calendar"></i>
                                                  </span>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('from_date') ? $errors->first('from_date') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group validated">
                                            <label>{{ __('dashboard.to_date') }}</label>
                                            <span class="text-danger"> *</span>
                                            <div class="input-group date" id="kt_datetimepicker_1" data-target-input="nearest">
                                                <input type="text" name="to_date" value="{{old('to_date')}}" id="todate"
                                                       class="form-control datetimepicker-input" placeholder="{{ __('dashboard.Select_date_and_time') }}" data-target="#kt_datetimepicker_1">
                                                <div class="input-group-append" data-target="#kt_datetimepicker_1" data-toggle="datetimepicker">
                                                  <span span class="input-group-text">
                                                      <i class="ki ki-calendar"></i>
                                                  </span>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('to_date') ? $errors->first('to_date') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12">
                                    <hr class="mb-6">
                                </div>

                                <div class="row mb-6">
                                    <div class="col-10">
                                        <h3>&nbsp; &nbsp; {{ __('dashboard.Contractors')}}
                                            <small>@lang('dashboard.s')</small></h3>
                                    </div>
                                    <div class="col-2">
                                        <a href="#" id="AddVisitor" class="btn btn-block btn-outline-primary" data-type="contract">
                                            <i class="flaticon-plus"></i> {{ __('dashboard.add_contractor') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-12">
                                        <table class="table">
                                            <thead id="visitors_head" class="thead-light" style="display: none;">
                                                <tr>
                                                    <th>{{ __('dashboard.contractor') }}</th>
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

    @include('dashboard.visits.requests._create_visitor',['visitor_type' => 'contractor'])

@endsection

@php
    $old_visitor = array_map(function ($item){return (int)$item;},old('contractors_id')??[]);
@endphp

@push('js')
    <script>
        var old_visitor = "@json($old_visitor)";
        var old_visitors = JSON.parse(old_visitor);
    </script>
    <script src="{{asset('dashboard_assets/custom/js/visitor.js')}}" type="text/javascript"></script>
    <script src="{{asset('dashboard_assets/custom/js/contractor.js')}}" type="text/javascript"></script>
@endpush
