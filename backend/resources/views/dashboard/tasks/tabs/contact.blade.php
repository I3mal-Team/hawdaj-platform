<div>
    <div class="card card-custom gutter-b mb-2 card-shadowless">
        <div class="card-body">
            <div class="card modal-card card-custom gutter-b mb-2 card-shadowless">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">{{__('dashboard.contact_details')}}</h3>
                        <br>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <!-- first row -->
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.name')}}</b>
                        </div>
                        <div class="col-6 mb-3">
                            <p>{{$task->visitor->fullname??"---"}}</p>
                        </div>
                        <div class="col-md-3"></div>
                        <!-- end first row -->
                        <!--  row 2-->
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.id_type')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->visitor->id_type??"---"}}</p>
                        </div>
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.mobile')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->visitor->mobile??"---"}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card modal-card card-custom gutter-b mb-2 card-shadowless">
                <div class="card-header">
                    <h4 class="mb-3 card-label">{{__('dashboard.other_info')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.nationally')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$task->visitor->nationality??"---"}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.company')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$task->visitor->company->name??"---"}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.gender')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$task->visitor->gender??"---"}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.position')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$task->visitor->position??"---"}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-3 card-label">{{__('dashboard.id_copy')}}</h4>
                            <br>
                            <div>
                                <img src="{{resolvePhoto($task->visitor->id_copy,'none')}}" style="width: 25%; border: 1px solid #EEE"
                                     alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
