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
    @if($tasks->count()>0)
        @foreach($tasks as $task)
            <div class="card card-custom task-card gutter-b">
                <div class="card-body">
                    <div class="d-flex">
                        <!--begin::Pic-->
                        <div class="flex-shrink-0 ">
                            <div class="symbo" style="width: 145px;height: 112px;">
                                <img alt="Pic" style="width: 100%; height: 100%"
                                     src="{{asset('dashboard_assets/media/avatar.png')}}"/>
                            </div>
                        </div>
                        <!--end::Pic-->
                        <!--begin: Info-->
                        <div class="flex-grow-1 task-details-card">
                            <div class="mt-2">
                                <div class="row ">
                                    <div class="col-md-4 col-sm-12" style="margin:auto">
                                        <!--begin::Title-->
                                        <div class="">
                                            <!--begin::Name-->
                                            <a href="#"
                                               class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">
                                                {{ $task['contractor']['first_name'].' '.$task['contractor']['last_name'] }}

                                            </a>
                                            <!--end::Name-->
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Content-->
                                        <div class="">
                                            <!--begin::Description-->
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.company') }}: </strong>
                                                <span class="c-details">{{ $task['contractor']['company']['name']}}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.nationally') }}: </strong>
                                                <span class="c-details">{{ $task['contractor']['nationality'] }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.idednty_no') }}: </strong>
                                                <span class="c-details">{{ $task['contractor']['id_number'] }}</span>
                                            </div>
                                            <!--end::Description-->
                                            <!--begin::Progress-->
                                            <!--end::Progress-->
                                        </div>
                                        <!--end::Content-->
                                    </div>
{{--                                    <div class="vl"></div>--}}
                                    <div class="col-md-4 col-sm-12" style="margin:auto">
                                        <!--begin::Title-->
                                        <div class="mb-2">
                                            <!--begin::Name-->
                                            <strong class="text-muted c-label">{{ __('dashboard.request_id') }}: </strong>
                                            <span class="c-details">{{ $task['contractRequest']['id'] }}</span>
{{--                                            <a href="#"--}}
{{--                                               class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">--}}
{{--                                                <a href="#">--}}
{{--                                                    <b>{{ __('dashboard.visit') }}</b>:--}}
{{--                                                </a>--}}
{{--                                                <span class="c-details">{{ $task['contractRequest']['id'] }}</span>--}}
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
                                                <strong class="text-muted c-label">{{ __('dashboard.visit_from') }}: </strong>
                                                <span class="c-details">{{ $task['contractRequest']['from_date'] }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.visit_to') }}: </strong>
                                                <span class="c-details">{{ $task['contractRequest']['to_date'] }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.host') }}: </strong>
                                                <span class="c-details">{{ $task['contractRequest']['contract_manager']['first_name'] }}</span>
                                            </div>
                                            <!--end::Description-->
                                            <!--begin::Progress-->
                                            <!--end::Progress-->
                                        </div>
                                        <!--end::Content-->
                                    </div>
{{--                                    <div class="vl"></div>--}}
                                    <div class="col-md-4 col-sm-12" style="margin:auto">
                                        <!--begin::Title-->

                                        <!--end::Title-->
                                        <!--begin::Content-->
                                        <div class="">
                                            <!--begin::Description-->
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.request_date') }}: </strong>
                                                <span class="c-details">{{ $task['created_at'] }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.contractor') }}: </strong>
                                                <span class="c-details">{{ $task['contractor']['first_name'] }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.status') }}: </strong>
                                                @if($task['status'] == null)
                                                    <span
                                                        class="badge badge-success">@lang('dashboard.in_progress')</span>
                                                @else
                                                    <span
                                                        class="badge badge-success">{{ handleTrans($task['status'])}}</span>
                                                @endif
                                            </div>
                                            <!--end::Description-->
                                            <!--begin::Progress-->
                                            <!--end::Progress-->
                                        </div>
                                        <!--end::Content-->
                                        <div class="mt-2">
                                            <!--begin::Name-->
                                            <a type="button" id="contractorsModalActive" data-id="{{ $task['id'] }}"
                                               class="btn  btn-outline-success">{{ __('dashboard.take_action') }}
                                            </a>
                                            <!--end::Name-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
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
