<div class="row">
    <div class="col-md-2 col-lg-2 col-sm-4">
        <div class="row justify-content-md-center">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="rounded p-10">
                    <div class="example-preview">
                        <ul class="nav flex-column" style="width: 175px">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab-1" data-toggle="tab" href="#generalTab-1">
																	<span class="nav-icon">
																		<i class="flaticon2-chat-1"></i>
																	</span>
                                    <span class="nav-text">{{ __('dashboard.general_setting') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="healthCheck-tab-1" data-toggle="tab" href="#healthCheck-1">
																	<span class="nav-icon">
																		<i class="flaticon2-layers-1"></i>
																	</span>
                                    <span class="nav-text">{{ __('dashboard.healthChecks') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="notification-tab-1" data-toggle="tab" href="#notification-1">
																	<span class="nav-icon">
																		<i class="flaticon2-layers-1"></i>
																	</span>
                                    <span class="nav-text">{{ __('dashboard.notification') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-10 col-lg-10 col-sm-10">
        <div class="row justify-content-md-center">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="bg-white rounded p-10">
                    <div class="tab-content mt-5" id="myTabContent1">
                        <div class="tab-pane fade show active" id="generalTab-1" role="tabpanel" aria-labelledby="general-tab-1">
                            <!-- first tab form -->
                        @include('dashboard.settings.general.general')
                        <!-- end first tab form -->
                        </div>
                        <div class="tab-pane fade show" id="healthCheck-1" role="tabpanel" aria-labelledby="healthCheck-tab-1">
                            <!-- first tab form -->
                        @include('dashboard.settings.general.healthCheck')
                        <!-- end first tab form -->
                        </div>
                        <div class="tab-pane fade show" id="notification-1" role="tabpanel" aria-labelledby="notification-tab-1">
                            <!-- first tab form -->
                            sadsa
                        @include('dashboard.settings.general.notification')
                        <!-- end first tab form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
