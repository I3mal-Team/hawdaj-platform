@extends('layouts.dashboard.master')

@push('css')
    <link href="{{asset('dashboard_assets/plugins/custom/jstree/jstree.bundle.css')}}" rel="stylesheet"/>
@endpush

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url('dashboard/report/draft')}}">@lang('dashboard.drafted')</a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.reports')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="{{url('dashboard/report/draft')}}" class="text-muted">@lang('dashboard.drafted')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{__("dashboard.edit_drafted_report")}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header">
                            <h3 class="card-title">{{__("dashboard.".$title)}}</h3>
                            <div class="card-toolbar">
                                <a href="{{url('dashboard/report/builder')}}"
                                   class="btn btn-primary ">
                                    <i class="flaticon2-reply-1"></i>
                                    @lang('dashboard.back')
                                </a>
                            </div>
                        </div>

                        <!--begin::Form-->
                        <form id="form" novalidate="novalidate" class="form form-label-right" method="post"
                              action="{{route('draft.update',$draft->id)}}">
                            @method('PUT')
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group validated">
                                            <label>{{trans('dashboard.draft_title')}} </label>
                                            <span class="text-danger"> *</span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                      <i class="fas fa-pen"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="title"
                                                       class="form-control {{ inputError('title') }}"
                                                       value="{{__("dashboard.".$draft->title)??''}}"
                                                       placeholder="{{trans('dashboard.enter').' '.trans('dashboard.draft_title')}}  "
                                                       required/>
                                                <div class="invalid-feedback">
                                                    <strong>{{ msgError('title') }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.report_type') </label>
                                            <span class="text-danger"> *</span>
                                            <select required name="report_list" id="report_list"
                                                    class="form-control nice-select {{ inputError('report_list')}}">
                                                <option value="">@lang('dashboard.select_type')</option>
                                                @foreach(array_keys(config("$draft->model_type.report")) as $report_type)
                                                    <option value="{{$report_type}}"
                                                        {{$draft->report_list == $report_type ? 'selected' : ''}}>
                                                        {{__("dashboard.$report_type")}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ msgError('report_list')}}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.time_type') </label>
                                            <span class="text-danger"> *</span>
                                            <select id="time_type"
                                                    class="form-control nice-select {{ inputError('time_type')}}"
                                                    name="time_type" required>
                                                <option value="">@lang('dashboard.select_time_type')</option>
                                                <option
                                                    value="dynamic" {{$draft->time_type == 'dynamic' ? 'selected' : ''}}>
                                                    {{__('dashboard.dynamic')}}
                                                </option>
                                                <option
                                                    value="specific" {{$draft->time_type == 'specific' ? 'selected' : ''}}>
                                                    {{__('dashboard.specific')}}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ msgError('time_type')}}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="time_range" class="col-md-4"
                                         style="display: {{$draft->time_type == 'dynamic' ? 'block' :'none'}}">
                                        <div class="form-group input-group input-daterange ">
                                            <label>@lang('dashboard.select_date') </label>
                                            <span class="text-danger"> *</span>
                                            <select name="time_range" class="form-control nice-select">
                                                <option
                                                    @if($draft->time_range == 'today'||$draft->time_range  == null)
                                                    selected @endif value="today">
                                                    @lang('dashboard.today')
                                                </option>
                                                <option value="14" @if($draft->time_range ==14) selected @endif>
                                                    {{trans('dashboard.this_week')}}
                                                </option>
                                                <option value="13" @if($draft->time_range ==13) selected @endif>
                                                    {{trans('dashboard.last_week')}}
                                                </option>
                                                <option value="16" @if($draft->time_range ==16) selected @endif>
                                                    {{trans('dashboard.this_month')}}
                                                </option>
                                                <option value="15" @if($draft->time_range ==15) selected @endif>
                                                    {{trans('dashboard.last_month')}}
                                                </option>
                                                @for($i = 0; $i < date('m'); $i++)
                                                    <option value="{{date('m', strtotime("-$i month"))}}"
                                                            @if($draft->time_range == date('m', strtotime("-$i month")))
                                                            selected @endif >
                                                        <?php echo date('Y-m', strtotime("-$i month")); ?>
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4" id="select_container" style="display: none">
                                    </div>

                                    <div id="specific_time" class="col-md-8"
                                         style="display: {{$draft->time_type == 'specific' ? 'block' :'none'}}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group validated">
                                                    <label>@lang('dashboard.from_date')</label>
                                                    <span class="text-danger"> *</span>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="la la-exclamation-triangle flaticon-event-calendar-symbol"></i>
                                                        </span>
                                                        </div>
                                                        <input type="date" required name="start"
                                                               max="{{carbon()->now()->toDateString()}}"
                                                               value="{{$draft->start}}"
                                                               class="form-control {{ inputError('start')}}"
                                                               placeholder="@lang('dashboard.enter') @lang('dashboard.from_date')"
                                                               aria-describedby="basic-addon1"
                                                        />
                                                        <div class="invalid-feedback">
                                                            <strong>{{ msgError('start')}}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group validated">
                                                    <label>@lang('dashboard.end_date')</label>
                                                    <span class="text-danger"> *</span>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="la la-exclamation-triangle flaticon-event-calendar-symbol"></i>
                                                            </span>
                                                        </div>
                                                        <input type="date" name="end" value="{{$draft->end}}" required
                                                               max="{{carbon()->now()->toDateString()}}"
                                                               class="form-control {{ inputError('end')}}"
                                                               placeholder="@lang('dashboard.enter') @lang('dashboard.end_date')">
                                                        <div class="invalid-feedback">
                                                            <strong>{{ msgError('end')}}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="form-actions">
                                    <button type="button" class="btn btn-primary close-setting add_loading">
                                        @lang('dashboard.update_draft')
                                    </button>
                                    <input type="reset" class="btn btn-secondary" value="@lang('dashboard.cancel')"
                                    />
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function () {
            $("#time_type").on('change', function (e) {
                let type = $(this).val();
                if (type === 'dynamic') {
                    $("#specific_time").fadeOut('fast');
                    $("#time_range").fadeIn('fast');
                    $("#specific_time input").val('');
                } else if (type === 'specific') {
                    $("#specific_time").fadeIn('fast');
                    $("#time_range select").val('');
                    $("#time_range").fadeOut('fast');
                } else {
                    $("#specific_time").fadeOut('fast');
                    $("#time_range").fadeOut('fast')
                    $("#specific_time input").val('');
                    $("#time_range select").val('');
                }
            });

            if ("{{$errors->first()}}" !== "") {
                toastr.error("{{$errors->first()}}");
            }
        });
    </script>
@endpush
