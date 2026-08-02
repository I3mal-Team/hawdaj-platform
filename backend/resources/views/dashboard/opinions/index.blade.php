@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url('dashboard/opinions')}}">{{__('dashboard.opinions')}}</a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>

        <li class="breadcrumb-item text-muted">
            <a href="javascript:void(0);" class="text-muted">{{$title}}</a>
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

@push('css')
    <style>
        #dataTable_info, #dataTable_paginate {
            display: none !important;
        }

        .pagination .page-link {
            padding: 0.7rem 1.5rem;
        }
    </style>
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{$title}}</h3>
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

                    <table class="table dataTable">
                        <thead class="thead-light">
                        <tr>
                            <th width="10">#</th>
                            <th>{{ trans('dashboard.user') }}</th>
                            <th>{{ trans('dashboard.message') }}</th>
                            <th>{{ trans('dashboard.date') }}</th>
                            <th>{{ trans('dashboard.time') }}</th>
                            <!-- <th class="not-export-column">{{ trans('dashboard.action')}}</th> -->
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($opinions as $index => $opinion)
                            <tr id="row-{{$opinion->id}}">
                            <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                                <td>
                                    <a href="{{route('dashboard.opinions.show' , $opinion->id)}}">
                                        {{$opinion->name}}
                                        <br/>
                                        {{$opinion->phone}}
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript:;">
                                        {{$opinion->message}}
                                    </a>
                                </td>
                                <td>{{dateFormat($opinion->created_at)}}</td>
                                <td>{{timeFormat($opinion->created_at)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-cont">
                        <div class="d-flex flex-wrap py-2 mr-3 justify-content-center">
                        {!! str_replace('/?', '?', $opinions->withQueryString()->render()) !!}
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
