@extends('layouts.dashboard.master')
@push('js')
<script src="{{ asset('dashboard_assets/js/pages/features/charts/apexcharts.js') }}"></script>
@endpush
@section('content')
<div class="d-flex flex-column-fluid" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-4">
                @include('dashboard.statistics.filters')
            </div>
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-6">
                        @include('dashboard.statistics.chart1')
                    </div>
                    <div class="col-6">
                        @include('dashboard.statistics.chart2')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
