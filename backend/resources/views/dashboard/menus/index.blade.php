@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.zad_elgadels')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">{{$title}}</a>
    </li>
</ul>
@endsection

@push('js')
 <script>
    $(document).ready(function() {
        $('.insertion').select2({
            tags: true
        });
    });

    //disable search in data table and paging
    if ($.fn.dataTable.isDataTable('.dataTable2')) {
        table = $('.dataTable2').DataTable();
    } else {
        table = $('.dataTable2').DataTable({
            paging: false,
            searching: false,
        });
    }
    table = $('.dataTable2').DataTable({
        retrieve: true,
        paging: false,
        'searching': false
    });




</script>

 <script>
    $('.activate').change(function() {
        var checked;
        var place_id = $(this).data('item-id');

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
            url: "{{Route('dashboard.zad_elgadels.active')}}",
            method: 'POST',
            data: {
                'checked': checked,
                'zad_elgadelId': place_id,
            },
            success: function(data) {
                if (data.status) {
                    toastr.success(data.message);
                }
            },
        });
    });
</script>

@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{__('dashboard.Menus')}}</h3>
                </div>
                 <div class="card-toolbar">


                    @if(!empty($menus->first()) && !request('archive'))

                    <a href="{{route('dashboard.menu.create')}}" class="btn btn-primary"><i class="la la-plus"></i>@lang('dashboard.Menus')</a>
                    @else

                    <a href="{{route('dashboard.menu.index')}}" class="btn btn-primary m-2">@lang('dashboard.Menus')</a>
                    @endif

                </div>
            </div>
            <div class="card-header flex-wrap border-0 pt-6 pb-0">

                <form action="{{ route('dashboard.menu.index') }}" method="get" style="width: -webkit-fill-available;">

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <input name="title" class="form-control" placeholder="@lang('dashboard.search')" value="{{ request()->title }}">
                        </div>


                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('dashboard.search')</button>
                        </div>

                    </div>
                </form>

            </div>
            @if(!empty($menus->first()))
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

                <!--begin: Datatable-->
                <table class="table table-bordered dataTable2" id="">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.title')</th>
                            <th>@lang('dashboard.description')</th>
                            <th>@lang('dashboard.price')</th>
                            <th>@lang('dashboard.image')</th>
                            <th>@lang('dashboard.zad_elgadels')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.updated_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($menus->first()))
                        @foreach($menus as $index => $menu)
                        <tr id="row-{{$menu->id}}">
                        <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                            <td>{{ $menu->title ?? '---'}}</td>
                            <td>{{ $menu->description ?? '---'}}</td>
                            <td>{{ $menu->price ?? 0}}</td>

                            <td>
                                @php
                                    $descriptionImage = extractImageFromHtml($menu->description ?? '');
                                @endphp
                                @if($descriptionImage)
                                <a style="width: 200px;" href="{{ $descriptionImage }}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-60 flex-shrink-0">
                                            <div class="symbol-label" style="background-image: url({{ $descriptionImage }})">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @elseif($menu->image)
                                <a style="width: 200px;" href="{{ resolvePhoto($menu->image) }}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-60 flex-shrink-0">
                                            <div class="symbol-label" style="background-image: url({{ resolvePhoto($menu->image) }})">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @else
                                <span class="text-muted">---</span>
                                @endif
                            </td>


                            <td>{{ $menu->zad->title ?? '---' }}</td>


                            <td>{{ dateFormat($menu->created_at) ?? '---' }}</td>
                            <td>{{ dateFormat($menu->updated_at) ?? '---' }}</td>
                            <td>

                                @if(request('archive',0))
                                {{-- <a class="btn btn-sm btn-clean btn-icon btn-icon-md restore-button" title="{{__('dashboard.restore')}}" data-toggle="modal" data-target="#restore_modal" data-url="{{ route('dashboard.zad_elgadels.restore',$zadElgadel->id) }}" data-item-id="{{ $zadElgadel->id }}"> --}}
                                    <i class="flaticon2-refresh refresh-icon"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.force_delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.zad_elgadels.destroy',[$menu->id , 'archive' => request('archive')]) }}" data-item-id="{{ $menu->id }}">
                                    <i class="flaticon2-trash trash-icon"></i>
                                </a>
                                @else

                                <a href="{{route('dashboard.menu.edit', $menu->id)}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                    <i class="flaticon-edit-1 edit-icon"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.menu.destroy',$menu->id) }}" data-item-id="{{ $menu->id }}">
                                    <i class="flaticon2-trash trash-icon"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                {{-- {!! str_replace('/?', '?', $menu->render()) !!} --}}

            </div>
            @else
            <div class="card-body">

                <div class="card-toolbar" style=" text-align: center; background-color: #f2f3f8 ;padding: 100px;  color: #101010">
                    <div style="font-size: large">
                        <i class="la la-briefcase mb-2 " style="font-size: xxx-large; "></i><br>
                        @lang('dashboard.no_data')
                    </div>

                    <a href="{{route('dashboard.menu.create')}}" class="btn btn-primary mt-2">
                        <i class="la la-plus"></i>@lang('dashboard.new_menu')</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


<!-- begin: delete modal -->
@include('dashboard.includes.alerts.delete-modal')
{{-- @include('dashboard.includes.alerts.delete-selected-modal') --}}
@include('dashboard.includes.alerts.restore-modal')


@endsection
