@extends('layouts.front.hawdaj_master')
@section('content')
    @if ($trips && count($trips) > 0)
        <link rel="stylesheet" href="{{ asset('front_assets/css/mytrips-style.css') }}">
        <section class="zad section-padding">
            <div class="my_trips_section outer_page ">
                <div class="container px-0">
                    <div class="zad__grid-container">
                        <div class="d-flex flex-row flex-wrap mx-0 all-trip-result ">
                            @foreach ($trips as $place)
                                <div class="mb-4  my-trip-result">
                                    <a href="{{ url('/ar/view_trip/' . $place['id']) }}"
                                       class="palce-card my_trips_place_card h-100" style="width:300px">

                                        <!-- card img -->
                                        <img src="{{ asset('front_assets/imgs/popup_images/suitcase.png') }}"
                                             class="card-img-top" alt="place">

                                        <!-- card content -->
                                        <div class="card-body">
                                            {{-- <h5 class="my_trips_card_title">{{ $place['name'] }} </h5> --}}

                                            <div class="my_trips_card_views  ">
                                                <span
                                                    class="my_trips_card_numbers">
                                                    {{ $place['name'] }}</span>

                                                <span class="my_trips_card_numbers">
                                                    {{ $place['days'] }} {{__("Day")}}

                                                    </span>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div class="rate d-flex align-items-center">
                                                    <span class="mx-1 my_trips_card_date">
                                                        {{ $place['item_per_day'] }}
                                                        {{__("Places in day")}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="see-more-n">
                                            <span class="see-m-nd">
                                                {{__("Explore more")}}
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @include('errors.notFount')
    @endif
@endsection
