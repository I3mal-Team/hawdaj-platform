@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.applications')</h5>
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
        var typeValue = $("#app_type option:selected").val();
        if (typeValue === 'web') {
            $('#ios').css('display', 'none');
            $('#android').css('display', 'none');
            $('#web').css('display', 'block');
        } else if (typeValue === 'app') {
            $('#ios').css('display', 'block');
            $('#android').css('display', 'block');
            $('#web').css('display', 'none');
        }

        $("#app_type").change(
            function() {
                var typeValue = $("#app_type option:selected").val();

                console.log(typeValue)

                if (typeValue === 'web') {
                    $('#ios').css('display', 'none');
                    $('#android').css('display', 'none');
                    $('#web').css('display', 'block');
                } else if (typeValue === 'app') {
                    $('#ios').css('display', 'block');
                    $('#android').css('display', 'block');
                    $('#web').css('display', 'none');
                }
            });
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

                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">@include('dashboard.applications.includes._global_edit')</div>

                                    </div>

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
