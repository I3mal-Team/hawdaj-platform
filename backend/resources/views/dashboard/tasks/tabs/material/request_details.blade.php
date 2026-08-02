<div>
{{--    <h3 class="text-muted text-center">--}}
{{--        {{ __("dashboard.".$materialRequest->type) }}--}}
{{--    </h3>--}}
    <div class="card card-custom card-shadowless gutter-b mb-2">
        <div class="card-body">
            <div class="card modal-card card-custom card-shadowless gutter-b mb-2">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">{{__('dashboard.request_details')}}</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row ">
                        <!-- first row -->
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.requestId')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$materialRequest->id}}</p>
                        </div>
                        <div class="col-3 mb-3">
                            <b class="text-muted">{{__('dashboard.requester')}}</b>
                        </div>
                        <div class="col-3 mb-3">
                            <p>{{$materialRequest->host->first_name}}  {{$materialRequest->host->last_name}}</p>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12 mb-4">
                                            <strong class="text-muted">
                                                {{ __('dashboard.sender') }}
                                            </strong>
                                        </div>
                                        @if($materialRequest->type == 'between_sites')
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <b class="text-muted">{{__('dashboard.site')}}</b>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <p>{{optional($materialRequest->sender_site)->name}}</p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <b class="text-muted">{{__('dashboard.employee')}}</b>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <p>{{optional($materialRequest->sender_host)->full_name??'---'}}</p>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <b class="text-muted">{{__('dashboard.department')}}</b>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <p>{{optional($materialRequest->sender_department)->name}}</p>
                                                </div>
                                            </div>
                                        @elseif($materialRequest->type == 'personal_request')
                                        @else

                                                <div class="col-6 mb-3">
                                                    <b class="text-muted">{{__('dashboard.company')}}</b>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <p>{{$materialRequest->company}}</p>
                                                </div>


                                                <div class="col-6 mb-3">
                                                    <b class="text-muted">{{__('dashboard.contact_person_name')}}</b>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <p>{{$materialRequest->contact_person}}</p>
                                                </div>


                                                <div class="col-6 mb-3">
                                                    <b class="text-muted">{{__('dashboard.phone')}}</b>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <p>{{$materialRequest->phone}}</p>
                                                </div>

                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12 mb-4">
                                            <strong class="text-muted">
                                                {{ __('dashboard.receiver') }}
                                            </strong>
                                        </div>
                                        <div class="col-6">
                                            <b class="text-muted">{{ __('dashboard.site') }}</b>
                                        </div>
                                        <div class="col-6"><p>{{ $materialRequest->site->name }}</p></div>
                                        <div class="col-6">
                                            <b class="text-muted">{{ __('dashboard.employee') }}</b>
                                        </div>
                                        <div class="col-6"><p>{{__('dashboard.employee_name')}}</p></div>
                                        <div class="col-6">
                                            <b class="text-muted">{{ __('dashboard.department') }}</b>
                                        </div>
                                        <div class="col-6"><p>{{ $materialRequest->department }}</p></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- end first row -->
                    </div>
                </div>
            </div>
            <div class="card modal-card card-custom card-shadowless gutter-b mb-2">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">
                            {{__('dashboard.ex_date')}}
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(in_array($materialRequest->type ,['inward_non-returnable','personal_request']))
                            <div class="col-3 mb-3">
                                <b class="text-muted">{{ __('dashboard.expected_delivery_date') }}</b>
                            </div>
                            <div class="col-3 mb-3">
                                <p>{{$materialRequest->delivery_date}}</p>
                            </div>
                        @elseif($materialRequest->type == 'inward_returnable')
                            <div class="col-3 mb-3">
                                <b class="text-muted">{{ __('dashboard.expected_delivery_date') }}</b>
                            </div>
                            <div class="col-3 mb-3">
                                <p>{{$materialRequest->delivery_date}}</p>
                            </div>
                            <div class="col-3 mb-3">
                                <b class="text-muted">{{ __('dashboard.expected_return_date') }}</b>
                            </div>
                            <div class="col-3 mb-3">
                                <p>{{$materialRequest->return_date}}</p>
                            </div>
                        @elseif($materialRequest->type == 'outward_non-returnable')
                            <div class="col-3 mb-3">
                                <b class="text-muted">{{ __('dashboard.expected_dispatch_date') }}</b>
                            </div>
                            <div class="col-3 mb-3">
                                <p>{{$materialRequest->dispatch_date}}</p>
                            </div>
                        @elseif($materialRequest->type == 'outward_returnable')
                            <div class="col-3 mb-3">
                                <b class="text-muted">{{ __('dashboard.expected_dispatch_date') }}</b>
                            </div>
                            <div class="col-3 mb-3">
                                <p>{{$materialRequest->dispatch_date}}</p>
                            </div>
                            <div class="col-3 mb-3">
                                <b class="text-muted">{{ __('dashboard.expected_return_date') }}</b>
                            </div>
                            <div class="col-3 mb-3">
                                <p>{{$materialRequest->return_date}}</p>
                            </div>
                        @elseif($materialRequest->type == 'between_sites')
                            <div class="col-3 mb-3">
                                <b class="text-muted">{{ __('dashboard.expected_dispatch_date') }}</b>
                            </div>
                            <div class="col-3 mb-3">
                                <p>{{$materialRequest->dispatch_date}}</p>
                            </div>
                            <div class="col-3 mb-3">
                                <b class="text-muted">{{ __('dashboard.expected_return_date') }}</b>
                            </div>
                            <div class="col-3 mb-3">
                                <p>{{$materialRequest->return_date}}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
