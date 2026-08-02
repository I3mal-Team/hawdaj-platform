<link rel="stylesheet" href="{{ asset('front_assets/css/mktrip-style.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
<style>
    .steps-tabs-trp .owl-carousel {
        display: block !important;
    }
</style>

<div class="popup_that_shows_on_startup hide {{ app()->getLocale() != 'ar' ? 'ltr' : 'rtl' }}" dir="ltr">
    <div id="make_a_trip_popup" class="container" style="z-index: 999999;">
        <img class="closing_x" src="{{ asset('front_assets/imgs/popup_images/xmark-solid.svg') }}" alt="">
        <div class="fixed-img">
            <img src="{{ asset('front_assets/imgs/Screenshosmall.png') }}" alt="">
        </div>
        <form action="{{ route('front.action_selected_places') }}" method="POST" id="trip_form">
            @csrf
            <div class="start-trips-taps">
                <div class="frirst-step">
                    <div class="start-search">
                        <!-- <img src="{{ asset('front_assets/imgs/Screenshotrp.png') }}" alt=""> -->
                        <iframe src="https://embed.lottiefiles.com/animation/47956"></iframe>
                    </div>
                    <div class="trip-text">
                        <span>{{ __('Start your trip') }}</span>
                        <em>{{ __('trip_subtitle') }}</em>
                        <em>{{ __('trip_subtitle_2') }}</em>
                        <button class="start-trip-hudj the-loader-btn" type="button">{{ __('Start now') }}</button>
                    </div>
                </div>

                <div class="steps-tabs-trp ">
                    <div class="step-details d-flex  justify-content-between">
                        <div class="img-step-detl">
                            {{-- <div class="carusel-steeps"> --}}
                            <div class="carusel-steeps owl-carousel owl-theme">
                                <div class="item">
                                    <img src="{{ asset('front_assets/imgs/Screenshosteppic.png') }}" alt="">
                                </div>
                                <div class="item">
                                    <img src="{{ asset('front_assets/imgs/ezba_slide.png') }}" alt="">
                                </div>
                                <div class="item">
                                    <img src="{{ asset('front_assets/imgs/our-services.jpg') }}" alt="">
                                </div>
                                <div class="item">
                                    <img src="{{ asset('front_assets/imgs/outimg.jpg') }}" alt="">
                                </div>
                                <div class="item">
                                    <img src="{{ asset('front_assets/imgs/outimg2.png') }}" alt="">
                                </div>
                                <div class="item">
                                    <img src="{{ asset('front_assets/imgs/16414227701709.jpg') }}" alt="">
                                </div>
                                <div class="item">
                                    <img src="{{ asset('front_assets/imgs/outimg2.png') }}" alt="">
                                </div>
                                <div class="item">
                                    <img src="{{ asset('front_assets/imgs/Screenshosteppic.png') }}" alt="">
                                </div>
                            </div>
                            {{-- <img src="{{ asset('front_assets/imgs/Screenshosteppic.png') }}" alt=""> --}}
                            {{-- </div> --}}

                        </div>
                        <div class="all-step-tabs">
                            <div class="steps-action">
                                <div class="steps-numb">
                                    <span class="one- step-stutes active"><em>1</em></span>
                                    <span class="two- step-stutes "><em>2</em></span>
                                    <span class="thre- step-stutes "><em>3</em></span>
                                    <span class="four- step-stutes"><em>4</em></span>
                                    <span class="fiv- step-stutes"><em>5</em></span>
                                </div>
                            </div>
                            <div class="all-steps-details">
                                <div class="notifcation-trip" id="val" style="display: none">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span id="validationMsg"></span>
                                </div>
                                <div class="the-first-stepp">
                                    {{--                                    <button class="btn-process btn-ring">--}}
                                    {{--                                        <!-- <div class="the-loader"></div> -->--}}
                                    {{--                                        <!-- <div class="dottet-loader"></div> -->--}}
                                    {{--                                        <iframe src="https://embed.lottiefiles.com/animation/79727"></iframe>--}}
                                    {{--                                    </button>--}}
                                    <div class="first-step-hudj hudj-details-data">
                                        <div class="trip-text">
                                            <span>{{__("Start your trip")}}</span>
                                            <em>{{__("Please choose the start date of the trip and the end date of the trip")}} </em>
                                        </div>
                                    </div>
                                    <div class="trip-content">
                                        <div class="first-step-hudj hudj-details-step">
                                            <div class="date-rang">
                                                <input type="text" readonly name="daterange" id="daterange"
                                                       value="01/01/2018 - 01/15/2018"
                                                       style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center mt-5">
                                        <span class="frst-nexttt btn-next the-loader-btn">
                                            <span>{{__("Next")}}</span>
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                    <!-- <div class="fixed-the-loader"></div> -->
                                </div>
                                <div class="the-sec-step">
                                    {{--                                    <button class="btn-process btn-ring">--}}
                                    {{--                                        <!-- <div class="the-loader"></div> -->--}}
                                    {{--                                        <!-- <div class="dottet-loader"></div> -->--}}
                                    {{--                                        <iframe src="https://embed.lottiefiles.com/animation/79727"></iframe>--}}
                                    {{--                                    </button>--}}
                                    <div class="first-step-hudj hudj-details-data">
                                        <div class="trip-text">
                                            <span>{{__("Starting area")}}</span>
                                            <em>{{__("Please select the starting area for the trip")}} </em>
                                        </div>
                                    </div>

                                    <div class="trip-content">
                                        <div class="trip-text">
                                            <input type="hidden" name="region1" id="region1">
                                            <input type="hidden" name="lat1" id="lat1">
                                            <input type="hidden" name="long1" id="long1">
                                            <div id="placeMap1" class="text-right" class="filter-grid-section__local-map">
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" btns-action">
                                        <span class="sec-back btn-back">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span>{{__("Previous")}}</span>
                                        </span>
                                        <span class="sec-nexttt btn-next the-loader-btn">
                                            <span>{{__("Next")}}</span>
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </span>

                                    </div>
                                </div>
                                <div class="the-thr-step">
                                    {{--                                    <button class="btn-process btn-ring">--}}
                                    {{--                                        <!-- <div class="the-loader"></div> -->--}}
                                    {{--                                        <!-- <div class="dottet-loader"></div> -->--}}
                                    {{--                                        <iframe src="https://embed.lottiefiles.com/animation/79727"></iframe>--}}
                                    {{--                                    </button>--}}
                                    <div class="first-step-hudj hudj-details-data">
                                        <div class="trip-text">
                                            <span>{{__("End area")}}</span>
                                            <em>{{__("Please select the end area for the trip")}} </em>

                                        </div>
                                    </div>

                                    <div class="trip-content">
                                        <div class="trip-text">
                                            <input type="hidden" name="region2" id="region2">
                                            <input type="hidden" name="lat2" id="lat2">
                                            <input type="hidden" name="long2" id="long2">
                                            <div id="placeMap2" class="text-right"
                                                 class="filter-grid-section__local-map">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btns-action">

                                        <span class="thr-back btn-back">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span>{{__("Previous")}}</span>
                                        </span>
                                        <span class="thr-nexttt btn-next the-loader-btn">
                                            <span>{{__("Next")}}</span>
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>

                                        </span>
                                    </div>
                                </div>
                                <div class="the-for-step">
                                    {{--                                    <button class="btn-process btn-ring">--}}
                                    {{--                                        <!-- <div class="the-loader"></div> -->--}}
                                    {{--                                        <!-- <div class="dottet-loader"></div> -->--}}
                                    {{--                                        <iframe src="https://embed.lottiefiles.com/animation/79727"></iframe>--}}
                                    {{--                                    </button>--}}
                                    <div class="trip-text">
                                        <span>{{__("Your trip details")}}</span>
                                        <em>{{__("Please enter your trip information")}}</em>
                                    </div>
                                    <div class="trip-content">
                                        <div class="vixt-type">
                                            <div class="vist-day-type d-flex align-items-center active"
                                                 onclick="type('day')">
                                                <input type="radio" name="type" class="vist-check"
                                                       value="day" checked>
                                                <span><i class="fa-solid fa-place-of-worship"></i></span>
                                                <div class="details-visit-trip">
                                                    <span>{{__("Day")}}</span>
                                                    <em> {{__("The number of places to visit per day")}}</em>
                                                </div>
                                            </div>
                                            <div class="vist-day-type d-flex align-items-center"
                                                 onclick="type('trip')">
                                                <input type="radio" name="type" class="vist-check"
                                                       value="trip">
                                                <span><i class="fa-solid fa-plane-departure"></i></span>
                                                <div class="details-visit-trip">
                                                    <span>{{__("Trip")}}</span>
                                                    <em> {{__("The number of places to visit during the trip")}}</em>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="numb-plc">{{__("Number of places to visit")}}</span>
                                        <div class="search-type">
                                            <div class="search-box">
                                                <input type="number" min="1" max="4" value="4" name="funny_place_per_day"
                                                       id="funny_place_per_day">
                                            </div>
                                            <div class="search-reslt">
                                                <span>Price</span>
                                                <div class="checked-box">
                                                    @foreach ($prices as $price)
                                                        <div class="checkbox-example">
                                                            <input type="checkbox" name="price[]"
                                                                   value="{{ $price->id }}"
                                                                   id="checkboxOneInput{{ $price->id }}"/>
                                                            <label for="checkboxOneInput{{ $price->id }}"></label>
                                                            <span>{{ $price->name }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btns-action">

                                        <span class="for-back btn-back">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span>{{__("Previous")}}</span>
                                        </span>
                                        <span class="for-nexttt btn-next the-loader-btn">
                                            <span>{{__("Next")}}</span>
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="the-fiv-step">
                                    <div class="trip-text">
                                        <span>{{__("Trip categories")}}</span>
                                        <em>{{__("Choose the categories of the trip you want to visit")}}
                                        </em>
                                    </div>
                                    <div class="trip-content">
                                        <div class="search-type">
                                            <div class="search-box">
                                                <input type="text" id="myInput" onkeyup="searchText()"
                                                       placeholder="{{__("Search")}}">
                                            </div>
                                        </div>
                                        <div class="vixt-type " id="myUL">
                                            @foreach ($categories as $cat)
                                                <div class="vist-day-typee for-step d-flex align-items-center"
                                                     onclick="categories({{ $cat->id }})">
                                                    <input type="checkbox" name="categories[]" id="categories"
                                                           value="{{ $cat->id }}" class="vist-check ">
                                                    <span><img width="55" height="40"
                                                               src="{{ asset($cat->icon ?? '/front_assets/imgs/zad1.jpg') }}"
                                                               alt="park icon"></span>
                                                    <div class="details-visit-trip">
                                                        <span>{{ $cat->name }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="btns-action">
                                        <span class="fiv-back btn-back">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span>{{__("Previous")}}</span>
                                        </span>
                                        <span onclick="move()" class="fiv-nexttt btn-next thelast-loader-btn">
                                            <span>{{__("Next")}}</span>
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="the-last-step">
                    <button class="btn-process btn-ring">
                        <div class="percentage-progress" id="test">
                            <div class="clock-counter">
                                <iframe src="https://embed.lottiefiles.com/animation/78807"></iframe>
                            </div>
                            <div class="text-trip">
                                {{__("Please wait while processing the trip")}}
                            </div>
                        </div>
                        <div id="new-section" class="last-sec">
                            <iframe src="https://embed.lottiefiles.com/animation/97818"></iframe>
                            <span> {{__("The trip has been processed successfully")}}</span>
                        </div>
                        <!-- <div class="the-loader " ></div> -->


                        <!-- <div class="dottet-loader"></div> -->
                    </button>
                    <img class="closing_x" src="{{ asset('front_assets/imgs/popup_images/xmark-solid.svg') }}"
                         alt="">
                    <div class="fix-last-img">
                        <img src="{{ asset('front_assets/imgs/Screensholast-hupng.png') }}" alt="">
                    </div>
                    <div class="trip-text">
                        <span>{{__("Your trip is ready")}} </span>
                        <em>{{__("Happy trip, for more information click")}} <a href="">{{__("Help")}}</a> </em>

                    </div>
                    <div class="start-search">
                        <!-- <img src="{{ asset('front_assets/imgs/Screenshotrp2.png') }}" alt=""> -->
                        <iframe src="https://embed.lottiefiles.com/animation/88140"></iframe>
                    </div>
                    <div class="result-search">
                        <button class="start-trip-resut the-last-loader"> {{__("Start now")}}</button>
                    </div>

                </div>

            </div>
        </form>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    var valid = 1;

    var cat = [];

    $(".start-trip-hudj").click(function () {
        $(".frirst-step").addClass("active");
        $(".steps-tabs-trp").addClass("active");
        $(".fixed-img").addClass("active");
        $("#daterange").click();
        @php
            \Illuminate\Support\Facades\Cache::forget("current_trip_" . Auth::id());
        @endphp
    })
    $(".frst-nexttt").click(function () {
        if ($('#daterange').val() == '') {
            $('#validationMsg').html('{{__("Trip start and end date is required")}}');
            $('#val').show()
            valid = 0;

        } else {
            $('#val').hide()
            valid = 1
            $(".the-first-stepp").addClass("done-tap")
            $(".one-.step-stutes").addClass("done")
            $(".one-.step-stutes").removeClass("active")
            $(".two-.step-stutes").addClass("active")
            $(".the-sec-step").addClass("active")
        }
    })
    $(".sec-nexttt").click(function () {
        if ($('#region1').val() == '') {
            $('#validationMsg').html('{{__("Please select the starting area for the trip")}}');
            $('#val').show()
            valid = 0;

        } else {
            $('#val').hide()
            valid = 1
            $(".the-sec-step").addClass("done-tap")
            $(".the-sec-step").removeClass("active")
            $(".the-thr-step").addClass("active")
            $(".two-.step-stutes").removeClass("active")
            $(".two-.step-stutes").addClass("done")
            $(".thre-.step-stutes").addClass("active")
        }
    })
    $(".thr-nexttt").click(function () {
        if ($('#region2').val() == '') {
            $('#validationMsg').html('{{__("Please select the end area for the trip")}}');
            $('#val').show()
            valid = 0;

        } else {
            $('#val').hide()
            valid = 1
            $(".the-thr-step").addClass("done-tap")
            $(".the-thr-step").removeClass("active")
            $(".the-for-step").addClass("active")
            $(".two-.step-stutes").removeClass("active")
            $(".thre-.step-stutes").addClass("done")
            $(".four-.step-stutes").addClass("active")
        }
    })

    $('#funny_place_per_day').on('change', function () {
        if ($(this).val() <= 0)
            $(this).val(1);
        if ($(this).val() > 4)
            $(this).val(4);
    })

    $('#funny_place_per_day').on('input', function () {
        if ($(this).val() <= 0)
            $(this).val(1);
        if ($(this).val() > 4)
            $(this).val(4);
    })

    $(".for-nexttt").click(function () {
        if ($('#funny_place_per_day').val() == '') {
            $('#validationMsg').html('{{__("The number of places the trip is required")}}');
            $('#val').show()
            valid = 0;
        } else if ($('#funny_place_per_day').val() > 4 && $('input[name="type"]').val() == 'day') {
            $('#validationMsg').html('{{__("The maximum number of places to travel per day is 4 places")}}');
            $('#val').show()
            valid = 0;
        } else {
            $('#val').hide()
            valid = 1
            $(".the-for-step").addClass("done-tap")
            $(".the-for-step").removeClass("active")
            $(".the-fiv-step").addClass("active")
            $(".thre-.step-stutes").removeClass("active")
            $(".four-.step-stutes").addClass("done")
            $(".fiv-.step-stutes").addClass("active")
        }
    })
    $(".fiv-nexttt").click(function () {
        if (cat.length < 1) {
            $('#validationMsg').html('{{__("Trip categories are required")}}');
            $('#val').show()
            valid = 0;

        } else {
            $('#val').hide()
            valid = 1
            $(".the-for-step").addClass("done-tap")
            $(".the-for-step").removeClass("active")
            $(".the-fiv-step").addClass("active")
            $(".four-.step-stutes").removeClass("active")
            $(".four-.step-stutes").addClass("done")
            $(".fiv-.step-stutes").addClass("active")
            $(".steps-tabs-trp").removeClass("active")
            $(".the-last-step").addClass("active")
        }
        // $(".fixed-img").removeClass("active")
    })
    $(".sec-back.btn-back").click(function () {
        $(".the-sec-step").removeClass("active")
        $(".the-first-stepp").removeClass("done-tap")
        $(".two-.step-stutes").removeClass("active")
        $(".two-.step-stutes").removeClass("done")
        $(".one-.step-stutes").addClass("active")
        $(".one-.step-stutes").removeClass("done")
    })
    $(".thr-back.btn-back").click(function () {
        $(".the-sec-step").removeClass("done-tap")
        $(".the-sec-step").addClass("active")
        $(".the-thr-step").removeClass("active")
        $(".thre-.step-stutes").removeClass("active")
        $(".thre-.step-stutes").removeClass("done")
        $(".two-.step-stutes").addClass("active")
        $(".two-.step-stutes").removeClass("done")
    })
    $(".for-back.btn-back").click(function () {
        $(".the-thr-step").removeClass("done-tap")
        $(".the-thr-step").addClass("active")
        $(".the-for-step").removeClass("active")
        $(".four-.step-stutes").removeClass("active")
        $(".thre-.step-stutes").removeClass("done")
        $(".thre-.step-stutes").addClass("active")
        $(".thre-.step-stutes").removeClass("done")
    })
    $(".fiv-back.btn-back").click(function () {
        $(".the-for-step").removeClass("done-tap")
        $(".the-for-step").addClass("active")
        $(".the-fiv-step").removeClass("active")
        $(".fiv-.step-stutes").removeClass("active")
        $(".four-.step-stutes").removeClass("done")
        $(".four-.step-stutes").addClass("active")
        $(".four-.step-stutes").removeClass("done")
    })
    $(".vist-day-type").click(function () {
        $(this).toggleClass("active")
        $(this).siblings().removeClass("active")
    })
    $(".vist-day-typee.for-step").click(function () {
        $(this).toggleClass("active")
    })

    var s, s2;

    function KSAMAP() {
        $("#customMapType").addClass('active')
        $("#googleMapType").removeClass('active')

        const data = [
            ['sa-4293', 0],
            ['sa-tb', 1],
            ['sa-jz', 2],
            ['sa-nj', 3],
            ['sa-ri', 4],
            ['sa-md', 5],
            ['sa-ha', 6],
            ['sa-qs', 7],
            ['sa-hs', 8],
            ['sa-jf', 9],
            ['sa-sh', 10],
            ['sa-ba', 11],
            ['sa-as', 12],
            ['sa-mk', 250]
        ];

        Highcharts.mapChart('placeMap1', {
            chart: {
                map: 'ksa',
                backgroundColor: '#e4e4e4',
                borderColor: '#c8c8c8',
                borderRadius: 9,
                events: {
                    load: function () {
                    }
                },
            },
            // tooltip: false,
            title: {
                text: ''
            },
            mapNavigation: {
                enabled: false
            },
            plotOptions: {
                series: {
                    point: {
                        events: {
                            click: function () {
                                if(this.properties.region == parseInt($('input[name="region2"]').val())){
                                    $('#validationMsg').html('{{__("Please select valid start area for the trip")}}');
                                    $('#val').show();
                                    return;
                                }else {
                                    $('#validationMsg').html('');
                                    $('#val').hide();
                                }

                                if (s) {
                                    if (s.options.value != this.options.value) {
                                        s.selected = false;
                                        s.setState('')
                                        s = null
                                        s = this;
                                        s.selected = true;
                                        s.setState('hover')
                                    } else {
                                        s.selected = false;
                                        s.setState('')
                                        s = null
                                    }
                                } else {
                                    s = this;
                                    s.selected = true;
                                    s.setState('hover')
                                }
                                if($('input[name="region1"]').val() != this.properties.region)
                                {
                                    $('input[name="region1"]').val(this.properties.region);
                                    $('input[name="lat1"]').val(this.properties.latitude);
                                    $('input[name="long1"]').val(this.properties.longitude);
                                }else {
                                    $('input[name="region1"]').val("");
                                    $('input[name="lat1"]').val("");
                                    $('input[name="long1"]').val("");
                                }
                            },
                            mouseOver: function () {
                                this.setState('hover')
                            },
                            mouseOut: function () {
                                this.setState('')
                            }
                        }
                    },
                }
            },
            series: [{
                data: data,
                name: '',
                states: {
                    hover: {
                        color: '#84548E'
                    }
                },
                dataLabels: {
                    enabled: true,
                    format: '{point.name}',
                }
            }],
            colorAxis: {
                min: 1,
                type: '',
                minColor: '#b0b0b0',
                maxColor: '#84548E',
                stops: [
                    [0, '#f3f3f3']
                ]
            }
        });
        Highcharts.mapChart('placeMap2', {
            chart: {
                map: 'ksa',
                backgroundColor: '#e4e4e4',
                borderColor: '#c8c8c8',
                borderRadius: 9,
                events: {
                    load: function () {
                    }
                },
            },
            // tooltip: false,
            title: {
                text: ''
            },
            mapNavigation: {
                enabled: false
            },
            plotOptions: {
                series: {
                    point: {
                        events: {
                            click: function () {

                                if(this.properties.region == parseInt($('input[name="region1"]').val())){
                                    $('#validationMsg').html('{{__("Please select valid end area for the trip")}}');
                                    $('#val').show();
                                    return;
                                }else {
                                    $('#validationMsg').html('');
                                    $('#val').hide();
                                }

                                if (s2) {
                                    if (s2.options.value != this.options.value) {

                                        s2.selected = false;
                                        s2.setState('')
                                        s2 = null
                                        s2 = this;
                                        s2.selected = true;
                                        s2.setState('hover')
                                    } else {
                                        s2.selected = false;
                                        s2.setState('')
                                        s2 = null
                                    }
                                } else {
                                    s2 = this;
                                    s2.selected = true;
                                    s2.setState('hover')
                                }

                                if($('input[name="region2"]').val() != this.properties.region)
                                {
                                    $('input[name="region2"]').val(this.properties.region);
                                    $('input[name="lat2"]').val(this.properties.latitude);
                                    $('input[name="long2"]').val(this.properties.longitude);
                                }else {
                                    $('input[name="region2"]').val("");
                                    $('input[name="lat2"]').val("");
                                    $('input[name="long2"]').val("");
                                }
                            },
                            mouseOver: function () {
                                this.setState('hover')
                            },
                            mouseOut: function () {
                                this.setState('')
                            }
                        }
                    },
                }
            },
            series: [{
                data: data,
                name: '',
                states: {
                    hover: {
                        color: '#84548E'
                    }
                },
                dataLabels: {
                    enabled: true,
                    format: '{point.name}',
                }
            }],
            colorAxis: {
                min: 1,
                type: '',
                minColor: '#b0b0b0',
                maxColor: '#84548E',
                stops: [
                    [0, '#f3f3f3']
                ]
            }
        });
    }

    KSAMAP();

    $('.carusel-steeps.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        dots: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 1
            }
        }
    })

    function type(t) {
        $('input[name="type"]').val(t)
    }


    function categories(id) {
        var index = cat.indexOf(id);
        if (index !== -1) {
            cat.splice(index, 1);
        } else {
            $('#val').hide()
            valid = 1
            cat.push(id);
        }
        $('#categories').val(cat)
        $('input[id="categories"]').val(cat)
    }

    $('.btn-process').click(function () {
        $("fixed-the-loader").addClass(".active");
    })

    $('.the-loader-btn').on('click', function () {
        if (valid == 1) {
            $(".btn-ring").show();
            $(".btn-process").prop('disabled', true);
            $(".btn-process").val('disabled');
            setTimeout(function () {
                $(".btn-ring").hide();
                $(".btn-process").prop('disabled', false);
            }, 1000);
        }
    });

    $('.thelast-loader-btn').on('click', function () {
        if (valid == 1) {
            $(".btn-ring").show();
            $(".btn-process").prop('disabled', true);
            $(".btn-process").val('disabled');

        }
    });

    function move() {
        var elem = document.getElementById("myBar");

        var width = 0;
        var id = setInterval(frame, 50);

        function frame() {
            if (width >= 100) {
                clearInterval(id);
            } else {
                width++;

                elem.innerHTML = width * 1 + '%';
            }
            setTimeout(function () {
                document.getElementById('test').style = 'display:none'
                document.getElementById('new-section').style = 'display:flex'
            }, 6000);
            setTimeout(function () {
                $(".btn-ring").hide();
                $(".btn-process").prop('disabled', false);
            }, 9000);
        }
    }

    function searchText() {
        // Declare variables
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('myInput');
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByClassName('vist-day-typee');
        console.log(li);

        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("div")[0];
            let span = a.getElementsByTagName("span")[0];
            txtValue = span.textContent || span.innerText;
            if (txtValue == filter || txtValue.includes(filter)) {
                console.log('here');
                li[i].style.display = "";
            } else {
                console.log('hide');
                li[i].setAttribute('style', 'display:none !important');
            }
        }
    }
</script>
