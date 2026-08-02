<div class="row justify-content-md-center">
    <div class="col-12 search-permission-cont">
        <h3 class="title">
            {{ __('dashboard.search_permission') }}
        </h3>
        <form action="{{route('dashboard.guard.visit_search')}}" method="GET">
            <div class="row ">
                <div class="mb-2 col-12 col-md-5">
                    <select class="nice-select form-control" name="searchType">
                        <option value="1">{{ __('dashboard.visitor_requests_id') }}</option>
                        <option value="2">{{ __('dashboard.visitors_number') }}</option>
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
                    <a type="button" onclick="ScanCode()" class="btn btn-outline-success btn-custom-color btn-block">{{ __('dashboard.scan') }}</a>
                </div>
            </div>
            <form id="scanForm" action="{{route('dashboard.guard.visit_search')}}" method="GET">
                <input type="hidden" name="searchType" value="1" class="form-control">
                <input type="hidden" name="value" id="value"  placeholder="scan qrcode" class="form-control">
            </form>
        </div>
    </div>
</div>
