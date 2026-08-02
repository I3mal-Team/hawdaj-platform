@if($tasks->count() > 0)
    @foreach($tasks as $material)
        <div class="card card-custom task-card gutter-b">
            <div class="card-body">
                <!--begin::Top-->
                <div class="d-flex">
                    <div class="flex-grow-1 task-details-card">
                        <div class="mt-2">
                            <div class="row mr-3">
                                <div class="col-md-4 col-sm-12" >
                                    <!--begin::Title-->
                                    <div class="">
                                        <!--begin::Name-->
                                        <a href="#"
                                           class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">
                                            {{ handleTrans($material->type) }}
                                            <i class="questionIcon flaticon2-correct text-success icon-md ml-2"></i></a>
                                        <!--end::Name-->
                                    </div>
                                    <!--end::Title-->
                                    <!--begin::Content-->
                                    <div class="">
                                        <!--begin::Description-->
                                        @if( in_array($material->type ,['inward_non-returnable','personal_request']))
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.expected_delivery_date') }}: </strong>
                                                <span class="c-details">{{ $material->delivery_date }}</span>
                                            </div>
                                        @elseif($material->type == 'inward_returnable')
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.expected_delivery_date') }}: </strong>
                                                <span class="c-details">{{ $material->delivery_date }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.expected_return_date') }}: </strong>
                                                <span class="c-details">{{ $material->return_date }}</span>
                                            </div>
                                        @elseif($material->type == 'outward_non-returnable')
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.expected_dispatch_date') }}: </strong>
                                                <span class="c-details">{{ $material->dispatch_date }}</span>
                                            </div>
                                        @elseif($material->type == 'outward_returnable')
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.expected_dispatch_date') }}: </strong>
                                                <span class="c-details">{{ $material->dispatch_date }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.expected_return_date') }}: </strong>
                                                <span class="c-details">{{ $material->return_date }}</span>
                                            </div>
                                        @elseif($material->type == 'between_sites')
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.expected_dispatch_date') }}: </strong>
                                                <span class="c-details">{{ $material->dispatch_date }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <strong class="text-muted c-label">{{ __('dashboard.expected_delivery_date') }}: </strong>
                                                <span class="c-details">{{ $material->delivery_date }}</span>
                                            </div>
                                    @endif
                                    <!--end::Description-->
                                        <!--begin::Progress-->
                                        <!--end::Progress-->
                                    </div>
                                    <!--end::Content-->
                                </div>
{{--                                <div class="vl"></div>--}}
                                <div class="col-md-4 col-sm-12" >
                                    <!--begin::Title-->
                                    <div class="mb-2">
                                        <strong class="text-muted c-label">{{ __('dashboard.request_id') }}: </strong>
                                        <span class="c-details">{{ $material->id }}</span>
                                        <!--begin::Name-->
{{--                                        <a href="#"--}}
{{--                                           class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3"><a--}}
{{--                                                href="#"><b>{{ __('dashboard.material') }}</b>: </a>--}}
{{--                                            <span class="c-details">{{ $material->id }}</span>--}}
{{--                                            <i class="questionIcon flaticon2-notification text-success icon-md ml-2"></i></a>--}}
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
                                            <span class="c-details">{{ $material->host->full_name }}</span>
                                        </div>
                                        <div class="mt-2">
                                            <strong class="text-muted c-label">{{ __('dashboard.sender') }}: </strong>
                                            @if($material->type == 'between_sites')
                                                <span class="c-details">{{ optional($material->sender_host)->full_name??' ---' }}</span>
                                            @elseif($material->type == 'personal_request')
                                                <span class="c-details">{{ $material->host->full_name }}</span>
                                            @else
                                                <span class="c-details">{{ $material->company }}</span>
                                            @endif
                                        </div>
                                        <!--end::Description-->
                                        <!--begin::Progress-->
                                        <!--end::Progress-->
                                    </div>
                                    <!--end::Content-->
                                </div>
{{--                                <div class="vl"></div>--}}
                                <div class="col-md-4 col-sm-12" >
                                    <!--begin::Title-->

                                    <!--end::Title-->
                                    <!--begin::Content-->
                                    <div class="">
                                        <!--begin::Description-->
                                        <div class="">
                                            <strong class="text-muted c-label">{{ __('dashboard.request_date') }}: </strong>
                                            <span class="c-details">{{ $material->created_at }}</span>
                                        </div>
                                        <div class="mt-2">
                                            <strong class="text-muted c-label">{{ __('dashboard.requester') }}: </strong>
                                            <span class="c-details">{{ $material->requester->full_name }}</span>
                                        </div>
                                        <div class="mt-2">
                                            <strong class="text-muted c-label">{{ __('dashboard.status') }}: </strong>
                                            <span
                                                class="badge badge-success">{{ handleTrans($material['status'])}}</span>
                                        </div>
                                        <!--end::Description-->
                                        <!--begin::Progress-->
                                        <!--end::Progress-->
                                    </div>
                                    <!--end::Content-->
                                    <div class="mt-2">
                                        <!--begin::Name-->
                                        <a type="button" id="MaterialsModalActive" data-id="{{ $material->id }}"
                                           class="btn MaterialsModalActive  btn-outline-success">{{ __('dashboard.take_action') }}</a>
                                        <!--end::Name-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Top-->
                <!--begin::Separator-->

                <!--end::Separator-->
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
