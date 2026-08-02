@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.activities')</h5>
    <ul class="breadcrumb breadcrumb-transpart breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.activities')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid card--custom">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center" style="position: relative">
{{--                        <div class="">--}}
{{--                            <h3 class="c-title mb-0">@lang('dashboard.show_all_activities')</h3>--}}
{{--                        </div>--}}
                        <div class="d-none">
                            <div class="col-md-3 col-sm-6">
                                <div class="input-group">
                                    <input type="text" value="{{old('key_search')}}" class="form-control" placeholder="search PID people">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="input-group">
                                    <select class="nice-select form-control row" name="status">
                                        <option value="status1">Today</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="input-group">
                                    <select class="nice-select form-control row" name="status">
                                        <option value="status1">Host</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="activities-btn-cont">
                            <a class="btn  btn-primary" data-toggle="modal" data-target="#invitationLink">
                                @lang('dashboard.send_invitation')
                            </a>
                            <a href="{{route('dashboard.visits-activities.new')}}" class="btn btn-primary">
                                @lang('dashboard.checkin_new_visitor')
                            </a>
                        </div>
                    </div>
                    <div class="example-preview custom-nav  ">
                        <ul class="nav nav-tabs custom-nav-tabs " id="myTab1" role="tablist">
                            <li class="nav-item">
                                <a href="{{route('dashboard.visits-activities',['type'=>'expected-visit'])}}"
                                   class="nav-link {{in_array(request('type'),['expected-visit',null]) ? 'active' : ''}}">
                                    <span class="nav-icon">
                                        <i class="flaticon-users-1"></i>
                                    </span>
                                    <span class="nav-text">{{ __('dashboard.expected_visitors') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('dashboard.visits-activities',['type'=>'checkin-visit'])}}"
                                   class="nav-link {{request('type') == 'checkin-visit'? 'active' : ''}}">
                                    <span class="nav-icon">
                                        <i>
                                            <svg fill='currentColor' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 20 20" version="1.1">
                                                <g id="surface1">
                                                <path style=" stroke:none;fill-opacity:1;" d="M 11.289062 10.664062 L 8.007812 13.945312 C 7.640625 14.308594 7.046875 14.308594 6.683594 13.945312 C 6.316406 13.578125 6.316406 12.984375 6.679688 12.617188 L 8.363281 10.9375 L 1.875 10.9375 C 1.355469 10.9375 0.9375 10.519531 0.9375 10 C 0.9375 9.480469 1.355469 9.0625 1.875 9.0625 L 8.363281 9.0625 L 6.679688 7.382812 C 6.316406 7.015625 6.316406 6.421875 6.683594 6.054688 C 7.046875 5.691406 7.640625 5.691406 8.007812 6.054688 L 11.289062 9.335938 C 11.652344 9.703125 11.652344 10.296875 11.289062 10.664062 Z M 15 2.1875 L 10.625 2.1875 C 10.105469 2.1875 9.6875 2.605469 9.6875 3.125 C 9.6875 3.644531 10.105469 4.0625 10.625 4.0625 L 14.6875 4.0625 L 14.6875 15.9375 L 10.625 15.9375 C 10.105469 15.9375 9.6875 16.355469 9.6875 16.875 C 9.6875 17.394531 10.105469 17.8125 10.625 17.8125 L 15 17.8125 C 15.863281 17.8125 16.5625 17.113281 16.5625 16.25 L 16.5625 3.75 C 16.5625 2.886719 15.863281 2.1875 15 2.1875 Z M 15 2.1875 "/>
                                                </g>
                                                </svg>
                                        </i>

                                </span>
                                    <span class="nav-text">{{ __('dashboard.checkin') }}</span>
                                </a>
                            </li>
                                <li class="nav-item">
                                    <a href="{{route('dashboard.visits-activities',['type'=>'checkout-visit'])}}"
                                       class="nav-link {{request('type') == 'checkout-visit'? 'active' : ''}}">
                                         <span class="nav-icon">
                                               <i>
                                                   <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 20 20" version="1.1">
                                                                <g id="surface1">
                                                                <path style=" stroke:none;fill-opacity:1;" d="M 17.539062 10.664062 L 14.257812 13.945312 C 13.890625 14.308594 13.296875 14.308594 12.933594 13.945312 C 12.566406 13.578125 12.566406 12.984375 12.929688 12.617188 L 14.613281 10.9375 L 8.125 10.9375 C 7.605469 10.9375 7.1875 10.519531 7.1875 10 C 7.1875 9.480469 7.605469 9.0625 8.125 9.0625 L 14.613281 9.0625 L 12.929688 7.382812 C 12.566406 7.015625 12.566406 6.421875 12.933594 6.054688 C 13.296875 5.691406 13.890625 5.691406 14.257812 6.054688 L 17.539062 9.335938 C 17.902344 9.703125 17.902344 10.296875 17.539062 10.664062 Z M 8.125 15.9375 L 4.0625 15.9375 L 4.0625 4.0625 L 8.125 4.0625 C 8.644531 4.0625 9.0625 3.644531 9.0625 3.125 C 9.0625 2.605469 8.644531 2.1875 8.125 2.1875 L 3.75 2.1875 C 2.886719 2.1875 2.1875 2.886719 2.1875 3.75 L 2.1875 16.25 C 2.1875 17.113281 2.886719 17.8125 3.75 17.8125 L 8.125 17.8125 C 8.644531 17.8125 9.0625 17.394531 9.0625 16.875 C 9.0625 16.355469 8.644531 15.9375 8.125 15.9375 Z M 8.125 15.9375 "/>
                                                                </g>
                                                                </svg>
                                               </i>
                                        </span>
                                        <span class="nav-text">{{ __('dashboard.checkout') }}</span>
                                    </a>
                            </li>
                        </ul>


                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="tab-content mt-5" id="myTabContent1">
                        <div class="tab-pane fade show active">
                            @include('dashboard.visits.activities.extra._visitor_table',['type' => request('type')??"expected"])
                        </div>
                        @include('dashboard.visits.activities.extra._invitation_link')
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        </div>
    </div>
@endsection

@push('js')
    <script src="{{asset('dashboard_assets/custom/js/activities.js')}}" type="text/javascript"></script>
    <script>
        $("button[data-dismiss=modal]").click(function () {
            $(".modal").modal('hide');
        });

        $(".resend_link").on('click', function (e) {
            var type = $(this).data('type');
            var self = $(this);
            var old_text = $(this).text();

            self.addClass('spinner spinner-white spinner-right').prop('disabled', true);
            self.text('Please Wait..');
            const lang = "<?php echo app()->getLocale() ?>"
            $.ajax({
                url: lang + '/dashboard/visits-activities/resend-link',
                data: {type: type,  _token: $('meta[name="csrf-token"]').attr("content") },
                type: 'POST',
                success: function (data) {

                    setTimeout(() => {
                        self.removeClass('spinner spinner-white spinner-right').prop('disabled', false);
                        self.text(old_text);
                        $("#invitationLink").modal('hide');
                        toastr.success(data.message);
                    },1000);

                },
                error: function () {
                    self.removeClass('spinner spinner-white spinner-right').prop('disabled', false);
                    self.text(old_text);
                    toastr.error('Failed To Re-send Code');
                }
            });
        });

        $("#send_message").on('click', function (e) {
            var message = $("#message_area").val();
            var self = $(this);
            var old_text = $(this).text();

            if(message == ''){
                toastr.error('Please Write Message Frist');
                return;
            }

            self.addClass('spinner spinner-white spinner-right').prop('disabled', true);
            self.text('Please Wait..');



            $.ajax({
                url: "{{url('en/dashboard/visits-activities/send-message')}}",
                data: {message: message},
                type: 'POST',
                success: function (data) {
                    setTimeout(() => {
                        self.removeClass('spinner spinner-white spinner-right').prop('disabled', false);
                        self.text(old_text);
                        $("#invitationLink").modal('hide');
                        toastr.success(data.message);
                    },1000);
                },
                error: function () {
                    self.removeClass('spinner spinner-white spinner-right').prop('disabled', false);
                    self.text(old_text);
                    toastr.error('Failed To Send Message');
                }
            });
        });

    </script>
@endpush
