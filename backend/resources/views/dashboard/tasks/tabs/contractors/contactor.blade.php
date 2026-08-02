<div>
    <div class="card card-custom gutter-b mb-2 card-shadowless">
    <div class="card-body">
        <div class="card modal-card card-custom gutter-b mb-2 card-shadowless">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">{{__('dashboard.contractor_details')}}</h3>
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
                        <p>{{$task->contractor->fullname??"---"}}</p>
                    </div>
                    <div class="col-md-3"></div>
                    <!-- end first row -->
                    <!--  row 2-->
                    <div class="col-3 mb-3">
                        <b class="text-muted">{{__('dashboard.id_type')}}</b>
                    </div>
                    <div class="col-3 mb-3">
                        <p>{{$task->contractor->id_type??"---"}}</p>
                    </div>
                    <div class="col-3 mb-3">
                        <b class="text-muted">{{__('dashboard.mobile')}}</b>
                    </div>
                    <div class="col-3 mb-3">
                        <p>{{$task->contractor->mobile??"---"}}</p>
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
                                <p>{{$task->contractor->nationality??"---"}}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <b class="text-muted">{{__('dashboard.company')}}</b>
                            </div>
                            <div class="col-6 mb-3">
                                <p>{{$task->contractor->company->name??"---"}}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <b class="text-muted">{{__('dashboard.gender')}}</b>
                            </div>
                            <div class="col-6 mb-3">
                                <p>{{$task->contractor->gender??"---"}}</p>
                            </div>
                            <div class="col-6 mb-3">
                                <b class="text-muted">{{__('dashboard.position')}}</b>
                            </div>
                            <div class="col-6 mb-3">
                                <p>{{$task->contractor->position??"---"}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="mb-3 card-label">{{__('dashboard.id_copy')}}</h4>
                        <br>
                        <div>
                            <img src="{{resolvePhoto($task->contractor->id_copy,'none')}}" style="width: 25%; border: 1px solid #EEE"
                                 alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


</div>
