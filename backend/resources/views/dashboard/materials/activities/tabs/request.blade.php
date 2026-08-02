<div>
    <div class="card card-custom gutter-b mb-2 card-shadowless">
{{--        <div class="card-header">--}}
{{--            <div class="card-title">--}}
{{--                <h4 class="card-label">{{__('dashboard.request_details')}}</h4>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="card-body main-card-body-custom">
            <div class="row ">
                <div class="col-12">
                    <div class="card card-custom modal-card gutter-b mb-2 card-shadowless">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h4 class="card-label">{{__('dashboard.request_details')}}</h4>
                                    </div>
                                </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.pid')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$request->id}}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.req_date')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{dateFormat($request->created_at)}}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.requester')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$request->requester->fullname}}</p>
                                </div>
                                <div class="col-3 mb-3">
                                    <b class="text-muted">{{__('dashboard.status')}}</b>
                                </div>
                                <div class="col-3 mb-3">
                                    <p>{{$request->status}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">

                    <div class="card card-custom modal-card gutter-b mb-2 card-shadowless">
                        <div class="card-header ">
                            <div class="card-title">
                                <h3 class="card-label">{{__('dashboard.sender')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($request->type == 'between_sites')
                                <div class="row">
                                    <div class="col-6 mb-5">
                                        <b class="text-muted">{{__('dashboard.site')}}</b>
                                    </div>
                                    <div class="col-6 mb-5">
                                        <p>{{optional($request->sender_site)->name}}</p>
                                    </div>
                                    <div class="col-6 mb-5">
                                        <b class="text-muted">{{__('dashboard.employee')}}</b>
                                    </div>
                                    <div class="col-6 mb-5">
                                        <p>{{optional($request->sender_host)->full_name??' ---'}}</p>
                                    </div>
                                    <div class="col-6 mb-5">
                                        <b class="text-muted">{{__('dashboard.department')}}</b>
                                    </div>
                                    <div class="col-6 mb-5">
                                        <p>{{optional($request->sender_department)->name}}</p>
                                    </div>
                                </div>
                            @elseif($request->type == 'personal_request')
                            @else
                                <div class="row">
                                    <div class="col mb-3">
                                        <b class="text-muted">{{__('dashboard.company')}}</b>
                                    </div>
                                    <div class="col mb-3">
                                        <p>{{$request->company}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <b class="text-muted">{{__('dashboard.contact_person_name')}}</b>
                                    </div>
                                    <div class="col mb-3">
                                        <p>{{$request->contact_person}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <b class="text-muted">{{__('dashboard.phone')}}</b>
                                    </div>
                                    <div class="col mb-3">
                                        <p>{{$request->phone}}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card card-custom modal-card gutter-b mb-2 card-shadowless">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">{{__('dashboard.receiver')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                <div class="col mb-3">
                                    <b class="text-muted">{{__('dashboard.site')}}</b>
                                </div>
                                <div class="col mb-3">
                                    <p>{{$request->site->name}}</p>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col mb-3">
                                    <b class="text-muted">{{__('dashboard.employee')}}</b>
                                </div>
                                <div class="col mb-3">
                                    <p>{{$request->host->full_name}}</p>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col mb-3">
                                    <b class="text-muted">{{__('dashboard.department')}}</b>
                                </div>
                                <div class="col mb-3">
                                    <p>{{$request->department}}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-custom modal-card gutter-b mb-2 card-shadowless">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">{{__('dashboard.ex_date')}}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row ">
                                @if(in_array($request->type ,['inward_non-returnable','personal_request']))
                                    <div class="col-3 mb-5">
                                        <b class="text-muted">{{ __('dashboard.expected_delivery_date') }}</b>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <p>{{$request->delivery_date}}</p>
                                    </div>
                                @elseif($request->type == 'inward_returnable')
                                    <div class="col-3 mb-5">
                                        <b class="text-muted">{{ __('dashboard.expected_delivery_date') }}</b>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <p>{{$request->delivery_date}}</p>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <b class="text-muted">{{ __('dashboard.expected_return_date') }}</b>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <p>{{$request->return_date}}</p>
                                    </div>
                                @elseif($request->type == 'outward_non-returnable')
                                    <div class="col-3 mb-5">
                                        <b class="text-muted">{{ __('dashboard.expected_dispatch_date') }}</b>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <p>{{$request->dispatch_date}}</p>
                                    </div>
                                @elseif($request->type == 'outward_returnable')
                                    <div class="col-3 mb-5">
                                        <b class="text-muted">{{ __('dashboard.expected_dispatch_date') }}</b>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <p>{{$request->dispatch_date}}</p>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <b class="text-muted">{{ __('dashboard.expected_return_date') }}</b>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <p>{{$request->return_date}}</p>
                                    </div>
                                @elseif($request->type == 'between_sites')
                                    <div class="col-3 mb-5">
                                        <b class="text-muted">{{ __('dashboard.expected_dispatch_date') }}</b>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <p>{{$request->dispatch_date}}</p>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <b class="text-muted">{{ __('dashboard.expected_delivery_date') }}</b>
                                    </div>
                                    <div class="col-3 mb-5">
                                        <p>{{$request->delivery_date}}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
