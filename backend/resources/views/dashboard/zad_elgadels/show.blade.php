@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.zad_elgadel_details')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('dashboard.zad_elgadels.index') }}" class="text-muted">@lang('dashboard.zad_elgadels')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.zad_elgadel_details')</a>
    </li>
</ul>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#status').change(function() {
        if ($(this).val() === 'rejected') {
            $('#rejection_reason_group').show();
            $('#rejected_reason').attr('required', true);
        } else {
            $('#rejection_reason_group').hide();
            $('#rejected_reason').attr('required', false);
        }
    });
});
</script>
@endpush

@section('content')
@if($zad_elgadel)
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!-- ZadElgadel Header Card -->
        <div class="card card-custom mb-5">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">{{ $zad_elgadel->title ?? __('dashboard.no_title') }}</h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('dashboard.zad_elgadels.index') }}" class="btn btn-light-primary btn-sm mr-2">
                        <i class="la la-arrow-left"></i>@lang('dashboard.back_to_list')
                    </a>
                    @can('update-zadElgadel')
                    <a href="{{ route('dashboard.zad_elgadels.edit', $zad_elgadel->id) }}" class="btn btn-primary btn-sm mr-2">
                        <i class="la la-edit"></i>@lang('dashboard.edit')
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Information -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card card-custom mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.basic_information')</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.title'):</label>
                                    <p class="text-muted">{{ $zad_elgadel->title ?? '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.status'):</label>
                                    <p>
                                        @if($zad_elgadel->status === 'pending')
                                            <span class="badge badge-warning">@lang('dashboard.pending')</span>
                                        @elseif($zad_elgadel->status === 'accepted')
                                            <span class="badge badge-success">@lang('dashboard.accepted')</span>
                                        @elseif($zad_elgadel->status === 'rejected')
                                            <span class="badge badge-danger">@lang('dashboard.rejected')</span>
                                        @else
                                            <span class="badge badge-secondary">@lang('dashboard.not_set')</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($zad_elgadel->status === 'rejected' && $zad_elgadel->rejected_reason)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-danger">@lang('dashboard.rejection_reason'):</label>
                                    <div class="bg-light-danger p-3 rounded">
                                        <p class="text-danger mb-0">{{ $zad_elgadel->rejected_reason }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.region'):</label>
                                    <p class="text-muted">{{ $zad_elgadel->region->name ?? '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.city'):</label>
                                    <p class="text-muted">{{ $zad_elgadel->city->name ?? '---' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.featured'):</label>
                                    <p>
                                        @if($zad_elgadel->featured)
                                            <span class="badge badge-primary">@lang('dashboard.yes')</span>
                                        @else
                                            <span class="badge badge-secondary">@lang('dashboard.no')</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.active'):</label>
                                    <p>
                                        @if($zad_elgadel->active)
                                            <span class="badge badge-success">@lang('dashboard.active')</span>
                                        @else
                                            <span class="badge badge-secondary">@lang('dashboard.inactive')</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($zad_elgadel->description)
                        <div class="form-group">
                            <label class="font-weight-bold">@lang('dashboard.description'):</label>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($zad_elgadel->description)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Status Management Card -->
                @can('update-zadElgadel')
                <div class="card card-custom mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.status_management')</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.zad_elgadels.update_status', $zad_elgadel->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">@lang('dashboard.change_status'):</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="pending" {{ $zad_elgadel->status === 'pending' ? 'selected' : '' }}>@lang('dashboard.pending')</option>
                                            <option value="accepted" {{ $zad_elgadel->status === 'accepted' ? 'selected' : '' }}>@lang('dashboard.accepted')</option>
                                            <option value="rejected" {{ $zad_elgadel->status === 'rejected' ? 'selected' : '' }}>@lang('dashboard.rejected')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="rejection_reason_group" style="{{ $zad_elgadel->status === 'rejected' ? '' : 'display: none;' }}">
                                        <label class="font-weight-bold">@lang('dashboard.rejection_reason'):</label>
                                        <textarea name="rejected_reason" id="rejected_reason" class="form-control" rows="3" {{ $zad_elgadel->status === 'rejected' ? 'required' : '' }}>{{ $zad_elgadel->rejected_reason }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="la la-save"></i> @lang('dashboard.update_status')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endcan

                <!-- Ownership Proof File -->
                @if($zad_elgadel->ownership_proof_file)
                <div class="card card-custom mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.ownership_proof_file')</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.ownership_proof_document'):</label>
                                    <div class="mt-2">
                                        <div class="d-flex align-items-center">
                                            <i class="la la-file-text-o text-primary mr-2" style="font-size: 2rem;"></i>
                                            <div>
                                                <p class="mb-1 font-weight-bold">{{ basename($zad_elgadel->ownership_proof_file) }}</p>
                                                <div class="btn-group">
                                                    <a href="{{ $zad_elgadel->ownership_proof_file}}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="la la-eye"></i> @lang('dashboard.view_file')
                                                    </a>
                                                    <a href="{{ $zad_elgadel->ownership_proof_file }}" download class="btn btn-sm btn-success">
                                                        <i class="la la-download"></i> @lang('dashboard.download_file')
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Categories Card -->
                <div class="card card-custom mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.categories')</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.categories'):</label>
                                    <div class="mt-2">
                                        @if($zad_elgadel->categories && count($zad_elgadel->categories) > 0)
                                            @foreach($zad_elgadel->categories as $category)
                                                <span class="badge badge-primary mr-1 mb-1">{{ $category->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">@lang('dashboard.no_categories')</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.food_categories'):</label>
                                    <div class="mt-2">
                                        @if($zad_elgadel->foodCategories && count($zad_elgadel->foodCategories) > 0)
                                            @foreach($zad_elgadel->foodCategories as $foodCategory)
                                                <span class="badge badge-info mr-1 mb-1">{{ $foodCategory->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">@lang('dashboard.no_food_categories')</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Image Card -->
                @if($zad_elgadel->image)
                <div class="card card-custom mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.image')</h3>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $zad_elgadel->image }}" alt="{{ $zad_elgadel->title }}" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                </div>
                @endif

                <!-- Menu File Card -->
                @if($zad_elgadel->menu_file)
                <div class="card card-custom mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.menu_file')</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="la la-file-pdf-o text-danger mr-2" style="font-size: 2rem;"></i>
                            <div>
                                <p class="mb-1 font-weight-bold">{{ basename($zad_elgadel->menu_file) }}</p>
                                <div class="btn-group">
                                    <a href="{{ $zad_elgadel->menu_file }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="la la-eye"></i> @lang('dashboard.view_file')
                                    </a>
                                    <a href="{{ $zad_elgadel->menu_file }}" download class="btn btn-sm btn-success">
                                        <i class="la la-download"></i> @lang('dashboard.download_file')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Statistics Card -->
                <div class="card card-custom mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.statistics')</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40 symbol-light-info mr-4">
                                <span class="symbol-label">
                                    <i class="la la-flag text-info"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="text-dark-75 font-weight-bolder font-size-sm">@lang('dashboard.current_status')</span>
                                <span class="text-muted font-weight-bold">
                                    @if($zad_elgadel->status === 'pending')
                                        @lang('dashboard.pending')
                                    @elseif($zad_elgadel->status === 'accepted')
                                        @lang('dashboard.accepted')
                                    @elseif($zad_elgadel->status === 'rejected')
                                        @lang('dashboard.rejected')
                                    @else
                                        @lang('dashboard.not_set')
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="symbol symbol-40 symbol-light-success mr-4">
                                <span class="symbol-label">
                                    <i class="la la-calendar text-success"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="text-dark-75 font-weight-bolder font-size-sm">@lang('dashboard.created_at')</span>
                                <span class="text-muted font-weight-bold">{{ $zad_elgadel->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40 symbol-light-warning mr-4">
                                <span class="symbol-label">
                                    <i class="la la-edit text-warning"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="text-dark-75 font-weight-bolder font-size-sm">@lang('dashboard.updated_at')</span>
                                <span class="text-muted font-weight-bold">{{ $zad_elgadel->updated_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.quick_actions')</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        @can('update-zadElgadel')
                        <a href="{{ route('dashboard.zad_elgadels.edit', $zad_elgadel->id) }}" class="btn btn-primary btn-block mb-2">
                            <i class="la la-edit"></i> @lang('dashboard.edit_zad_elgadel')
                        </a>
                        @endcan

                        @can('delete-zadElgadel')
                        <button class="btn btn-danger btn-block delete-button"
                                data-toggle="modal"
                                data-target="#delete_modal"
                                data-url="{{ route('dashboard.zad_elgadels.destroy', $zad_elgadel->id) }}"
                                data-item-id="{{ $zad_elgadel->id }}">
                            <i class="la la-trash"></i> @lang('dashboard.delete_zad_elgadel')
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
@include('dashboard.includes.alerts.delete-modal')

@else
<!-- ZadElgadel Not Found -->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-body text-center py-10">
                <div class="text-center">
                    <i class="la la-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    <h3 class="text-dark-75 mt-4">@lang('dashboard.zad_elgadel_not_found')</h3>
                    <p class="text-muted">@lang('dashboard.zad_elgadel_not_found_message')</p>
                    <a href="{{ route('dashboard.zad_elgadels.index') }}" class="btn btn-primary mt-4">
                        <i class="la la-arrow-left"></i> @lang('dashboard.back_to_zad_elgadels')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
