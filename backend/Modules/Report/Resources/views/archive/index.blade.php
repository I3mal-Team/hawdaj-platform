@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url("dashboard/".shortModelName($model_type)."/".request('site_id'))}}">
            {{__("dashboard.".shortModelName($model_type))}}
        </a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.reports')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{url('dashboard/report/builder')}}" class="text-muted">@lang('dashboard.builder')</a>
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
                        <h3 class="card-label">{{$title}}</h3>
                    </div>
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs" id="archive-tab" role="tablist">
                            <li class="nav-item" data-toggle="tooltip" title="@lang('dashboard.cards_view')">
                                <a class="nav-link " id="cards-tab" data-toggle="tab" href="#cards" role="tab"
                                   aria-controls="home" aria-selected="true">
                                    <i class="fas fa-th active"></i>
                                </a>
                            </li>
                            <li class="nav-item " data-toggle="tooltip" title="@lang('dashboard.table_view')">
                                <a class="nav-link active" id="table-tab" data-toggle="tab" href="#table" role="tab"
                                   aria-controls="profile" aria-selected="false">
                                    <i class="fas fa-table active"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="cards" role="tabpanel" aria-labelledby="cards-tab">
                            @if(count($files)>0)
                                <div class="row">
                                    @foreach($files as $file)
                                        <div class="col-3">
                                            <div class="archive-card card h-100">
                                                <div class="card-header p-3">
                                                    <b>@lang('dashboard.file_name')</b>
                                                    <p>{{$file->name }}</p>
                                                </div>
                                                <div class="card-body py-2 px-4">
                                                    <div class="file-img">
                                                        <img src="{{asset('dashboard_assets/media/excel.svg')}}" alt="">
                                                    </div>

                                                    <div class="text-center mb-3">
                                                        <b>@lang('dashboard.site_name')</b>
                                                        <p>{{$file->site->name}}</p>
                                                    </div>
                                                    <div class="info ">

                                                        <div class="data">
                                                            <b>{{ trans('dashboard.start_date') }}</b>
                                                            <p>{{$file->start}}</p>
                                                        </div>
                                                        <div class="data">
                                                            <b>{{ trans('dashboard.end_date') }}</b>
                                                            <p>{{$file->end}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer p-2">
                                                    @if($file->status)
                                                        <a href="{{$file->path_url}}"
                                                           class="btn btn-text-dark-50 btn-icon-primary font-weight-bold btn-download mr-3">
                                                            <i class="fas fa-download"></i>
                                                            @lang('dashboard.download')
                                                        </a>
                                                    @endif
                                                    <button
                                                        class="btn btn-text-dark-50 btn-icon-primary font-weight-bold btn-delete mr-3">
                                                        <a class="btn btn-outline-danger btn-sm delete-button"
                                                           title="delete" data-toggle="modal"
                                                           data-target="#delete_modal"
                                                           data-url="{{ route('dashboard.files.destroy',$file->id) }}"
                                                           data-item-id="{{ $file->id }}">
                                                            <i class="fas fa-trash text-danger"></i>{{__('dashboard.delete')}}
                                                        </a>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="nodata-img-cont">
                                    <img
                                        src="{{ asset('dashboard_assets/media/no-data.png') }}"
                                    />
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane show active" id="table" role="tabpanel" aria-labelledby="table-tab">
                            <table class="table" id="dataTable">
                                <thead>
                                <tr>
                                    <th width="10">#</th>
                                    <th>{{ trans('dashboard.type') }}</th>
                                    <th>{{ trans('dashboard.start_date') }}</th>
                                    <th>{{ trans('dashboard.end_date') }}</th>
                                    <th>{{ trans('dashboard.model_type') }}</th>
                                    <th>{{ trans('dashboard.site_name') }}</th>
                                    <th>{{ trans('dashboard.file_name') }}</th>
                                    <th>{{ trans('dashboard.status') }}</th>
                                    <th>{{ trans('dashboard.created_at') }}</th>
                                    <th class="not-export-column">{{ trans('dashboard.action') }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($files as $index => $file)
                                    <tr id='row-{{$file->id}}'>
                                        <td>{{++$index}}</td>
                                        <td style='padding-top: -5px !important;'>
                                            @if($file->type === 'pdf')
                                                <img src="{{asset("dashboard_assets/media/pdf.svg")}}"
                                                     style="height: 35px !important;" alt='{{$file->type}}'
                                                />
                                            @elseif($file->type === 'xls')
                                                <img src="{{asset("dashboard_assets/media/xls.svg")}}"
                                                     style="height: 45px !important;" alt='{{$file->type}}'
                                                />
                                            @else
                                                <img src="{{asset("dashboard_assets/media/xls.svg")}}"
                                                     style="height: 45px !important;" alt='{{$file->type}}'
                                                />
                                            @endif
                                        </td>
                                        <td>{{$file->start}}</td>
                                        <td>{{$file->end}}</td>
                                        <td>{{ucfirst($file->model_type)}}</td>
                                        <td>{{$file->site->name}}</td>
                                        <td>
                                            @if($file->path_url)
                                                <a href="{{$file->path_url}}">{{$file->name }}</a>
                                            @else
                                                {{$file->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($file->status)
                                                <span class='badge badge-success'>{{__('dashboard.ready')}}</span>
                                            @else
                                                <span class='badge badge-danger'>{{__('dashboard.not_ready')}}</span>
                                            @endif
                                        </td>
                                        <td>{{dateFormat($file->created_at)}}</td>
                                        <td class="dt_action_btn_cont px-1">
                                            @if($file->path_url)
                                                <a href="{{$file->path_url}}"
                                                   class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                   title="{{__('dashboard.download')}}">
                                                    <i class="flaticon2-download"></i>
                                                </a>
                                            @endif
                                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md "
                                               title="{{__('dashboard.delete')}}" data-toggle="modal"
                                               data-target="#delete_modal"
                                               data-url="{{ route('dashboard.files.destroy',$file->id) }}"
                                               data-item-id="{{ $file->id }}">
                                                <i class="flaticon2-trash trash-icon px-0"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->

    <!-- begin: delete modal -->
    @include('dashboard.includes.alerts.delete-modal')
    <!-- end:: delete modal -->
@endsection
