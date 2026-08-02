@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.prices')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="{{url('dashboard/setting/prices')}}" class="text-muted">@lang('dashboard.prices')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection
@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('.description'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        $(document).on('change', '#region_id', function () {
            // get cities
            const region_id = $(this).val()

            $.ajax
            ({
                type: "GET",
                url: "{{ route('dashboard.getCities') }}",
                data: {region_id: region_id},
                success: function (data) {
                    $('#city_id').empty();
                    $('#city_id').append(data);
                }
            });
        })
    </script>
@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}}</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column-fluid">
                            <div class="container-fluid ">
                                <div class="card card-custom gutter-b">

                                    <div class="card-body custom-nav">
                                        <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info">
                                                    <span class="nav-icon"><i class="flaticon-users-1"></i></span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="image-tab" data-toggle="tab" href="#image">
                                                    <span class="nav-icon"><i class="flaticon2-image-file"></i></span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="ceo-tab" data-toggle="tab" href="#ceo">
                                                    <span class="nav-icon"><i class="flaticon2-setup"></i></span>
                                                </a>
                                            </li>
                                        @if($data->address_type == 'map')
                                                <li class="nav-item">
                                                    <a class="nav-link" id="location-tab" data-toggle="tab"
                                                       href="#location">
                                                        <span class="nav-icon"><i class="flaticon2-map"></i></span>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="info" role="tabpanel"
                                                 aria-labelledby="info-tab">@include('dashboard.stores.includes._global_edit')</div>
                                            <div class="tab-pane fade show" id="image" role="tabpanel"
                                                 aria-labelledby="image-tab">@include('dashboard.includes.gallery._place_gallery')</div>
                                            <div class="tab-pane fade show" id="ceo" role="tabpanel"
                                                 aria-labelledby="location-tab">@include('dashboard.includes.ceo._global_ceo')</div>
                                            <div class="tab-pane fade show" id="location" role="tabpanel"
                                                 aria-labelledby="ceo-tab">@include('dashboard.includes.map._global_g_map')</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
