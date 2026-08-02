<div>
{{--    <h3 class="text-muted text-center">--}}
{{--        {{ __("dashboard.".$carRequest->type) }}--}}
{{--    </h3>--}}
    <div class="card card-custom gutter-b mb-2">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">{{__('dashboard.permisson_request')}}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row container">
                <!-- first row -->
                <div class="col-3 mb-5">
                    <b class="text-muted">{{__('dashboard.requestId')}}</b>
                </div>
                <div class="col-3 mb-5">
                    <p>{{$carRequest->id}}</p>
                </div>
                <div class="col-3 mb-5">
                    <b class="text-muted">{{__('dashboard.requester')}}</b>
                </div>
                <div class="col-3 mb-5">
                    <p>{{$carRequest->host->first_name}}  {{$carRequest->host->last_name}}</p>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                            <div class="col-12 mb-4">
                                    <strong>
                                        {{ __('dashboard.sender') }}
                                    </strong>
                                </div>
                                <div class="col-6">
                                    <b class="text-muted">{{ __('dashboard.name') }}</b>
                                </div>
                                <div class="col-6"><p>{{ $carRequest->requester->full_name }}</p></div>
                                <div class="col-6">
                                    <b class="text-muted">{{ __('dashboard.email') }}</b>
                                </div>
                                <div class="col-6"><p>{{ $carRequest->requester->email }}</p></div>
                                <div class="col-6">
                                    <b class="text-muted">{{ __('dashboard.phone') }}</b>
                                </div>
                                <div class="col-6"><p>{{ $carRequest->requester->phone }}</p></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <strong>
                                        {{ __('dashboard.receiver') }}
                                    </strong>
                                </div>
                                <div class="col-6">
                                    <b class="text-muted">{{ __('dashboard.site') }}</b>
                                </div>
                                <div class="col-6"><p>{{ $carRequest->site->name }}</p></div>
                                <div class="col-6">
                                    <b class="text-muted">{{ __('dashboard.employee') }}</b>
                                </div>
                                <div class="col-6"><p>{{ $carRequest->host->full_name }}</p></div>
                                <div class="col-6">
                                    <b class="text-muted">{{ __('dashboard.department') }}</b>
                                </div>
                                <div class="col-6"><p>{{ $carRequest->department->name }}</p></div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="col-12 mb-4 mt-4">--}}
{{--                    <h3 class="text-muted">--}}
{{--                        {{__('dashboard.expacted_date')}}--}}
{{--                    </h3>--}}
{{--                </div>--}}
{{--                <div class="col-3 mb-5">--}}
{{--                    <b class="text-muted">{{ __('dashboard.delivery_date') }}</b>--}}
{{--                </div>--}}
{{--                <div class="col-3 mb-5">--}}
{{--                    <p>{{$carRequest->delivery_date}}</p>--}}
{{--                </div>--}}
                <!-- end first row -->
            </div>
        </div>
    </div>
</div>
