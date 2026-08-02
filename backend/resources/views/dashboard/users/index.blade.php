@extends('layouts.dashboard.master')

@section('page_header')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.users')</a>
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

    // Handle show entries dropdown
    $('#show-enteries').on('change', function() {
        var perPage = $(this).val();
        var url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        window.location.href = url.toString();
    });

</script>
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">@lang('dashboard.users_list')</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{route('dashboard.users.create', ['type' => app('request')->input('type') ])}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_user')
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('dashboard.users.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('dashboard.search_by_email')</label>
                                    <input type="text" name="email" class="form-control" 
                                           placeholder="@lang('dashboard.enter_email')" 
                                           value="{{ request('email') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('dashboard.search_by_role')</label>
                                    <select name="role_id" class="form-control select2">
                                        <option value="">@lang('dashboard.all_roles')</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" 
                                                    {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="la la-search"></i> @lang('dashboard.search')
                                        </button>
                                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary">
                                            <i class="la la-refresh"></i> @lang('dashboard.reset')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Hidden field to maintain per_page value -->
                        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    </form>

                    <div style="width: 200px;display: inline-flex;margin-bottom: 30px;">
                        <span style="width: 50%;padding-top: 10px;">Show Entries</span>
                        <select class="form-control" id="show-enteries" style="display: inline-block;width:50%">
                            <option {{ Request::get('per_page') == '10' ? 'selected' : '' }} value="10">10</option>
                            <option {{ Request::get('per_page') == '25' ? 'selected' : '' }}  value="25">25</option>
                            <option {{ Request::get('per_page') == '50' ? 'selected' : '' }}  value="50">50</option>
                            <option {{ Request::get('per_page') == '-1' ? 'selected' : '' }}  value="-1">All</option>
                        </select>
                    </div>

                    <table class="table dataTable">
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
                        @if(!empty($users->first()))
                            @foreach($users as $index => $user)
                                <tr id="row-{{$user->id}}">
                                <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                                    <td>
                                        <a style="width: 200px;" href="{{resolvePhoto($user->photo)}}" target="_blank">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-60 flex-shrink-0">
                                                    <div class="symbol-label"
                                                         style="background-image: url({{resolvePhoto($user->photo)}})">
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>{{ $user->full_name ?? '---'}}</td>
                                    <td>{{ $user->email ?? '---'}}</td>
                                    <td>
                                        @if($user->roles()->first())
                                            @foreach($user->roles()->get() as $role)
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
                                        <span class="badge badge-{{$user->department->name=='All'?'danger':'success'}}">
                                            {{$user->department->name}}
                                        </span>
                                    </td>
                                    <td class="number">{{ dateFormat($user->created_at) ?? '---' }}</td>
                                    <td>
                                        <a href="{{route('dashboard.users.edit', $user->id)}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                           title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal"
                                           data-url="{{ route('dashboard.users.destroy',$user->id) }}"
                                           data-item-id="{{ $user->id }}">
                                            <i class="flaticon2-trash trash-icon"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {!! str_replace('/?', '?', $users->withQueryString()->render()) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- begin: delete modal -->
    @include('dashboard.includes.alerts.delete-modal')
    <!-- end:: delete modal -->
@endsection

