<div class="card-body">
    <div class="row justify-content-center">
    @foreach($report as $item)
        @php
            $data = $item['data'];
            $filter = $item['filter'];
            $charts = $item['charts'];
            $details = $item['details'];
        @endphp
        @if(isset($data['cards']))
            @include("report::extra._card")
            <br>
        @endif
    @endforeach
    </div>
    <div class="row mt-5">
        @foreach($report as $key => $item)
            @php
                $data = $item['data'];
                $filter = $item['filter'];
                $charts = $item['charts'];
                $details = $item['details'];
            @endphp
            @foreach($charts as $chart)
                @includeIf("report::charts._$chart",['chart_key' => $key])
                <h3></h3>
            @endforeach
        @endforeach
    </div>
</div>

@php
    $messgError = $errors->first();
@endphp

@push('js')
    <script src="{{asset('dashboard_assets/js/pages/features/charts/apexcharts1036.js')}}"></script>
    <script src="{{asset('dashboard_assets/custom/js/report/reportService.js')}}"></script>

    @foreach($report as $key => $data)
        <script>
            var currentForm = @json($data['filter']);
            var currentCharts = @json($data['charts']);

            @isset($data['data']['bar'])
                barChart(@json($data['data']['bar']['result']??[]),  @json($data['data']['bar']['sites']), "#bar_{{$key}}","{{$data['filter']['unit']}}", @json($data['details']))
            @endisset

            @isset($data['data']['line'])
             areaChart(@json($data['data']['line']), "#line_{{$key}}","{{$data['filter']['unit']}}", @json($data['details']))
            @endisset

            @isset($data['data']['pie'])
                @foreach($data['filter']['columns']['data'] as $column)
                    pieChart(@json($data['data']['pie'][$column]), "#{{$column}}_pie_{{$key}}","{{$data['filter']['unit']}}",@json($data['details']))
                @endforeach
            @endisset
        </script>
    @endforeach
@endpush

