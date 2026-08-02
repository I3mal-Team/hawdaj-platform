<div class="row">
    <div class="col-md-2 col-lg-2 col-sm-4">
        <div class="row justify-content-md-center">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="rounded p-10">
                    <div class="example-preview">
                        <ul class="nav flex-column" style="width: 175px">
                            <li class="nav-item">
                                <a class="nav-link active" id="rights-tab-1" data-toggle="tab" href="#rights-1">
																	<span class="nav-icon">
																		<i class="flaticon2-chat-1"></i>
																	</span>
                                    <span class="nav-text">{{ __('dashboard.rights') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="department-tab-1" data-toggle="tab" href="#department-1">
																	<span class="nav-icon">
																		<i class="flaticon2-layers-1"></i>
																	</span>
                                    <span class="nav-text">{{ __('dashboard.departments') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="sites-tab-1" data-toggle="tab" href="#sites-1">
																	<span class="nav-icon">
																		<i class="flaticon2-layers-1"></i>
																	</span>
                                    <span class="nav-text">{{ __('dashboard.sites') }}</span>
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
                        <div class="tab-pane fade show active" id="rights-1" role="tabpanel" aria-labelledby="rights-tab-1">
                            <!-- first tab form -->
                        @include('dashboard.settings.sites.rights')
                        <!-- end first tab form -->
                        </div>
                        <div class="tab-pane fade show" id="department-1" role="tabpanel" aria-labelledby="department-tab-1">
                            <!-- first tab form -->
                        @include('dashboard.settings.sites.department')
                        <!-- end first tab form -->
                        </div>
                        <div class="tab-pane fade show" id="sites-1" role="tabpanel" aria-labelledby="sites-tab-1">
                            <!-- first tab form -->
                        @include('dashboard.settings.sites.sites')
                        <!-- end first tab form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
