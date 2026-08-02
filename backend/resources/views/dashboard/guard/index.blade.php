@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.guard')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.visit_search')</a>
        </li>
    </ul>
@endsection
@push('js')

    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script>
        function ScanCode() {
            $("#defualtScanImage").css('display', 'none')
            $("#preview").css('display', 'block')
            let scanner = new Instascan.Scanner({video: document.getElementById('preview')});
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    alert('No cameras found');
                }

            }).catch(function (e) {
                console.error(e);
            });

            scanner.addListener('scan', function (c) {
                document.getElementById('value').value = c;
                $("#scanForm").submit();
            });
        }

        // matrial
        function ScanmaterialCode() {
            $("#material_defualtScanImage").css('display', 'none')
            $("#material_preview").css('display', 'block')
            let scanner = new Instascan.Scanner({video: document.getElementById('material_preview')});
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    alert('No cameras found');
                }

            }).catch(function (e) {
                console.error(e);
            });

            scanner.addListener('scan', function (c) {
                document.getElementById('Matvalue').value = c;
                $("#scanMatForm").submit();
            });
        }

        // car
        function ScanCarCode() {
            $("#material_defualtScanImage").css('display', 'none')
            $("#material_preview").css('display', 'block')
            let scanner = new Instascan.Scanner({video: document.getElementById('material_preview')});
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    alert('No cameras found');
                }

            }).catch(function (e) {
                console.error(e);
            });

            scanner.addListener('scan', function (c) {
                document.getElementById('Matvalue').value = c;
                $("#scanMatForm").submit();
            });
        }
    </script>
@endpush

@section('content')
    <!--begin::Example-->
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-3 ">
                    <ul class="nav nav-pills scroll-sm-nav-pills flex-md-column rounded bg-white" id="myTab1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="visitor-tab-1"
                               data-toggle="tab" href="#visitor-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon-users-1 "></i>
                                                </span>
                                <span
                                    class="nav-text">{{ __('dashboard.visitors') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="material-tab-1" data-toggle="tab"
                               href="#material-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon2-box-1 "></i>
                                                </span>
                                <span
                                    class="nav-text">{{ __('dashboard.materials') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="car-tab-1" data-toggle="tab"
                               href="#car-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon2-delivery-truck "></i>
                                                </span>
                                <span class="nav-text">{{ __('dashboard.cars') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contractor-tab-1" data-toggle="tab"
                               href="#contractor-1">
                                                <span class="nav-icon">
                                                    <!-- <i class="flaticon2-user"></i> -->
                                                    <i class="flaticon-users-1 "></i>
                                                </span>
                                <span
                                    class="nav-text">{{ __('dashboard.Contractors') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-md-9 ">
                    <div class="bg-white rounded p-10">
                        <!-- steps -->
                        <div class="example-preview">

                            <div class="tab-content" id="myTabContent1">
                                <div class="tab-pane fade show" id="material-1" role="tabpanel"
                                     aria-labelledby="material-tab-1">
                                    <!-- first tab form -->
                                @include('dashboard.guard.material_scan')
                                <!-- end first tab form -->
                                </div>
                                <div class="tab-pane fade show active" id="visitor-1"
                                     role="tabpanel" aria-labelledby="visitor-tab-1">
                                    <!-- first tab form -->
                                @include('dashboard.guard.visitor_scan')
                                <!-- end first tab form -->
                                </div>
                                <div class="tab-pane fade show" id="car-1" role="tabpanel"
                                     aria-labelledby="car-tab-1">
                                    <!-- first tab form -->
                                @include('dashboard.guard.car_scan')
                                <!-- end first tab form -->
                                </div>
                                <div class="tab-pane fade show" id="contractor-1"
                                     role="tabpanel" aria-labelledby="contractor-tab-1">
                                    <!-- first tab form -->
                                @include('dashboard.guard.contractor_scan')
                                <!-- end first tab form -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    <!--begin::Code example-->

@endsection
