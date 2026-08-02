@extends('layouts.dashboard.master')
@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.menu_templates')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
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
                        <h3 class="card-label">@lang('dashboard.templates_list')</h3>
                    </div>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('dashboard.mail_type') }}</th>
                            <th>{{ __('dashboard.mail_title') }}</th>
                            <th>{{ __('dashboard.mail_subject') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($templates as $key => $template)
                            <tr>
                                <td>{{($key + 1) + ((request('page' , 1)-1) * 10) }}</td>
                                <td> {{ $template->type ?? '' }}</td>
                                <td> {{ $template->title ?? '' }}</td>
                                <td> {{ $template->subject ?? '' }}</td>
                                <td>
                                    <a href="{{route('dashboard.mail_template.edit', $template->id)}}"
                                       class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{ __('dashboard.edit') }}">
                                        <i class="flaticon-edit-1 edit-icon"></i>
                                    </a>
{{--                                    <a href="{{route('dashboard.mail_template.show', $template->id)}}"--}}
{{--                                       class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.show')}}">--}}
{{--                                        <i class="flaticon-eye eye-icon"></i>--}}
{{--                                    </a>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
