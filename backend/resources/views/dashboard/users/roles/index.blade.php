@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.roles')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.users')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.roles')</a>
        </li>

    </ul>
@endsection
@push('js')
 <script>
    //disable search in data table and paging
    if ($.fn.dataTable.isDataTable('.dataTable')) {
        table = $('.dataTable').DataTable();
    } else {
        table = $('.dataTable').DataTable({
            paging: false,
            searching: false,
        });
    }
    table = $('.dataTable').DataTable({
        retrieve: true,
        paging: false,
        'searching': false
    });

</script>
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">@lang('dashboard.roles_list')</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{url('roles/create')}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_role')</a>
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
                    <!--begin: Datatable-->
                    <table class="table table-bordered dataTable" id="">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.display_name')</th>
                            <th>@lang('dashboard.name')</th>
                            <th>@lang('dashboard.users_count')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.updated_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($roles->first()))
                            @foreach($roles as $index => $role)
                                <tr id="row-{{$role->id}}">
                                <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                                    <td>{{ $role->label?? '---'}}</td>
                                    <td>{{ $role->name?? '---'}}</td>
                                    <td>
                                        @if(count($role->users) > 0)
                                            <span class="badge badge-success" style="font-size: 12px">
                                                 {{$role->users()->count()}} @lang('dashboard.users')
                                            </span>
                                        @else
                                            <span class="badge badge-danger" style="font-size: 12px">
                                                @lang('dashboard.not_found_users')
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ dateFormat($role->created_at) ?? '---' }}</td>
                                    <td>{{ dateFormat($role->updated_at) ?? '---' }}</td>
                                    <td>
                                        <a href="{{route('dashboard.roles.edit', $role->id)}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" title="@lang('dashboard.edit')">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                            title="@lang('dashboard.delete')" data-toggle="modal" data-target="#delete_modal"
                                            data-url="{{ route('dashboard.roles.destroy',$role->id) }}"
                                            data-item-id="{{ $role->id }}">
                                            <i class="flaticon2-trash trash-icon" ></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {!! str_replace('/?', '?', $roles->withQueryString()->render()) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- begin: delete modal -->
    @include('dashboard.includes.alerts.delete-modal')
    <!-- end:: delete modal -->
@endsection

