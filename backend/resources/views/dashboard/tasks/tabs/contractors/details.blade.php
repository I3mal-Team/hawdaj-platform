<div>
    <div class="card card-custom gutter-b mb-2 card-shadowless">
        <div class="card-body">
            <div class="card modal-card card-custom gutter-b mb-2 card-shadowless">
                <div class="card-header">
                    <div class="card-title">
                        <h4 class="card-label">{{__('dashboard.schedule')}}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.from_date')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{dateFormat($task->contractRequest->from_date)}} {{timeFormat($task->contractRequest->from_date)}}</p>
                        </div>
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.to_date')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{dateFormat($task->contractRequest->to_date)}} {{timeFormat($task->contractRequest->to_date)}}</p>
                        </div>
                        {{--                <div class="col-6">--}}
                        {{--                    {{dateFormat($task->contractRequest->from_date)}}--}}
                        {{--                </div>--}}
                        {{--                <div class="col-6">--}}
                        {{--                    {{timeFormat($task->contractRequest->to_date)}}--}}
                        {{--                </div>--}}
                    </div>
                </div>
            </div>
            <div class="card modal-card card-custom gutter-b mb-2 card-shadowless">
                <div class="card-header">
                    <div class="card-title">
                        <h4 class="card-label">{{__('dashboard.permisson_request')}}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <!-- first row -->
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.requestId')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->contractRequest->id??"---"}}</p>
                        </div>
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.contract_manager')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->contractRequest->contract_manager->fullname??"---"}}</p>
                        </div>
                        <!-- end first row -->
                        <!--  row 2-->
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.department')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->contractRequest->contract->department->name??"---"}}</p>
                        </div>
                        <!-- row 2 -->
                        <!--  row 3 -->
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.contract_type')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->contractRequest->contract->contract_type->name??"---"}}</p>
                        </div>
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.company')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->contractRequest->company->name??"---"}}</p>
                        </div>
                        <!-- row 3 -->
                        <!--  row 4 -->
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.vehicle_detail')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->contractor->vehicle_detail??"---"}}</p>
                        </div>
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.materials')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->contractor->vehicle_material??"---"}}</p>
                        </div>
                        <!-- row 4 -->
                        <!--  row 5 -->
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.remarks')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$task->contractor->vehicle_remark??"---"}}</p>
                        </div>
                        <!-- row 5 -->
                        <!--  row 6 -->
                        <div class="col-6 mb-3">
                            <b class="text-muted">{{__('dashboard.comment')}}</b>
                        </div>
                        <div class="col-6 mb-3">
                            <p>{{$task->contractRequest->notes??"---"}}</p>
                        </div>
                        <!-- row 6 -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
