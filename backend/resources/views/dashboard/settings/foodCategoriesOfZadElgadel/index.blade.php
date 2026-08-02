@extends('layouts.dashboard.master')

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

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.food-categories')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.zad_elgadels')</a>
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
                        <h3 class="card-label">{{__('dashboard.food-categories_list')}}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{route('dashboard.zad_elgadel-food-categories.create')}}"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>@lang('dashboard.new_category')</a>
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
                    <!--begin: Datatable-->
                    <table class="table table-bordered dataTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.photo')</th>
                            <th>@lang('dashboard.category')</th>
                            <th>@lang('dashboard.category_name')</th>
                            <th>@lang('dashboard.notes')</th>
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.updated_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($categories->first()))
                            @foreach($categories as $index => $category)
                                <tr id="row-{{$category->id}}">
                                <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                                    <td>
                                        <a style="width: 200px;" href="{{url($category->icon)}}" target="_blank">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-60 flex-shrink-0">
                                                    <div class="symbol-label"
                                                         style="background-image: url({{url($category->icon)}})">
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>{{ $category->parent_id?$category->parent->name :'---'}}</td>
                                    <td>{{ $category->name?? '---'}}</td>
                                    <td>{{ $category->notes?? '---'}}</td>
                                    <td>{{ dateFormat($category->created_at) ?? '---' }}</td>
                                    <td>{{ dateFormat($category->updated_at) ?? '---' }}</td>
                                    <td>
                                        <a href="{{route('dashboard.zad_elgadel-food-categories.edit', $category->id)}}"
                                           class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.edit')}}">
                                            <i class="flaticon-edit-1 edit-icon"></i>
                                        </a>
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                           title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal"
                                           data-url="{{ route('dashboard.zad_elgadel-food-categories.destroy',$category->id) }}"
                                           data-item-id="{{ $category->id }}">
                                            <i class="flaticon2-trash trash-icon" ></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {!! str_replace('/?', '?', $categories->withQueryString()->render()) !!}
                </div>
            </div>
        </div>
    </div>


    <!-- begin: delete modal -->
    @include('dashboard.includes.alerts.delete-modal')
    <!-- end:: delete modal -->
@endsection
