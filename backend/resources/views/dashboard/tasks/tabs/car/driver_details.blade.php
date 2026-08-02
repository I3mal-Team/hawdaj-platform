<div>
{{--    <h3 class="text-muted text-center">--}}
{{--        {{ __("dashboard.".$materialRequest->type) }}--}}
{{--    </h3>--}}
    <div class="card card-custom card-shadowless gutter-b mb-2">
        <div class="card-body">
            <div class="card modal-card card-custom card-shadowless gutter-b mb-2">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">{{__('dashboard.driver_details')}}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.contact_person_name')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->driver->contact_person_name}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.id_number')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->driver->id_number}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.email')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->driver->email}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.phone')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->driver->phone}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.vehicle_details')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->driver->vehicle_details}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.remarks')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->driver->remarks}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
