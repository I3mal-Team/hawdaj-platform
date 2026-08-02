@extends('layouts.dashboard.app')

@section('page')

<!--begin::Main-->
<style>
    span.select2-selection.select2-selection--single {
        min-height: 40px
    }
</style>
@include('dashboard.includes.partials._header-mobile')

<div class="d-flex flex-column flex-root @role('guard') guard_layout @endrole">

    <!--begin::Page-->
    <div class="d-flex flex-row flex-column-fluid page">
        @unlessrole('guard')
        @include('dashboard.includes.partials._aside')
        @endunlessrole

        <!--begin::Wrapper-->
        <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

            @include('dashboard.includes.partials._header')

            <!--begin::Content-->
            <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

                @include('dashboard.includes.partials._subheader')

                @yield('content')

            </div>
            <!--end::Content-->

            @include('dashboard.includes.partials._footer')
        </div>

        <!--end::Wrapper-->
    </div>

    <!--end::Page-->
</div>

<!--end::Main-->

@endsection

@if(session()->has('message'))
@include('dashboard.includes.alerts.toaster',[
'message' => session()->get('message'),
'alert_status' => session()->get('status')??'success'
])
@endif