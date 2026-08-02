<div>
    <div class="card card-custom gutter-b mb-2 card-shadowless">
        <div class="card-body main-card-body-custom">
            <div class="row ">
                <div class="col-12">
                    <div class="card modal-card card-custom gutter-b mb-2 card-shadowless">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="card-label">{{__('dashboard.schedule')}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.date')}}</b>
                                </div>
                                <div class="col-3">
                                    {{dateFormat($visitorReq->visitRequest->from_date)}}
                                </div>
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.time')}}</b>
                                </div>
                                <div class="col-3">
                                    {{timeFormat($visitorReq->visitRequest->from_fromtime)}}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-12">
                    <div class="card modal-card card-custom gutter-b mb-2 card-shadowless">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="card-label">{{__('dashboard.request_details')}}</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                <!-- first row -->
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.requestId')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$visitorReq->visitRequest->id??"---"}}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.host')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$visitorReq->visitRequest->host->fullname??"---"}}</p>
                                </div>
                                <!-- end first row -->
                                <!--  row 2-->
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.host')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$visitorReq->visitRequest->host->fullname??"---"}}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.department')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$visitorReq->visitRequest->department->name??"---"}}</p>
                                </div>
                                <!-- row 2 -->
                                <!--  row 3 -->
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.visit_type')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$visitorReq->visitRequest->visitType->name??"---"}}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.visit_reason')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$visitorReq->visitRequest->reason->reason??"---"}}</p>
                                </div>
                                <!-- row 3 -->
                                <!--  row 4 -->
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.purpose_description')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$visitorReq->visitRequest->description??"---"}}</p>
                                </div>
                                <!-- row 4 -->
                                <!--  row 5 -->
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.vehicle_detail')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$visitorReq->visitor->vehicle_detail??"---"}}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.materials')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$visitorReq->visitor->vehicle_material??"---"}}</p>
                                </div>
                                <!-- row 5 -->
                                <!--  row 6 -->
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.remarks')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$visitorReq->visitor->vehicle_remark??"---"}}</p>
                                </div>
                                <!-- row 6 -->
                                <!--  row 6 -->
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.comment')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$visitorReq->visitRequest->comment??"---"}}</p>
                                </div>
                                <!-- row 6 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
