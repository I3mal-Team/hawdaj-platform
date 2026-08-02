<div class=" ">
    <!-- search bar -->
    <form action="{{ route('dashboard.tasks') }}" method="get">
        <input type="hidden" name="type" value="{{ $type }}" id="">
        <div class="row mb-5">
            <div class="col-md-4 col-sm-6">
                <label for="">{{ __('dashboard.search') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i
                                class="flaticon-search icon-lg"></i></span>
                    </div>
                    <input type="text" value="@if(isset($filter['text'])){{$filter['text']}}@endif" class="form-control"
                           name="text"
                           placeholder="{{ __('dashboard.search') }}"/>
                </div>
            </div>
            <div class="col-md-7 col-sm-6">
                <div class="row">
                    <div class="col-4">
                        <label for="">{{ __('dashboard.from_date') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="flaticon-calendar-with-a-clock-time-tools icon-lg"></i>
                            </span>
                            </div>
                            <input type="date" name="date_from"
                                   value="@if(isset($filter['date_from'])){{$filter['date_from']}}@endif"
                                   class="form-control"/>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="">{{ __('dashboard.to_date') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="flaticon-calendar-with-a-clock-time-tools icon-lg"></i>
                            </span>
                            </div>
                            <input type="date" name="date_to"
                                   value="@if(isset($filter['date_to'])){{$filter['date_to']}}@endif"
                                   class="form-control"/>
                        </div>
                    </div>
                    <div class="col-4">
                        <label for="">{{ __('dashboard.status') }}</label>
                        <div class="input-group">
                            <select class="nice-select form-control row" name="status">
                                <option value=""
                                        @if(!isset($filter['status'])) selected @endif>{{ __('dashboard.status') }}</option>
                                <option value="in_progress"
                                        @if(isset($filter['status']) && $filter['status'] == 'in_progress') selected @endif
                                ">{{ __('dashboard.in_progress') }}</option>
                                <option value="confirmed"
                                        @if(isset($filter['status']) && $filter['status'] == 'confirmed') selected @endif
                                ">{{ __('dashboard.confirmed') }}</option>
                                <option value="approve"
                                        @if(isset($filter['status']) && $filter['status'] == 'approve') selected @endif>{{ __('dashboard.approved') }}</option>
                                <option value="reject"
                                        @if(isset($filter['status']) && $filter['status'] == 'reject') selected @endif>{{ __('dashboard.rejected') }}</option>
                                <option value="monitor"
                                        @if(isset($filter['status']) && $filter['status'] == 'monitor') selected @endif>{{ __('dashboard.monitor') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <button type="submit" class="mt-7 btn btn-success">@lang('dashboard.search')</button>
            </div>
        </div>
    </form>
    @if($tasks->count() > 0)
        @foreach($tasks as $car)
            <div class="card card-custom  task-card gutter-b">
                <div class="card-body">
                    <!--begin::Top-->
                    <div class="d-flex">
                        <div class="flex-grow-1 task-details-card">
                            <div class="mt-2">
                                <div class="row ">
                                    <div class="col-md-4 col-sm-12" >
                                        <!--begin::Title-->
                                        {{--                                        <div class="">--}}
                                        {{--                                            <!--begin::Name-->--}}
                                        {{--                                            <a href="#"--}}
                                        {{--                                                class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">--}}
                                        {{--                                                {{ __("dashboard.$car->type") }}--}}
                                        {{--                                                <i class="questionIcon flaticon2-correct text-success icon-md ml-2"></i></a>--}}
                                        {{--                                            <!--end::Name-->--}}
                                        {{--                                        </div>--}}
                                        <!--end::Title-->
                                        <!--begin::Content-->
                                        <div class="">
                                            <!--begin::Description-->
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.driver') }}: </strong>
                                                <span class="c-details">{{ $car->driver->contact_person_name ?? '' }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.delivery_date') }}: </strong>
                                                <span class="c-details">{{ $car->delivery_date }}</span>
                                            </div>
                                            <!--end::Description-->
                                        </div>
                                        <!--end::Content-->
                                    </div>
{{--                                    <div class="vl"></div>--}}
                                    <div class="col-md-4 col-sm-12" >
                                        <!--begin::Title-->
                                        <div class="mb-2">
                                            <strong class="text-muted c-label">{{ __('dashboard.request_id') }}</b>: </strong>
                                            <span class="c-details">{{ $car->id }}</span>
                                            <!--begin::Name-->
{{--                                            <a href="#"--}}
{{--                                               class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3"><a--}}
{{--                                                    href="#"><b>{{ __('dashboard.car') }}</b>: </a>--}}
{{--                                                <span class="c-details">{{ $car->id }}</span>--}}
{{--                                                <i class="questionIcon flaticon2-notification text-success icon-md ml-2"></i></a>--}}
                                            <!--end::Name-->
                                            <!--begin::Contacts-->
                                            <div class="">
                                            </div>
                                            <!--end::Contacts-->
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Content-->
                                        <div class="">
                                            <!--begin::Description-->
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.receiver') }}: </strong>
                                                <span class="c-details">{{ $car->host->full_name }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.sender') }}: </strong>
                                                <span class="c-details">{{ $car->requester->full_name }}</span>
                                            </div>
                                            <!--end::Description-->
                                            <!--begin::Progress-->
                                            <!--end::Progress-->
                                        </div>
                                        <!--end::Content-->
                                    </div>
{{--                                    <div class="vl"></div>--}}
                                    <div class="col-md-4 col-sm-12" >
                                        <!--begin::Title-->

                                        <!--end::Title-->
                                        <!--begin::Content-->
                                        <div class="">
                                            <!--begin::Description-->
                                            <div class="">
                                                <strong class="text-muted c-label">{{ __('dashboard.request_date') }}: </strong>
                                                <span class="c-details">{{ $car->created_at }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.requester') }}: </strong>
                                                <span class="c-details">{{ $car->requester->full_name }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.status') }}: </strong>
                                                <span class="badge badge-success">{{ handleTrans($car['status'])}}</span>
                                            </div>
                                            <!--end::Description-->
                                            <!--begin::Progress-->
                                            <!--end::Progress-->
                                        </div>
                                        <!--end::Content-->
                                        <div class="mt-2">
                                            <!--begin::Name-->
                                            <a type="button" id="CarsModalActive" data-id="{{ $car->id }}"
                                               class="btn CarsModalActive btn-outline-success">{{ __('dashboard.take_action') }}</a>
                                            <!--end::Name-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Top-->


                </div>
            </div>
        @endforeach
        <div class="pagination-cont">
            <div class="d-flex flex-wrap py-2 mr-3 justify-content-center">
                {{$tasks->links("pagination::bootstrap-4")}}
            </div>
        </div>
    @else
        @include('dashboard.includes._no-data-found')
    @endif
</div>
