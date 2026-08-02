<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title d-flex flex-column">
                <h5 class="" id="exampleModalLabel">{{ str_replace('_',' ',__("dashboard.$request->type"))}}<br>

                </h5>
                <span style=" font-size: small">{{ __('dashboard.status') }} : <span style="color: #1bc5bd;">{{ __("dashboard.$request->status") }}</span></span>
            </div>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="modal-body" style="height: 100vh;">
            <div>
                <div class="row">
                    <div class="container-fluid">
                        <!--begin::Container-->
                        <div class="custom-nav modal-nav">
                            <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#details">
                                        <span class="nav-icon">
                                            <i class="flaticon2-chat-1"></i>
                                        </span>
                                        <span class="nav-text">{{ __('dashboard.request_details') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#material_details">
                                            <span class="nav-icon">
                                                <i class="flaticon2-layers-1"></i>
                                            </span>
                                        <span class="nav-text">{{ __('dashboard.material_details') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#transporter">
                                            <span class="nav-icon">
                                                <i class="flaticon2-user"></i>
                                            </span>
                                        <span class="nav-text">{{ __('dashboard.transporter_details') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#action">
                                            <span class="nav-icon">
                                                <i class="flaticon2-rocket-1"></i>
                                            </span>
                                        <span class="nav-text">{{ __('dashboard.action') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#history">
                                            <span class="nav-icon">
                                                <i class="fa fa-history" aria-hidden="true"></i>
                                            </span>
                                        <span class="nav-text">{{ __('dashboard.history') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content  custom-tab-content" id="myTabContent1">
                            <div class="tab-pane fade show active" id="details" role="tabpanel">
                                @include('dashboard.materials.activities.tabs.request')
                            </div>
                            <div class="tab-pane fade" id="material_details" role="tabpanel">
                                @include('dashboard.materials.activities.tabs.material')
                            </div>
                            <div class="tab-pane fade" id="transporter" role="tabpanel">
                                @include('dashboard.materials.activities.tabs.transporter')
                            </div>
                            <div class="tab-pane fade" id="action" role="tabpanel">
                                @include('dashboard.materials.activities.tabs.action')
                            </div>
                            <div class="tab-pane fade" id="history" role="tabpanel">
                                @include('dashboard.materials.activities.tabs.history')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
