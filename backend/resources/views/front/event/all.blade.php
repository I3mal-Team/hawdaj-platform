@extends('layouts.front.hawdaj_master')
@section('content')
    <link rel="stylesheet" href="{{ asset('front_assets/css/allevents.style.css') }}">

    <section class="filter-section">
        <div class="container-fluid text-start">
            <div class="secltion-forms">
                <button class="btn btn-primary" type="button" id="open-filters">
                    <i class="fa fa-glasses"></i>
                </button>
            </div>
            <form id="filterForm" class="filter-section__filters-form collapse">
                <div class="secltion-forms">
                    <label for="">{{ __('Event name') }}</label>
                    <input type="text" class="form-control" value="{{ request('search') }}"
                           placeholder="{{ __('Event name') }}" name="search">
                </div>
                <div class="secltion-forms">
                    <label for="">{{ __('Date') }}</label>
                    <input type="text" name="daterange" readonly id="daterange" class="form-control date"
                           value="{{ request('daterange' , '01/01/2023 - 01/15/2024') }}"/>
                </div>
                <div class="secltion-forms">
                    <label for="">{{ __('Location type') }}</label>
                    <select class="form-control select2-input" name="address_type" id="address_type">
                        <option selected value="0">{{ __('Location type') }}</option>
                        <option
                            value="link" {{ request('address_type') == 'link' ? 'selected' : '' }}>{{ __('Online') }}</option>
                        <option
                            value="map" {{ request('address_type') == 'map' ? 'selected' : '' }}>{{ __('In location') }}
                        </option>
                    </select>
                </div>
                <button type="submit" id="search" class="btn btn-primary">
                    {{ __('Search') }}
                </button>
            </form>
        </div>
    </section> <!-- filter -->

    @if ($places && count($places) > 0)
        <section class="all-event-page filter-grid-section filter-grid-section--height overfloow-pages">
            <div class="container">
                <div class="section-boxes container mt-4">
                    <div class="filter-grid-section__container filter-grid-section--height">
                        <div class="all-event" id="all-data">
                            @include('front.event.cards' , compact('places'))
                        </div>
                        <input type="hidden" id="page" value="1"/>
                        <input type="hidden" id="max_page" value="{{$places->lastPage()}}"/>
                    </div>
                </div>
            </div>
        </section>
    @else
        @include('errors.notFount')
    @endif
@endsection

@section('scripts')
    <script>

        $(".hover").mouseleave(
            function () {
                $(this).removeClass("hover");
            }
        );
    </script>
@endsection
