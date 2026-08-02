<div class="row justify-content-md-center">
    <div class="col-12 search-permission-cont">
        <h3 class="title">
            {{ __('dashboard.search_permission') }}
        </h3>
        <form action="{{route('dashboard.guard.contract_visit_search')}}" method="GET">
            <div class="row ">
                <div class="mb-2 col-12 col-md-5">
                    <select class="nice-select form-control" name="searchType">
                        <option value="1">{{ __('dashboard.contractor_requests_id') }}</option>
                        <option value="2">{{ __('dashboard.contractors_number') }}</option>
                        <option value="3">{{ __('dashboard.mobile') }}</option>
                    </select>
                </div>
                <div class="mb-2 col-12 col-md-5">
                    <input type="text" required name="value" class="form-control" placeholder="{{ __('dashboard.search') }}"/>
                </div>
                <div class="col-12  col-md-2">
                    <button type="submit" class="btn btn-outline-primary btn-custom-color btn-block">{{ __('dashboard.search') }}</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12 my-6">
        <div class="or_divider">{{ __('dashboard.or') }}</div>
    </div>
    <div class="col-12 qr-scan-cont ">
        <div class="row justify-content-md-center">
            <div class="col-12 text-center">
                <h3 class="title" >{{ __('dashboard.scan_qr_code') }}</h3>
            </div>
            <div class="col-12 col-md-6 col-lg-4 text-center mx-auto">

                <img id="defualtScanImage" class="mb-2" src="/dashboard_assets/media/qr_code.png" alt="" width="50%">
                <video id="preview" width="100%" style="display:none"></video>
                <div class="mt-4">
                    <a type="button" onclick="ScanCode()"  class="btn btn-outline-success btn-custom-color btn-block">{{ __('dashboard.scan') }}</a>
                </div>
            </div>
            <form id="scanForm" action="{{route('dashboard.guard.contract_visit_search')}}" method="GET">
                <input type="hidden" name="searchType" value="1" class="forn-control">
                <input type="hidden" name="value" id="value"  placeholder="scan qrcode" class="form-control">
            </form>
        </div>
    </div>
</div>






{{--<div class="col-md-12 bg-white">--}}
{{--    <div class="row justify-content-md-center">--}}
{{--        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--            <div class="bg-white rounded p-10">--}}
{{--                <!--begin::Card-->--}}
{{--                <div class="card card-custom card-border mb-4" style="padding:inherit">--}}
{{--                    <div class="card-header">--}}
{{--                        <div class="card-toolbar text-center">--}}
{{--                            <div class="row justify-content-md-center">--}}
{{--                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">--}}
{{--                                    <div class="card-title">--}}
{{--                                        <h3 class="card-label" style="margin:auto">{{ __('dashboard.scan_qr_code') }}</h3>--}}
{{--                                    </div>--}}
{{--                                    <img id="defualtScanImage" class="mb-2" src="/dashboard_assets/media/qr_code.png" alt="" width="50%">--}}
{{--                                    <video id="preview" width="100%" style="display:none"></video>--}}
{{--                                    <div class="mt-4">--}}
{{--                                        <a type="button" onclick="ScanCode()" class="btn btn-outline-secondary btn-block">{{ __('dashboard.scan') }}</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <form id="scanForm" action="{{route('dashboard.guard.contract_visit_search')}}" method="GET">--}}
{{--                                    <input type="hidden" name="searchType" value="1" class="forn-control">--}}
{{--                                    <input type="hidden" name="value" id="value"  placeholder="scan qrcode" class="form-control">--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!--end::Card-->--}}

{{--                <!--begin::Card 2-->--}}
{{--                <div class="card card-custom card-border mb-4" style="padding:inherit">--}}
{{--                    <div class="card-header" style="margin:auto">--}}
{{--                        <div class="card-toolbar text-center">--}}
{{--                            <div class="row justify-content-md-center">--}}
{{--                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
{{--                                    <div class="card-title">--}}
{{--                                        <h3 class="card-label" style="margin:auto">{{ __('dashboard.search_permission') }}</h3>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}
{{--                        <form action="{{route('dashboard.guard.contract_visit_search')}}" method="GET">--}}
{{--                            <div class="mb-2">--}}
{{--                                <select class="nice-select form-control" name="searchType">--}}
{{--                                    <option value="1">{{ __('dashboard.contractor_requests_id') }}</option>--}}
{{--                                    <option value="2">{{ __('dashboard.contractors_number') }}</option>--}}
{{--                                    <option value="3">{{ __('dashboard.mobile') }}</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="mb-2">--}}
{{--                                <input type="text" required name="value" class="form-control" placeholder="{{ __('dashboard.search') }}"/>--}}
{{--                            </div>--}}
{{--                            <div class="mt-4">--}}
{{--                                <button type="submit" class="btn btn-outline-secondary btn-block">{{ __('dashboard.search') }}</button>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!--end::Card 2-->--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
