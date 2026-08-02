@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.vendors')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.vendors')</a>
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
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">@lang('dashboard.vendors_list')</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{route('dashboard.vendors.create', ['type' => app('request')->input('type') ])}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_vendor')
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div style="width: 200px;display: inline-flex;margin-bottom: 30px;">
                        <span style="width: 50%;padding-top: 10px;">Show Entries</span>
                        <select class="form-control" id="show-enteries" style="display: inline-block;width:50%">
                            <option {{ Request::get('per_page') == '10' ? 'selected' : '' }} value="10">10</option>
                            <option {{ Request::get('per_page') == '25' ? 'selected' : '' }}  value="25">25</option>
                            <option {{ Request::get('per_page') == '50' ? 'selected' : '' }}  value="50">50</option>
                            <option {{ Request::get('per_page') == '-1' ? 'selected' : '' }}  value="-1">All</option>
                        </select>
                    </div>

                    <table class="table" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.photo')</th>
                            <th>@lang('dashboard.name')</th>
                            <th>@lang('dashboard.email')</th>
                            <th>@lang('dashboard.role')</th>
                            <th>@lang('dashboard.department')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($vendors->first()))
                            @foreach($vendors as $index => $vendor)
                                <tr id="row-{{$vendor->id}}">
                                <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                                    <td>
                                        <a style="width: 200px;" href="{{resolvePhoto($vendor->photo)}}" target="_blank">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-60 flex-shrink-0">
                                                    <div class="symbol-label"
                                                         style="background-image: url({{resolvePhoto($vendor->photo)}})">
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>{{ $vendor->full_name ?? '---'}}</td>
                                    <td>{{ $vendor->email ?? '---'}}</td>
                                    <td>
                                        @if($vendor->roles()->first())
                                            @foreach($vendor->roles()->get() as $role)
                                                <span class="badge badge-primary">
                                                  {{$role->label}}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="badge badge-danger">
                                                {{ trans('dashboard.no_role')}}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{$vendor->department->name=='All'?'danger':'success'}}">
                                            {{$vendor->department->name}}
                                        </span>
                                    </td>
                                    <td class="number">{{ dateFormat($vendor->created_at) ?? '---' }}</td>
                                    <td>
                                        <a href="{{route('dashboard.vendors.edit', $vendor->id)}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                           title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal"
                                           data-url="{{ route('dashboard.vendors.destroy',$vendor->id) }}"
                                           data-item-id="{{ $vendor->id }}">
                                            <i class="flaticon2-trash trash-icon"></i>
                                        </a>
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

    <!-- begin: delete modal -->
    @include('dashboard.includes.alerts.delete-modal')
    <!-- end:: delete modal -->
@endsection

