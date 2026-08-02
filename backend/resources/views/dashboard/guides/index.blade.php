@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.guides')</h5>
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
        var guide_id = $(this).data('item-id');

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
            url: "{{Route('dashboard.guides.active')}}",
            method: 'POST',
            data: {
                'checked': checked,
                'guide_id': guide_id,
            },
            success: function(data) {
                if (data.status) {
                    toastr.success(data.message);
                }
            },
        });
    });

    $('.show_in_home').change(function() {
        var checked;
        var guide_id = $(this).data('item-id');

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
            url: "{{Route('dashboard.guides.showInHome')}}",
            method: 'POST',
            data: {
                'checked': checked,
                'guide_id': guide_id,
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
                    <h3 class="card-label">{{__('dashboard.guides')}}</h3>
                </div>
            </div>
            <div class="card-header flex-wrap border-0 pt-6 pb-0">

                <form action="{{ route('dashboard.guides.index') }}" method="get" style="width: -webkit-fill-available;">

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
            @if(!empty($guides->first()))
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
                            <th>@lang('dashboard.name')</th>
                            <th>@lang('dashboard.image')</th>
                            <th>@lang('dashboard.experience')</th>
                            @if(auth()->user()->can('patch-guides'))
                                <th>@lang('dashboard.active')</th>
                                <th>@lang('dashboard.show_in_home')</th>
                            @endif
                            <th>@lang('dashboard.action')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($guides as $index => $guide)
                        <tr id="row-{{$guide->id}}">
                        <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                            <td>{{ $guide->name ?? '---'}}</td>

                            <td> <img width="70" src="{{ $guide->getFirstMediaUrl('image', 'small') ?: asset('images/default-avatar.png') }}"  alt=""></td>


                            <td>{{ $guide->experience }} </td>

                            @if(auth()->user()->can('patch-guides'))
                                <td>
                                    <span class="switch switch-outline switch-icon switch-success">
                                        <label>
                                            <input id="active" class="activate" data-item-id="{{$guide->id}}" type="checkbox" @if($guide->active) checked @endif name="active"
                                                   value=""/>
                                            <span></span>
                                        </label>
                                    </span>
                                </td>

                                <td>
                                <span class="switch switch-outline switch-icon switch-success">
                                    <label>
                                        <input id="show_in_home" class="show_in_home" data-item-id="{{$guide->id}}" type="checkbox" @if($guide->show_in_home) checked @endif name="show_in_home"
                                               value=""/>
                                        <span></span>
                                    </label>
                                </span>
                                </td>

                            @endif
                            <td>
                                <a href="{{route('dashboard.guides.show', $guide->id)}}" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="{{__('dashboard.show')}}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.guides.destroy',$guide->id) }}" data-item-id="{{ $guide->id }}">
                                    <i class="flaticon2-trash trash-icon"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                {!! str_replace('/?', '?', $guides->withQueryString()->render()) !!}

            </div>
            @else
            <div class="card-body">

                <div class="card-toolbar" style=" text-align: center; background-color: #f2f3f8 ;padding: 100px;  color: #101010">
                    <div style="font-size: large">
                        <i class="la la-briefcase mb-2 " style="font-size: xxx-large; "></i><br>
                        @lang('dashboard.no_data')
                    </div>

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
