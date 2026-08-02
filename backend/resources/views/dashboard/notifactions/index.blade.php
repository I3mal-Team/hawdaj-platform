@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url('dashboard/notifications')}}">{{__('dashboard.notifications')}}</a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:void(0);" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

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

                    <table class="table" id="dataTable">
                        <thead class="thead-light">
                        <tr>
                            <th width="10">#</th>
                            <th>{{ trans('dashboard.title') }}</th>
                            <th>{{ trans('dashboard.message') }}</th>
                            <th>{{ trans('dashboard.date') }}</th>
                            <th>{{ trans('dashboard.time') }}</th>
                        <!-- <th class="not-export-column">{{ trans('dashboard.action')}}</th> -->
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($notifications as $index => $notification)
                            <tr id="row-{{$notification->id}}">
                                @php $data = json_decode($notification->data); @endphp
                                <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                                <td>
                                    <a href="{{$data->url?url('/').$data->url:'javascript:;'}}">
                                        {{$data->title??'---'}}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{$data->url?url('/').$data->url:'javascript:;'}}">
                                        {{$data->message??'---'}}
                                    </a>
                                </td>
                                <td>{{dateFormat($notification->created_at)}}</td>
                                <td>{{timeFormat($notification->created_at)}}</td>
                            <!-- <td>
                                    <a class="btn btn-outline-success"
                                       href="{{ $data->url??'javascript:;' }}">
                                        {{__('dashboard.show')}}
                                </a>
                            </td> -->
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-cont">
                        <div class="d-flex flex-wrap py-2 mr-3 justify-content-center">
                            {{$notifications->links("pagination::bootstrap-4")}}
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
