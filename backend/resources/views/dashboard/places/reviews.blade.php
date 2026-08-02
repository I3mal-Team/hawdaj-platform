@extends('layouts.dashboard.master')

@section('page_header')
<style>
    span.select2-selection.select2-selection--single {
        height: 40px;
    }
</style>
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.rates')</h5>
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
        'searching': false
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
            url: "{{ route('dashboard.places.destroy_selected' , ['type' => 'reviews']) }}",
            type: 'delete',
            data: {
                'array_ids': listArray,
            },
            success: function(data) {
                $("#delete_selected_modal").modal("toggle");
                toastr.success(data.message);
                $('table tr').has('input.checkboxD:checkbox:checked').remove();
                $('#delete_selected').hide();
            },

            error(data) {
                toastr.error(data.responseJSON.message);
            },
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
                    <h3 class="card-label">{{__('dashboard.rates_list')}}</h3>
                </div>
                <div class="card-toolbar">

                    <a id="" href="#" class="btn btn-danger m-2 delete_selected_modal"
                     style="color: white !important; display:none" data-toggle="modal" data-target="#delete_selected_modal">
                        <i class="flaticon2-trash trash-icon" style="color: white !important;"></i>@lang('dashboard.delete_selected')</a>
                </div>
            </div>


            @if(!empty($reviews->first()))
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-bordered dataTable1" id="">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll" class="checkbox"></th>
                            <th>#</th>
                            <th>@lang('dashboard.name')</th>
                            <th>@lang('dashboard.email')</th>
                            <th>@lang('dashboard.rateText')</th>
                            <th>@lang('dashboard.rate')</th>
                            <!-- <th>@lang('dashboard.active')</th> -->
                            <th>@lang('dashboard.created_at')</th>
                            <th>@lang('dashboard.updated_at')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($reviews->first()))
                        @foreach($reviews as $index => $review)
                        <tr id="row-{{$review->id}}">
                            <td><input type="checkbox" name="ids[]" value="{{$review->id}}" data-item-id="{{$review->id}}" class="checkbox checkboxD"></td>
                            <td>{{$index + 1}}</td>
                            <td>{{ $review->name ?? '---'}}</td>
                            <td>{{ $review->email ?? '---'}}</td>
                            <td>{{ $review->rateText ?? '---'}}</td>
                            <td>
                                @for($i=0;$i< $review->rate ?? 0; $i++)
                                    <i class="flaticon-star star-icon"></i>
                                @endfor
                            </td>
                            <!-- <td class="text-center">
                                <span class="switch switch-outline switch-icon switch-success">
                                    <label>
                                        <input id="active" class="activate" data-item-id="{{$review->id}}" type="checkbox" @if($review->active) checked @endif name="active" value=""/>
                                        <span></span>
                                    </label>
                                </span>
                            </td> -->
                            <td>{{ dateFormat($review->created_at) ?? '---' }}</td>
                            <td>{{ dateFormat($review->updated_at) ?? '---' }}</td>
                            <td>
                                <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.places.reviews.destroy_reviews',$review->id) }}" data-item-id="{{ $review->id }}">
                                    <i class="flaticon2-trash trash-icon"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>

                </table>
                {!! str_replace('/?', '?', $reviews->render()) !!}
            </div>
            @else
            <div class="card-body">

                <div class="card-toolbar" style=" text-align: center; background-color: #f2f3f8 ;
                                            padding: 100px;  color: #101010">
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
@include('dashboard.includes.alerts.delete-selected-modal')
<!-- end:: delete modal -->
@endsection
