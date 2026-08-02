@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url("dashboard/cars/".request('site_id'))}}">
            {{__('dashboard.car_model')}}
        </a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
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
                    <div class="card-toolbar">

                    </div>
                </div>
                <div class="card-body">
                    <table class="table" id="dataTable">
                        <thead>
                        <tr>
                            <th width="10">#</th>
                            <th>{{ trans('dashboard.notes') }}</th>
{{--                            <th>{{ trans('dashboard.camID') }}</th>--}}
                            <th>{{ trans('dashboard.notice_time') }}</th>
                            <th>{{ trans('dashboard.site_name') }}</th>
                            <th>{{trans('dashboard.observer')}}</th>
                            <th class="not-export-column">{{trans('dashboard.file')}}</th>
                            <th class="not-export-column">{{ trans('dashboard.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($notes as $index => $log)
                            <tr id="row-{{$log->id}}">
                                <td class="bold">{{++$index}}</td>
                                <td>{{$log->notes??'---'}}</td>
{{--                                <td>{{$log->car->camID}}</td>--}}
                                <td>{{dateFormat($log->notice_time) .' '. timeFormat($log->notice_time)}}</td>
                                <td>{{$log->car->site->name}}</td>
                                <td>{{$log->owner->full_name}}</td>
                                <td>
                                    @if($log->file)
                                        <a href="{{resolvePhoto($log->file)}}" target="_blank">
                                                <span class="badge badge-success">
                                                 {{trans('dashboard.show_file')}}
                                                </span>
                                        </a>
                                    @else
                                        <span class="badge badge-danger">No File</span>
                                    @endif
                                </td>
                                <th>
                                    <a class="btn btn-outline-success btn-sm" target="_blank"
                                       href="{{url('dashboard/car_notes/'.$log->id)}}">
                                        {{__('dashboard.show')}}
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm delete-button"
                                       title="delete" data-toggle="modal" data-target="#delete_modal"
                                       data-url="{{ route('car_notes.destroy',$log->id) }}"
                                       data-item-id="{{ $log->id }}">
                                        {{__('dashboard.delete')}}
                                    </a>
                                </th>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                    </table>
                    <div class="pagination-cont">
                        <div class="d-flex flex-wrap py-2 mr-3 justify-content-center">
                            {{$notes->links("pagination::bootstrap-4")}}
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

