<div class="card card-custom gutter-b mb-2 card-shadowless">
    <div class="card-body">
        <div class="card modal-card card-custom gutter-b mb-2 card-shadowless">
            <div class="card-header">
                <div class="card-title">
                    <h4 class="card-label">{{__('dashboard.healthChecks')}}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row ">
                    @forelse($task->health_check as $key => $question)
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
                                        <a href="#" class="text-dark text-hover-primary mb-1 font-size-lg">{{ $question['question']['question'] }}</a>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1">
                                        <div class="radio-inline">
                                            @if($question['answer'] == 1)
                                                <label class="radio radio-success">
                                                    <input type="radio" name="helth-{{ $question->id }}" value="1" checked/>
                                                    <span></span>
                                                    {{__('dashboard.yes')}}
                                                </label>
                                            @else
                                                <label class="radio radio-danger">
                                                    <input type="radio" name="helth-{{ $question->id }}" value="0" checked/>
                                                    <span></span>
                                                    {{ __('dashboard.no') }}
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Text-->
                        </div>

                        @if($loop->last)
                            <div class="card-body">
                                <div class="row container text-center">
                                    <div class="col-md-12">
                                        <form action="{{route('dashboard.taskAction')}}" method="post">
                                            @csrf
                                            @method('put')
                                            <input type="hidden" name="id" value="{{$task->id}}">
                                            @if($task->health_status === 0)
                                                <div class="container">
                                                    <input type="submit" class="btn btn-outline-success ml-2 mb-4" name="health_status"  value='Approve'>
                                                    <input type="submit" class="btn btn-outline-danger ml-2 mb-4" name="health_status"  value='Reject'>
                                                </div>
                                            @endif
                                        </form>
                                    </div>

                                </div>
                            </div>
                        @endif
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
