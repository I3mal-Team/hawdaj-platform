@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.caravans')</h5>
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
                        <h3 class="card-label">{{__('dashboard.caravans_list')}}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{route('dashboard.caravans.create')}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_caravan')</a>
                    </div>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.image')</th>
                            <th>@lang('dashboard.name')</th>
                            <th>@lang('dashboard.description')</th>
                            <th>@lang('dashboard.color')</th>
                            <th>@lang('dashboard.brand')</th>
                            <th>@lang('dashboard.licence_expiry_date')</th>
                            <th>@lang('dashboard.place_of_delivery')</th>
                            <th>@lang('dashboard.delivery_place')</th>
                            <th>@lang('dashboard.active')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.updated_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($caravans->first()))
                            @foreach($caravans as $index => $caravan)
                                <tr id="row-{{$caravan->id}}">
                                <td>{{($index + 1) + ((request('page' , 1)-1) * 10) }}</td>
                                    <td>
                                        <a style="width: 200px;" href="{{resolvePhoto($caravan->image)}}" target="_blank">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-60 flex-shrink-0">
                                                    <div class="symbol-label"
                                                         style="background-image: url({{resolvePhoto($caravan->image)}})">
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>{{$caravan->name ?? ''}}</td>
                                    <td>{{$caravan->description ?? ''}}</td>
                                    <td>{{$caravan->color ?? ''}}</td>
                                    <td>{{$caravan->brand ?? ''}}</td>
                                    <td>{{$caravan->licence_expiry_date ?? ''}}</td>
                                    <td>{{$caravan->place_of_delivery ?? ''}}</td>
                                    <td>{{$caravan->delivery_place ?? ''}}</td>
                                    <td class="text-center">
                                        @if($caravan->active)
                                            <i class="fa fa-check-circle text-success"></i>
                                        @else
                                            <i class="fa fa-times-circle text-danger"></i>
                                        @endif
                                    </td>
                                    {{--                                    <td>{{ $caravan->address ?? '---'}}</td>--}}
                                    <td>{{ dateFormat($caravan->created_at) ?? '---' }}</td>
                                    <td>{{ dateFormat($caravan->updated_at) ?? '---' }}</td>
                                    <td>
                                        <a href="{{route('dashboard.caravans.edit', $caravan->id)}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                           title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal"
                                           data-url="{{ route('dashboard.caravans.destroy',$caravan->id) }}"
                                           data-item-id="{{ $caravan->id }}">
                                            <i class="flaticon2-trash trash-icon" ></i>
                                        </a>
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
