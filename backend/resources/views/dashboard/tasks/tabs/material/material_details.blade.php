<div class="card  card-custom card-shadowless gutter-b mb-2 ">
{{--    <h3 class="text-muted text-center">--}}
{{--        {{ __("dashboard.".$materialRequest->type) }}--}}
{{--    </h3>--}}
    <div class="card-body">
        @if($materialRequest->requests->isNotEmpty())
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th>{{ __('dashboard.name') }}</th>
                    <th>{{ __('dashboard.description') }}</th>
                    <th>{{ __('dashboard.qty') }}</th>
                    <th>{{ __('dashboard.status') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($materialRequest->requests as $index => $material)
                    <tr>
                        <td>{{ $material->name }}</td>
                        <td>{{ $material->description }}</td>
                        <td>{{ $material->quantity }}</td>
                        <td>{{ $material->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="mt-5 text-center">
                <img
                    src="{{ asset('dashboard_assets/media/no-data.png') }}"
                    alt=""
                />
            </div>
        @endif
    </div>
</div>
