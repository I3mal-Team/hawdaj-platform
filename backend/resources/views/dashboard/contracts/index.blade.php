@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.contracts')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.settings')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.data_entry')</a>
        </li>

        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">@lang('dashboard.contracts_list')</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{route('dashboard.contracts.create')}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_contract')</a>
                    </div>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.contract_id')</th>
                            <th>@lang('dashboard.name')</th>
                            <th>@lang('dashboard.contract_manager')</th>
                            <th>@lang('dashboard.supervisor')</th>
                            <th>@lang('dashboard.from_date')</th>
                            <th>@lang('dashboard.to_date')</th>
                            <th>@lang('dashboard.status')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.updated_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($contracts->first()))
                            @foreach($contracts as $index => $contract)
                                <tr id="row-{{$contract->id}}">
                                <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                                    <td>{{ $contract->id }}</td>
                                    <td>{{ $contract->name?? '---'}}</td>
                                    <td>{{ $contract->contract_manager->first_name}} {{ $contract->contract_manager->last_name}}</td>
                                    <td>{{ $contract->supervisor->first_name}} {{ $contract->supervisor->last_name}}</td>
                                    <td>{{ $contract->from_date?? '---'}}</td>
                                    <td>{{ $contract->to_date?? '---'}}</td>
                                    <td>
                                        @if((date('Y-m-d') >= $contract->from_date) && (date('Y-m-d')<= $contract->to_date))
                                            <span class="badge badge-success">
                                              @lang('dashboard.active')
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                @lang('dashboard.not_active')
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ dateFormat($contract->created_at) ?? '---' }}</td>
                                    <td>{{ dateFormat($contract->updated_at) ?? '---' }}</td>
                                    <td>
                                        <a href="{{route('dashboard.contracts.edit', $contract->id)}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                           title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal"
                                           data-url="{{ route('dashboard.contracts.destroy',$contract->id) }}"
                                           data-item-id="{{ $contract->id }}">
                                            <i class="flaticon2-trash trash-icon" ></i>
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

