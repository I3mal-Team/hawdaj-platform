@extends('layouts.front.hawdaj_master')
@section('content')
    @if ($swalefs && count($swalefs) > 0)
        <section class="filter-grid-section filter-grid-section--height overfloow-pages">
            <div class="container">
                {{-- <h2 class="page-title mb-4">الســوالـف</h2> --}}
                <div class="section-boxes container mt-4">
                    <div class="section-boxes-wrap">
                        <div class="section-title">
                            <div class="section-projects-overflow">
                                <div class="filter-grid-section__container filter-grid-section--height">

                                    <div class="card-row" id="all-data">
                                        @include('front.swalef.cards' , compact('swalefs'))
                                    </div>
                                    <input type="hidden" id="page" value="1"/>
                                    <input type="hidden" id="max_page" value="{{$swalefs->lastPage()}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @include('errors.notFount')
    @endif
@endsection
