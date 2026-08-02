<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('dashboard.car_request_invitation') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="modal-body" style="height: 100vh;">
            <div>
                <div class="row">
                    <div class="container">
                        <div class="mb-4">
                            <div class="example-preview">
                                <ul class="nav nav-pills bg-gray-100" id="myTab1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="details-tab-1" data-toggle="tab" href="#details">
                                    <span class="nav-icon">
                                        <i class="flaticon2-chat-1"></i>
                                    </span>
                                            <span class="nav-text">{{ __('dashboard.request_details') }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="car-tab-1" data-toggle="tab" href="#car-1"
                                           aria-controls="car">
                                    <span class="nav-icon">
                                        <i class="flaticon2-layers-1"></i>
                                    </span>
                                            <span class="nav-text">{{ __('dashboard.car_details') }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="driver-tab-1" data-toggle="tab" href="#driver-1"
                                           aria-controls="driver">
                                    <span class="nav-icon">
                                        <i class="flaticon2-layers-1"></i>
                                    </span>
                                            <span class="nav-text">{{ __('dashboard.driver_details') }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="action-tab-1" data-toggle="tab" href="#action-1"
                                           aria-controls="action">
                                    <span class="nav-icon">
                                        <i class="flaticon2-rocket-1"></i>
                                    </span>
                                            <span class="nav-text">{{ __('dashboard.action') }}</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-5" id="myTabContent1">
                                    <div class="tab-pane fade show active" id="details" role="tabpanel"
                                         aria-labelledby="details-tab-1">
                                        @include('dashboard.cars.tabs.request_details')
                                    </div>
                                    <div class="tab-pane fade" id="car-1" role="tabpanel"
                                         aria-labelledby="car-tab-1">
                                        @include('dashboard.cars.tabs.car_details')
                                    </div>
                                    <div class="tab-pane fade" id="driver-1" role="tabpanel"
                                         aria-labelledby="driver-tab-1">
                                        @include('dashboard.cars.tabs.driver_details')
                                    </div>
                                    <div class="tab-pane fade" id="action-1" role="tabpanel"
                                         aria-labelledby="action-tab-1">
                                        @include('dashboard.cars.tabs.action')
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
