<div class="row">
    <div class="col-12">
        <!--begin::List Widget 1-->
        <div class="card card-custom card-stretch gutter-b">
            <!--begin::Header-->
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">{{ __('dashboard.health_check') }}</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-sm">{{ __('dashboard.health_check') }}</span>
                </h3>
                <div class="card-toolbar">
                    <div class="dropdown dropdown-inline" data-toggle="tooltip" title="Quick actions" data-placement="left">
                        <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ki ki-bold-more-hor"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-8" id="health_check">
                <!--begin::Item-->
                @foreach($questions as $key => $question)
                    <div class="d-flex align-items-center mb-10">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-40 symbol-light-primary mr-5 questionIcon">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-lg svg-icon-primary">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
                                            <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                        </div>
                        <!--end::Symbol-->
                        <!--begin::Text-->
                        <div class="d-flex flex-column font-weight-bold">
                            <div class="row">
                                <div class="col-md-10 col-sm-10 col-xs-10">
                                    <a href="javscript::;" class="text-dark text-hover-primary mb-1 font-size-lg">{{ $question->question }}</a>
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-1">
                                    <div class="radio-inline">
                                        <label class="radio radio-success">
                                            <input type="radio" name="health_check_id_{{ $question->id }}" value="1"/>
                                            <span></span>
                                            {{__('dashboard.yes')}}
                                        </label>
                                        <label class="radio radio-danger">
                                            <input type="radio" name="health_check_id_{{ $question->id }}" value="0" />
                                            <span></span>
                                            {{ __('dashboard.no') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Text-->
                    </div>
                @endforeach
                <!--end::Item-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::List Widget 1-->
    </div>
</div>
