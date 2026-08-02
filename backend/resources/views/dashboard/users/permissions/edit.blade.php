@extends('layouts.dashboard.master')

@push('js')
    <script src="{{asset('dashboard_assets/js/pages/crud/forms/widgets/tagify.js')}}" type="text/javascript"></script>
    <script>
        $(function () {
            $("#add_operation").on('click', function () {
                let value = $("#new_operation").val();
                $("#new_operation").val('');
                let data =
                    `<label class="col-2 col-form-label">
                       ${ucfirst(value)}
                     </label>
                    <div class="col-4">
                        <span class="switch switch-outline switch-icon switch-success">
                            <label>
                                <input type="checkbox" checked="checked" name="operations[]" value="${value.toLowerCase()}"/>
                                <span></span>
                            </label>
                        </span>
                    </div>`;
                if (value == '') {
                    return false;
                }
                $('.operations').append(data);
            });
        });

        function ucfirst(str) {
            var lower = str.toLowerCase();
            var firstLetter = lower.substr(0, 1);
            return firstLetter.toUpperCase() + lower.substr(1);
        }
    </script>
@endpush

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.permissions')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">

        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.users')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="{{url('permissions')}}" class="text-muted">@lang('dashboard.permissions')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{ __('dashboard.'.$title)}}</a>
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
                            <h3 class="card-title">{{ __('dashboard.'.$title)}}</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                </div>
                            </div>
                        </div>
                        <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
                              action="{{ route('dashboard.permissions.update' , $model) }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.group_name')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                    </span>
                                                </div>
                                                <input type="text" readonly name="model" value="{{$model ?? ''}}"
                                                       class="form-control {{ $errors->has('model') ? 'is-invalid' : '' }}"
                                                       placeholder="@lang('dashboard.enter') @lang('dashboard.group_name') "
                                                       aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('model') ? $errors->first('model') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.new_operation')</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="new_operation"
                                                       placeholder="@lang('dashboard.new_operation')..."/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-success" id="add_operation" type="button">
                                                        + @lang('dashboard.add_operation')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.operations')</label>
                                            <span class="text-danger"> * </span>
                                            <div class="form-group operations row m-2">
                                                @foreach($operations as $operation)
                                                    <label class="col-2 col-form-label">
                                                        @php($permission = explode(' ',lcfirst($operation->label))[0])
                                                        {{ucfirst(__('dashboard.'.$permission))}}
                                                    </label>
                                                    <div class="col-4">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" checked="checked"
                                                                       value="{{explode(' ',lcfirst($operation->label))[0]}}"
                                                                       name="operations[]"/>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="kt-form__actions">
                                    <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                                    <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
