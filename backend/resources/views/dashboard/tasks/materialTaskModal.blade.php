<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('dashboard.material_details') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="modal-body" style="height: 100vh;">
            <div>
                <div class="row">
                    <div class="container">
                        <div class="mb-4">
                            <div class="example-preview custom-nav modal-nav">
                                <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="details-tab-1" data-toggle="tab" href="#details">
                                    <span class="nav-icon">
                                        <i class="flaticon2-chat-1"></i>
                                    </span>
                                            <span class="nav-text">{{ __('dashboard.request_details') }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="contact-tab-1" data-toggle="tab" href="#contact-1"
                                           aria-controls="contact">
                                    <span class="nav-icon">
                                        <i class="flaticon2-layers-1"></i>
                                    </span>
                                            <span class="nav-text">{{ __('dashboard.material_details') }}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="transporter-tab-1" data-toggle="tab" href="#transporter-1"
                                           aria-controls="transporter">
                                    <span class="nav-icon">
                                        <i class="flaticon2-user"></i>
                                    </span>
                                            <span class="nav-text">{{ __('dashboard.transporter_details') }}</span>
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
                                        @include('dashboard.tasks.tabs.material.request_details')
                                    </div>
                                    <div class="tab-pane fade" id="contact-1" role="tabpanel"
                                         aria-labelledby="contact-tab-1">
                                        @include('dashboard.tasks.tabs.material.material_details')
                                    </div>
                                    <div class="tab-pane fade" id="transporter-1" role="tabpanel"
                                         aria-labelledby="transporter-tab-1">
                                        @include('dashboard.tasks.tabs.material.transporter_details')
                                    </div>
                                    <div class="tab-pane fade" id="action-1" role="tabpanel"
                                         aria-labelledby="action-tab-1">
                                        @include('dashboard.tasks.tabs.material.action')
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
