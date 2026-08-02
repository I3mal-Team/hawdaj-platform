@extends('layouts.dashboard.master')

@section('page_header')
<style>
    span.select2-selection.select2-selection--single {
        height: 40px;
    }
</style>
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.places')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>

    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.places')</a>
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
    if ($.fn.dataTable.isDataTable('.dataTable1')) {
        table = $('.dataTable1').DataTable();
    } else {
        table = $('.dataTable1').DataTable({
            paging: false,
            searching: false,
        });
    }
    table = $('.dataTable1').DataTable({
        retrieve: true,
        paging: false,
        'searching': false,
    });

    // to check all the checkboxes

    $("#checkAll").click(function() {
        if ($('#checkAll:checkbox:checked').length > 0) {
            $('.delete_selected_modal').show();
        } else {
            $('.delete_selected_modal').hide();
        }
        $('.checkboxD:checkbox').not(this).prop('checked', this.checked);
    });

    $(".checkboxD:checkbox").click(function() {
        if ($('.checkboxD:checkbox:checked').length > 0) {
            $('.delete_selected_modal').show();

        } else {
            $('.delete_selected_modal').hide();
        }
    });

    $("#delete_selected").click(function(e) {
        e.preventDefault();

        var listArray = [];
        $(".checkboxD:checkbox:checked").each(function() {
            listArray.push($(this).val());
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('dashboard.places.destroy_selected') }}",
            type: 'delete',
            data: {
                'array_ids': listArray,
                'archive' : "{{request('archive')}}"
            },
            success: function(data) {
                $("#delete_selected_modal").modal("toggle");
                toastr.success(data.message);
                $('table tr').has('input.checkboxD:checkbox:checked').remove();
                $('.delete_selected_modal').hide();
            },

            error(data) {
                toastr.error(data.responseJSON.message);
            },
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#region_id').selectize({
            sortField: 'text'
        });
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
            url: "{{Route('dashboard.places.active')}}",
            method: 'POST',
            data: {
                'checked': checked,
                'placeId': place_id,
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
                    <h3 class="card-label">{{__('dashboard.places_list')}}</h3>
                </div>
                <div class="card-toolbar">

                    <a id="" href="#" class="btn btn-danger m-2 delete_selected_modal"
                     style="color: white !important; display:none" data-toggle="modal" data-target="#delete_selected_modal">
                        <i class="flaticon2-trash trash-icon" style="color: white !important;"></i>@lang('dashboard.delete_selected')</a>

                    <a href="{{ route('dashboard.places.export', request()->all()) }}" class="btn btn-success m-2">
                        <i class="la la-file-excel-o"></i>{{__('dashboard.export_excel')}}</a>

                    @if(!request('archive') && \App\Models\Place::onlyTrashed()->first())
                    <a href="{{route('dashboard.places.index' , ['archive' => 1])}}" class="btn btn-warning m-2">
                        <i class="la la-trash"></i>@lang('dashboard.archive')</a>

                    <a href="{{route('dashboard.places.create')}}" class="btn btn-primary m-2">
                        <i class="la la-plus"></i>@lang('dashboard.new_place')</a>
                    @else

                    <a href="{{route('dashboard.places.index')}}" class="btn btn-primary m-2">@lang('dashboard.places')</a>

                    @endif

                </div>
            </div>


            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <form action="{{ route('dashboard.places.index') }}" method="get" style="width: -webkit-fill-available;">

                    @if(Request::get('archive'))
                        <input type="hidden" name="archive" value="{{ Request::get('archive') }}">
                    @endif

                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <input name="title" class="form-control" placeholder="@lang('dashboard.search')" value="{{ request()->title }}">
                        </div>

                        <div class="col-md-2 mb-3 input-group ">
                            <select name="region_id" class="form-control select2" style="height:40px">
                                <option value="">@lang('dashboard.regions')</option>
                                @foreach ($regions as $region)
                                <option value="{{ $region->id }}" {{ request()->region_id == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3 input-group ">
                            <select name="city_id" class="form-control select2" style="height:40px">
                                <option value="">@lang('dashboard.cities')</option>
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}" {{ request()->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3 input-group ">
                            <select name="season" class="form-control select2" style="height:40px">
                                <option value="">@lang('dashboard.seasons')</option>
                                @foreach ($seasons as $season)
                                <option value="{{ $season }}" {{ request()->season === $season ? 'selected' : '' }}>{{ $season }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3 input-group ">
                            <select name="category_id" class="form-control select2" style="height:40px">
                                <option value="">@lang('dashboard.categories')</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request()->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="user_places" value="1" id="user_places_filter" 
                                       {{ request()->user_places ? 'checked' : '' }}>
                                <label class="form-check-label" for="user_places_filter">
                                    @lang('dashboard.user_places')
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('dashboard.search')</button>
                            @if(request()->user_places || request()->title || request()->region_id || request()->city_id)
                                <a href="{{ route('dashboard.places.index') }}" class="btn btn-secondary mt-2">@lang('dashboard.reset')</a>
                            @endif
                        </div>

                    </div>
                </form><!-- end of form -->

            </div><!-- end of box header -->
            @if(!empty($places->first()))
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
                <table class="table table-bordered dataTable1" id="">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll" class="checkbox"></th>
                            <th>#</th>
                            <th>@lang('dashboard.image')</th>
                            <th>@lang('dashboard.title')</th>
                            <th>@lang('dashboard.seasons')</th>
                            <th>@lang('dashboard.categories')</th>
                            <th>@lang('dashboard.region')</th>
                            <th>@lang('dashboard.city')</th>
                            @if(auth()->user()->can('patch-places'))
                                <th>@lang('dashboard.active')</th>
                            @endif
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.updated_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($places->first()))
                        @foreach($places as $index => $place)
                        <tr id="row-{{$place->id}}">
                            <td><input type="checkbox" name="ids[]" value="{{$place->id}}" data-item-id="{{$place->id}}" class="checkbox checkboxD"></td>

                            <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                            <td>
                                @php
                                    $descriptionImage = extractImageFromHtml($place->description ?? '');
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
                                @elseif($place->image)
                                <a style="width: 200px;" href="{{ resolvePhoto($place->image) }}" target="_blank">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-60 flex-shrink-0">
                                            <div class="symbol-label" style="background-image: url({{ resolvePhoto($place->image) }})">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @else
                                <span class="text-muted">---</span>
                                @endif
                            </td>
                            <td>{{ $place->title ?? '---'}}</td>
                            <td>
                                @if(isset($place->seasons_tra))
                                @if(count($place->seasons_tra) >0)
                                @foreach($place->seasons_tra as $season)
                                <span class="badge badge-pill badge-primary">{{ $season }}</span>
                                @endforeach
                                @endif
                                @endif
                            </td>
                            <td>
                                @if(isset($place->categories))
                                @if(count($place->categories) > 0)
                                @foreach($place->categories as $category)
                                <span class="badge badge-pill badge-primary">{{ \App\Models\Category::where('id',$category)->first()->name ?? '---' }}</span>
                                @endforeach
                                @endif
                                @endif
                            </td>
                            <td>{{ $place->region->name ?? '---'}}</td>
                            <td>{{ $place->city->name ?? '---'}}</td>
                            @if(auth()->user()->can('patch-places'))
                                <td class="text-center">
                                    <span class="switch switch-outline switch-icon switch-success">
                                        <label>
                                            <input id="active" class="activate" data-item-id="{{$place->id}}" type="checkbox" @if($place->active) checked @endif name="active"
                                            value=""/>
                                            <span></span>
                                        </label>
                                    </span>
                                </td>
                            @endif
                            <td>{{ dateFormat($place->created_at) ?? '---' }}</td>
                            <td>{{ dateFormat($place->updated_at) ?? '---' }}</td>
                            <td>

                                @if(request('archive',0))
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md restore-button" title="{{__('dashboard.restore')}}" data-toggle="modal" data-target="#restore_modal" data-url="{{ route('dashboard.places.restore',$place->id) }}" data-item-id="{{ $place->id }}">
                                    <i class="flaticon2-refresh refresh-icon"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.places.force_destroy',$place->id) }}" data-item-id="{{ $place->id }}">
                                    <i class="flaticon2-trash trash-icon"></i>
                                </a>
                                @else
                                <a href="{{route('dashboard.places.show', $place->id)}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.view_details')}}">
                                    <i class="flaticon-eye eye-icon"></i>
                                </a>
                                <a href="{{route('dashboard.places.edit', $place->id)}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                    <i class="flaticon-edit-1 edit-icon"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.places.destroy',$place->id) }}" data-item-id="{{ $place->id }}">
                                    <i class="flaticon2-trash trash-icon"></i>
                                </a>
                                <a href="{{route('dashboard.places.reviews', $place->id)}}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.reviews')}}">
                                    <i class="flaticon-star star-icon"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>

                </table>
                {!! str_replace('/?', '?', $places->withQueryString()->render()) !!}
            </div>
            @else
            <div class="card-body">

                <div class="card-toolbar" style=" text-align: center; background-color: #f2f3f8 ;
                                            padding: 100px;  color: #101010">
                    <div style="font-size: large">
                        <i class="la la-briefcase mb-2 " style="font-size: xxx-large; "></i><br>
                        @lang('dashboard.no_data')
                    </div>

                    <a href="{{route('dashboard.places.create')}}" class="btn btn-primary mt-2">
                        <i class="la la-plus"></i>@lang('dashboard.new_place')</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- begin: delete modal -->
@include('dashboard.includes.alerts.delete-modal')
@include('dashboard.includes.alerts.delete-selected-modal')
@include('dashboard.includes.alerts.restore-modal')
<!-- end:: delete modal -->
@endsection
