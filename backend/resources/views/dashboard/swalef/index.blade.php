@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.swalefs')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.swalefs')</a>
    </li>

</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">@lang('dashboard.swalefs_list')</h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{route('dashboard.swalefs.create')}}" class="btn btn-primary">
                        <i class="la la-plus"></i>@lang('dashboard.new_swalef')
                    </a>
                </div>
            </div>
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <form action="{{ route('dashboard.swalefs.index') }}" method="get" style="width: -webkit-fill-available;">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="user_places" value="1" id="user_places_filter" 
                                       {{ request()->user_places ? 'checked' : '' }}>
                                <label class="form-check-label" for="user_places_filter">
                                    @lang('dashboard.user_places')
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('dashboard.search')</button>
                            @if(request()->user_places)
                                <a href="{{ route('dashboard.swalefs.index') }}" class="btn btn-secondary">@lang('dashboard.reset')</a>
                            @endif
                        </div>
                    </div>
                </form>
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

                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.image')</th>
                            <th>@lang('dashboard.title')</th>
                            <th>@lang('dashboard.description')</th>
                            <th>@lang('dashboard.type')</th>
                            <th>@lang('dashboard.content')</th>
                            <th>@lang('dashboard.active')</th>
                            <th>@lang('dashboard.created_at')</th>
                            @if(auth()->user()->can('patch-swalefs'))
                                <th>@lang('dashboard.show_in_home')</th>
                            @endif
                            <th>@lang('dashboard.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($swalefs->first()))
                        @foreach($swalefs as $index => $swalef)
                        <tr id="row-{{$swalef->id}}">
                        <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                            <td>
                                <a style="width: 200px;" href="{{resolvePhoto($swalef->image)}}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-60 flex-shrink-0">
                                            <div class="symbol-label" style="background-image: url({{resolvePhoto($swalef->image)}})">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td>{{ $swalef->title ?? '---'}}</td>
                            <td>{!! $swalef->description ? substr( $swalef->description, 0 , 50) : '---'!!}</td>
                            <td>
                                <span class="badge badge-primary">
                                    {{$swalef->type}}
                                </span>

                            </td>
                            <td>
                                @if(file_exists('storage/'.$swalef->content))
                                <a style="width: 200px;" href="{{resolvePhoto($swalef->content)}}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-60 flex-shrink-0">
                                            <div class="symbol-label" style="background-image: url({{resolvePhoto($swalef->content)}})">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @else
                                @php
                                    $content = $swalef->content ?? '';
                                    // Remove all HTML tags
                                    $content = strip_tags($content);
                                    // Decode HTML entities
                                    $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                    // Remove extra whitespace
                                    $content = trim(preg_replace('/\s+/', ' ', $content));
                                    // Limit length
                                    $content = mb_strlen($content) > 50 ? mb_substr($content, 0, 50) . '...' : $content;
                                @endphp
                                {!! $content ?: '---' !!}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($swalef->active)
                                <i class="fa fa-check-circle text-success"></i>
                                @else
                                <i class="fa fa-times-circle text-danger"></i>
                                @endif
                            </td>
                            <td class="number">{{ dateFormat($swalef->created_at) ?? '---' }}</td>

                            @if(auth()->user()->can('patch-swalefs'))
                                <td>
                                    <span class="switch switch-outline switch-icon switch-success">
                                        <label>
                                            <input id="active" class="activate" data-item-id="{{$swalef->id}}" type="checkbox" @if($swalef->show_in_home) checked @endif name="show_in_home"
                                                   value=""/>
                                            <span></span>
                                        </label>
                                    </span>
                                </td>
                            @endif

                            <td>
                                <a href="{{route('dashboard.swalefs.edit', $swalef->id)}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                    <i class="flaticon-edit-1 edit-icon"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.swalefs.destroy',$swalef->id) }}" data-item-id="{{ $swalef->id }}">
                                    <i class="flaticon2-trash trash-icon"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="pagination-cont">
                    <div class="d-flex flex-wrap py-2 mr-3 justify-content-center">
                        {!! str_replace('/?', '?', $swalefs->withQueryString()->render()) !!}
                    </div>
                </div>
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
        $('.activate').change(function() {
            var checked;
            var swalef_id = $(this).data('item-id');

            if ($(this).is(':checked')) {
                checked = 1;
            } else {
                checked = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{Route('dashboard.swalefs.showInHome')}}",
                method: 'POST',
                data: {
                    'checked': checked,
                    'swalef_id': swalef_id,
                },
                success: function(data) {
                    if (data.status) {
                        toastr.success(data.message);
                    }
                },
            });
        });

        $(document).ready(function () {
            const table = $('#dataTable').DataTable();

            // كل ما يرسم الجدول (بما فيها أول مرة)، اخفي الـ paginate
            $('#dataTable').on('draw.dt', function () {
                $('#dataTable_paginate').hide();
            });

            // مبدئيًا نخفيه بعد وقت بسيط لضمان أول مرة
            setTimeout(() => {
                $('#dataTable_paginate').hide();
            }, 300);
        });
    </script>

@endpush
