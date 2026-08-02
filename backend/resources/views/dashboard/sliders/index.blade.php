@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.sliders')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">{{ $title }}</a>
    </li>
</ul>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#show-enteries').on('change', function () {
            window.location.href = @json(route('dashboard.sliders.index')) + '?per_page=' + $(this).val() + '&title={{ urlencode(request('title', '')) }}';
        });
    });
</script>
@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __('dashboard.sliders') }}</h3>
                </div>
                <div class="card-toolbar">
                    @can('create-sliders')
                        <a href="{{ route('dashboard.sliders.create') }}" class="btn btn-primary">
                            <i class="la la-plus"></i> @lang('dashboard.new_slider')
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <form action="{{ route('dashboard.sliders.index') }}" method="get" style="width: -webkit-fill-available;">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <input name="title" class="form-control" placeholder="@lang('dashboard.search')" value="{{ request('title') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('dashboard.search')</button>
                        </div>
                    </div>
                </form>
            </div>

            @if($sliders->isNotEmpty())
            <div class="card-body">
                <div style="width: 200px;display: inline-flex;margin-bottom: 30px;">
                    <span style="width: 50%;padding-top: 10px;">Show Entries</span>
                    <select class="form-control" id="show-enteries" style="display: inline-block;width:50%">
                        <option {{ request('per_page') == '10' ? 'selected' : '' }} value="10">10</option>
                        <option {{ request('per_page') == '25' ? 'selected' : '' }} value="25">25</option>
                        <option {{ request('per_page') == '50' ? 'selected' : '' }} value="50">50</option>
                        <option {{ request('per_page') == '-1' ? 'selected' : '' }} value="-1">All</option>
                    </select>
                </div>
                <table class="table table-bordered dataTable2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.title')</th>
                            <th>@lang('dashboard.link')</th>
                            <th>@lang('dashboard.image')</th>
                            <th>@lang('dashboard.order_id')</th>
                            <th>@lang('dashboard.active')</th>
                            <th>@lang('dashboard.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sliders as $index => $slider)
                        <tr id="row-{{ $slider->id }}">
                            <td>{{ ($index + 1) + (($sliders->currentPage() - 1) * $sliders->perPage()) }}</td>
                            <td>{{ $slider->title ?? '---' }}</td>
                            <td>
                                @if($slider->link)
                                    <a href="{{ $slider->link }}" target="_blank" rel="noopener noreferrer">{{ \Illuminate\Support\Str::limit($slider->link, 40) }}</a>
                                @else
                                    <span class="text-muted">---</span>
                                @endif
                            </td>
                            <td>
                                @if($slider->getFirstMediaUrl('image'))
                                    <a href="{{ $slider->getFirstMediaUrl('image') }}" target="_blank">
                                        <div class="symbol symbol-60 flex-shrink-0">
                                            <div class="symbol-label" style="background-image: url({{ $slider->getFirstMediaUrl('image') }})"></div>
                                        </div>
                                    </a>
                                @else
                                    <span class="text-muted">---</span>
                                @endif
                            </td>
                            <td>{{ $slider->order_id }}</td>
                            <td>
                                @if($slider->active)
                                    <span class="label label-success label-inline">@lang('dashboard.yes')</span>
                                @else
                                    <span class="label label-secondary label-inline">@lang('dashboard.no')</span>
                                @endif
                            </td>
                            <td>
                                @can('update-sliders')
                                    <a href="{{ route('dashboard.sliders.edit', $slider->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="{{ __('dashboard.edit') }}">
                                        <i class="flaticon-edit-1 edit-icon"></i>
                                    </a>
                                @endcan
                                @can('delete-sliders')
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{ __('dashboard.delete') }}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.sliders.destroy', $slider->id) }}" data-item-id="{{ $slider->id }}">
                                        <i class="flaticon2-trash trash-icon"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! str_replace('/?', '?', $sliders->withQueryString()->render()) !!}
            </div>
            @else
            <div class="card-body">
                <div class="card-toolbar" style="text-align: center; background-color: #f2f3f8; padding: 100px; color: #101010">
                    <div style="font-size: large">
                        <i class="la la-image mb-2" style="font-size: xxx-large;"></i><br>
                        @lang('dashboard.no_data')
                    </div>
                    @can('create-sliders')
                        <a href="{{ route('dashboard.sliders.create') }}" class="btn btn-primary mt-2">
                            <i class="la la-plus"></i> @lang('dashboard.new_slider')
                        </a>
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('dashboard.includes.alerts.delete-modal')
@endsection
