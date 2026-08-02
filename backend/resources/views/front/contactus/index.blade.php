@extends('layouts.front.hawdaj_master')
@section('content')
    <main class="place-details mx-auto overflow-hidden py-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4 mb-5 col-xs-12 ">
                    <div class="contactInfo tourist-facilities__form">
                        <div class="priceTableTitle">
                            <h2 class="section__title">{{__('Contact Us')}}
                            </h2>
                        </div>
                        <ul class="list-unstyled list-address">
                            <li class="mb-2">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                {{__('Address')}}
                            </li>
                            <li class="mb-2">
                                {{__("Hawally, Block 1 - Street 156 - behind the Disabled Club - Mezzanine")}}
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-mobile" aria-hidden="true"></i>
                                {{__('Hot Line')}}
                            </li>
                            <li class="mb-2">
                                +55 654 545 122
                            </li>
                            <li class="mb-2">
                                +55 654 545 123
                            </li>
                            <li class="mb-2">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                {{__('E-mail')}}
                            </li>
                            <li>
                                <a href="#">info @example.com</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-8 mb-5 col-xs-12 tourist-facilities">
                    <div class="signUpFormArea">
                        <div class="priceTableTitle">
                            <h2 class="section__title">{{__('Tourist Facilities')}}
                            </h2>
                            <p class="section__sub-title">
                                {{__('Tourist Facilities Words')}}
                            </p>
                        </div>
                        <div class="signUpForm">
                            <form action="{{ route('front.send') }}" method="post" id="msgForm">
                                {{ csrf_field() }}
                                {{ method_field('post') }}
                                <div class="form-group mt-3">
                                    <label for="name" class="tajawal-bold">{{__('name')}} </label>
                                    <input type="text" id="name" name="name" class="form-control"
                                           placeholder="{{__('name')}}">
                                    <p id="u_name" style="display: none;color: red">{{ __("Please enter the data for this field") }}</p>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="email" class="tajawal-bold">{{__('E-mail')}}</label>
                                    <input type="text" id="email" name="email" class="form-control"
                                           placeholder="you@company.com">
                                    <p id="u_email" style="display: none;color: red">{{ __("Please enter the data for this field") }}</p>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="phone" class="tajawal-bold">{{__('phone')}}</label>
                                    <div dir="ltr">
                                        <input type="number" maxlength="14" id="phone" name="phone" class="form-control"
                                               placeholder="+966-000-0000">

                                    </div>
                                    <p id="u_phone" style="display: none;color: red">{{ __("Please enter the data for this field") }}
                                    </p>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="message" class="tajawal-bold">{{__('How can I help you')}}</label>
                                    <textarea class="form-control" name="message" id="message" cols="30"
                                              rows="6"></textarea>
                                    <p id="u_message" style="display: none;color: red">{{ __("Please enter the data for this field") }}</p>
                                </div>
                                <div class="form-group mt-3">
                                    <button onclick="addOpinion()" id="contact_btn" class="btn btn-block btn-primary"
                                            type="button">{{__('Send')}}
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('scripts')
    <script>

        function addOpinion() {
            var name = $('#name').val();
            var email = $('#email').val()
            var phone = $('#phone').val()
            var message = $('#message').val()
            $('#u_name').hide();
            $('#u_email').hide();
            $('#u_phone').hide();
            $('#u_message').hide();

            if (name == '' || name == null || name == ' ') {
                $('#u_name').show();
            }
            if (email == '' || email == null || email == ' ') {
                $('#u_email').show();
            }
            if (phone == '' || phone == null || phone == ' ') {
                $('#u_phone').show();
            }
            if (message == '' || message == null || message == ' ') {
                $('#u_message').show();
            }

            if ($('#name').val() != '' && $('#email').val() != '' && $('#phone').val() != '' && $('#message').val() != '') {
                $('#contact_btn').html("{{__("Sending Now")}}")
                $.ajax({
                    url: "{{ route('front.send') }}",
                    type: "POST",
                    data: {
                        name: $('#name').val(),
                        email: $('#email').val(),
                        phone: $('#phone').val(),
                        message: $('#message').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#msgForm').trigger("reset");
                        $('#u_name').hide();
                        $('#u_email').hide();
                        $('#u_phone').hide();
                        $('#u_message').hide();
                        toastr.success('{{__("Sent successfully")}}')
                        $('#contact_btn').html("{{__("Send")}}")
                    },
                    error(data) {
                        const keys = Object.keys(data.responseJSON);
                        keys.forEach(key => $('#u_' + key).html(data.responseJSON[key]).show());
                        $('#contact_btn').html("{{__("Send")}}")
                    }
                });
            }
        }

    </script>
@endsection
