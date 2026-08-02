@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url()->current()}}">@lang('dashboard.report')</a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.reports')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="javascript:;" class="text-muted">{{handleTrans($title)}}</a>
        </li>
    </ul>
@endsection
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-0 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{$title}}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.title')</th>
                            <th>@lang('dashboard.report_type')</th>
                            <th>@lang('dashboard.start_date')</th>
                            <th>@lang('dashboard.end_date')</th>
                            <th>@lang('dashboard.time_range')</th>
                            <th>@lang('dashboard.model_type')</th>
                            <th style="max-width: 300px">@lang('dashboard.sites')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.created_by')</th>
                            <th class="not-export-column">@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($drafts->first()))
                            @foreach($drafts as $index => $draft)
                                <tr id="row-{{$draft->id}}">
                                    <td>{{$index + 1}}</td>
                                    <td>{{ __("dashboard.$draft->title") ?? '---'}}</td>
                                    <td>{{ __("dashboard.$draft->report_list")}}</td>
                                    <td>{{ $draft->start ?? '---'}}</td>
                                    <td>{{ $draft->end ?? '---'}}</td>
                                    <td>{{ $draft->time_range ? handleRange($draft->time_range) : '---'}}</td>
                                    <td>{{ __("dashboard.$draft->model_type") ?? '---'}}</td>
                                    <td>
                                        @foreach(\App\Models\Site::whereIn('id',Arr::wrap($draft->site_id))->get() as $site)
                                            <span class="badge badge-success" style="background-color: var(--color2) !important;">
                                              {{__("dashboard.$site->name")}}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="number">{{ dateFormat($draft->created_at) ?? '---' }}</td>
                                    <td>{{ $draft->createdBy->full_name ?? '---' }}</td>
                                    <td class="dt_action_btn_cont px-1">
                                        <a href="{{url("dashboard/report/draft/$draft->id/draw")}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" target="_blank" title=" @lang('dashboard.show')">
                                            <i class="far fa-eye px-0"></i>
                                        </a>
                                        <a href="{{url("dashboard/report/draft/$draft->id/edit")}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" title=" @lang('dashboard.edit')">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        @if(!$draft->active && !$draft->default)
                                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                               data-toggle="modal" data-target="#delete_modal"
                                               data-reload="false"
                                               data-url="{{ route('draft.destroy',$draft->id) }}"
                                               data-item-id="{{ $draft->id }}" title="  @lang('dashboard.delete')">
                                                <i class="flaticon2-trash trash-icon px-0"></i>
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

    <!-- begin: delete modal -->
    @include('dashboard.includes.alerts.delete-modal')
    <!-- end:: delete modal -->
@endsection

@push('js')
    <script>
        $(function () {
            $(document).on('change', ".change_status", function (e) {
                let url = $(this).data("url");
                let status = 0;

                if ($(this).prop('checked') == true) {
                    status = 1;
                }
                let token = $('meta[name="csrf-token"]').attr("content");
                let inputs = `<input name="_token" value="${token}">`;
                inputs += `<input name="status" value=${status} >`;

                $(`<form action=${url} method="post">${inputs}</form>`).appendTo('body').submit().remove();
            });
        });
    </script>
@endpush
