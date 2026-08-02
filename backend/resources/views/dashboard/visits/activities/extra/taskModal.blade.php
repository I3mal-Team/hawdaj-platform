<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('dashboard.tasks') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>
        <div class="modal-body" style="height: 100vh;">
            <div class="row">
                <div class="container-fluid">
                    <div class="custom-nav modal-nav">
                        <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="details-tab-1" data-toggle="tab" href="#details">
                                    <span class="nav-icon">
                                                <i class="flaticon2-user"></i>
                                            </span>
                                    <span class="nav-text">{{ __('dashboard.visit_details') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab-1" data-toggle="tab" href="#contact-1"
                                   aria-controls="contact">
                                    <span class="nav-icon">
{{--                                                <i class="flaticon2-user"></i>--}}
                                            <i class="fa fa-address-book" aria-hidden="true"></i>
                                            </span>
                                    <span class="nav-text">{{ __('dashboard.contact_details') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="histroy-tab-1" data-toggle="tab" href="#histroy-1"
                                   aria-controls="contact">
                                    <span class="nav-icon">
                                                <i class="fa fa-history" aria-hidden="true"></i>
                                            </span>
                                    <span class="nav-text">{{ __('dashboard.histroy') }}</span>
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
                    </div>

                    <div class="tab-content " id="myTabContent1">
                        <div class="tab-pane fade show active" id="details" role="tabpanel"
                             aria-labelledby="details-tab-1">
                            @include('dashboard.visits.activities.tabs.details')
                        </div>
                        <div class="tab-pane fade" id="contact-1" role="tabpanel"
                             aria-labelledby="contact-tab-1">
                            @include('dashboard.visits.activities.tabs.contact')
                        </div>
                        <div class="tab-pane fade" id="histroy-1" role="tabpanel"
                             aria-labelledby="contact-tab-1">
                            @include('dashboard.visits.activities.tabs.histroy')
                        </div>
                        <div class="tab-pane fade" id="action-1" role="tabpanel"
                             aria-labelledby="action-tab-1">
                            @include('dashboard.visits.activities.tabs.action')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
