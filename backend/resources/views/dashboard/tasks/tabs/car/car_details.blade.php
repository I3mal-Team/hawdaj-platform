<div>
{{--    <h3 class="text-muted text-center">--}}
{{--        {{ __("dashboard.".$materialRequest->type) }}--}}
{{--    </h3>--}}
    <div class="card card-custom card-shadowless gutter-b mb-2">
        <div class="card-body">
            <div class="card modal-card card-custom card-shadowless gutter-b mb-2">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">{{__('dashboard.car_details')}}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.plate_ar')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->car->plate_ar}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.plate_en')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->car->plate_en}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.licence')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->car->licence}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.type')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->car->type}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <b class="text-muted">{{__('dashboard.description')}}</b>
                                </div>
                                <div class="col-6 mb-3">
                                    <p>{{$carRequest->car->description}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
