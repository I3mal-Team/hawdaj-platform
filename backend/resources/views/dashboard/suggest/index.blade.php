@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.suggests')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.suggests')</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">@lang('dashboard.suggests_list')</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div style="width: 200px;display: inline-flex;margin-bottom: 30px;">
                        <span style="width: 50%;padding-top: 10px;">Show Entries</span>
                        <select class="form-control" id="show-enteries" style="display: inline-block;width:50%">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="-1">All</option>
                        </select>
                    </div>

                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('dashboard.title')</th>
                                <th>@lang('dashboard.description')</th>
                                <th>@lang('dashboard.type')</th>
                                <th>@lang('dashboard.name')</th>
                                <th>@lang('dashboard.email')</th>
                                <th>@lang('dashboard.active')</th>
                                <th>@lang('dashboard.created_at')</th>
                                <th>@lang('dashboard.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($suggests->first()))
                                @foreach ($suggests as $index => $suggest)
                                    <tr id="row-{{ $suggest->id }}">
                                    <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                                        <td>{{ $suggest->title ?? '---' }}</td>
                                        <td>{{ $suggest->description ? substr($suggest->description, 0, 50) : '---' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $suggest->type ?? 'place' }}
                                            </span>

                                        </td>
                                        <td>{{ $suggest->name ?? '---' }}</td>

                                        <td>{{ $suggest->email ?? '---' }}</td>

                                        <td class="text-center">
                                            @if ($suggest->active)
                                                <i class="fa fa-check-circle text-success"></i>
                                            @else
                                                <i class="fa fa-times-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="number">{{ dateFormat($suggest->created_at) ?? '---' }}</td>
                                        @if (!$suggest->active)
                                            <td>
                                                <a href="{{ route('dashboard.suggests.activate', [$suggest->id, 'active' => $suggest->active]) }}"
                                                    class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                    title="{{ __('dashboard.active') }}">
                                                    @if ($suggest->active)
                                                        <i class="fa fa-times-circle text-danger"></i>
                                                    @else
                                                        <i class="fa fa-check-circle text-success"></i>
                                                    @endif
                                                </a>
                                            </td>
                                        @endif

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
