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
                            <th>@lang('dashboard.start_date')</th>
                            <th>@lang('dashboard.end_date')</th>
                            <th>@lang('dashboard.time_range')</th>
                            <th>@lang('dashboard.date')</th>
                            <th>@lang('dashboard.charts')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.created_by')</th>
                            <th class="not-export-column">@lang('dashboard.in_dashboard')</th>
                            <th class="not-export-column">@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($pinneds->first()))
                            @foreach($pinneds as $index => $pinned)
                                <tr id="row-{{$pinned->id}}">
                                    <td>{{$index + 1}}</td>
                                    <td>{{ __("dashboard.".$pinned->title) ?? __('dashboard.mixed')}}</td>
                                    <td>
                                        @if($pinned->start)
                                            {{$pinned->start}}
                                        @elseif($pinned->global_date && $pinned->time_type == 'dynamic')
                                            @php
                                              $date = getStartEndDate($pinned->time_range)
                                            @endphp
                                            {{$date['start']}}
                                        @else
                                            {{__('dashboard.mixed')}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($pinned->end)
                                            {{$pinned->end}}
                                        @elseif($pinned->global_date && $pinned->time_type == 'dynamic')
                                            @php
                                                $date = getStartEndDate($pinned->time_range)
                                            @endphp
                                            {{$date['end']}}
                                        @else
                                            {{__('dashboard.mixed')}}
                                        @endif
                                    </td>
                                    <td>{{ $pinned->time_range ? handleRange($pinned->time_range) : '---'}}</td>
                                    <td>
                                        @if($pinned->global_date)
                                            <span class="badge badge-primary">
                                                @lang('dashboard.global_date')
                                            </span>
                                        @else
                                            <span class="badge badge-success"
                                                  style="background-color: var(--color2) !important;">
                                                @lang('dashboard.mixed')
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-success"
                                              style="background-color: var(--color2) !important;">
                                            {{ $pinned->charts_count.' '.__('dashboard.charts')}}
                                        </span>
                                    </td>
                                    <td class="number">{{ dateFormat($pinned->created_at) ?? '---' }}</td>
                                    <td>{{ $pinned->createdBy->full_name ?? '---' }}</td>
                                    <td class="text-center">
                                        <span class="switch switch-outline switch-icon switch-success">
                                            <label>
                                                <input type="checkbox"
                                                       data-url="{{ route('pinned.status',$pinned->id) }}"
                                                       data-method="get" name="status"
                                                       data-id="{{$pinned->id}}"
                                                       {{$pinned->active ? 'checked disabled' : ''}}
                                                       class="change_status">
                                                <span></span>
                                            </label>
                                        </span>
                                    </td>
                                    <td class="dt_action_btn_cont px-1">
{{--                                        <a data-id="{{$pinned->id}}"--}}
{{--                                           class="btn btn-sm btn-outline-success reload_report mb-1 btn_status">--}}
{{--                                            <i class="fa fa-sync-alt"></i>--}}
{{--                                        </a>--}}
                                        <a href="{{url("dashboard/report/pinned/$pinned->id/draw")}}"
                                          title=" @lang('dashboard.show_report')" class="btn btn-sm btn-clean btn-icon btn-icon-md" target="_blank">
                                            <i class="far fa-eye px-0"></i>
                                        </a>
                                        <a href="{{url("dashboard/report/pinned/$pinned->id/edit")}}"
                                           title=' @lang('dashboard.edit') 'class="btn btn-sm btn-clean btn-icon btn-icon-md">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        @if(!$pinned->active && !$pinned->default)
                                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                               data-toggle="modal" data-target="#delete_modal"
                                               data-reload="true"
                                               data-url="{{ route('pinned.destroy',$pinned->id) }}"
                                               data-item-id="{{ $pinned->id }}"
                                                title="@lang('dashboard.delete')"
                                            >
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

            $(document).on('click', ".reload_report", function (e) {
                let id = $(this).data("id");
                $.get(`${HOST_URL}/${LANG}/dashboard/report/pinned/${id}/reload`, function (data, textStatus, jqXHR) {
                    toastr.success(data.message);
                });
            });

        });
    </script>
@endpush
