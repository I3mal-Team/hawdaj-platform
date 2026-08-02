@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url("dashboard/cars/".$notes->car->site->id)}}">
            @lang('dashboard.car_model')
        </a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="{{url()->previous()}}">{{__('dashboard.notes')}}</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom overflow-auto">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{$title}}</h3>
                    </div>
                    <div class="card-toolbar">
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tbody>
                        <tr>
                            <th>
                                {{ trans('dashboard.id') }}
                            </th>
                            <td>
                                {{ $notes->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('dashboard.notes') }}
                            </th>
                            <td>
                                {{ $notes->notes }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('dashboard.site_name') }}
                            </th>
                            <td>
                                {{ $notes->car->site->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('dashboard.observer') }}
                            </th>
                            <td>
                                {{ $notes->owner->full_name }}
                            </td>
                        </tr>

                        @if($notes->file)
                            <tr>
                                <th>
                                    {{ trans('dashboard.file') }}
                                </th>
                                <td>
                                    <a href="{{resolvePhoto($notes->file)}}" target='_blank'>
                                      <span class="badge badge-success">
                                         {{trans('dashboard.download')}}
                                      </span>
                                    </a>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>
                                {{ trans('dashboard.created_at') }}
                            </th>
                            <td>
                                {{dateFormat($notes->car->notice_time) .' '. timeFormat($notes->car->notice_time)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

