@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.event_details')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('dashboard.events.index') }}" class="text-muted">@lang('dashboard.events')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.event_details')</a>
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
@if($event)
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!-- Event Header Card -->
        <div class="card card-custom mb-5">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">{{ $event->title ?? __('dashboard.no_title') }}</h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('dashboard.events.index') }}" class="btn btn-light-primary btn-sm mr-2">
                        <i class="la la-arrow-left"></i>@lang('dashboard.back_to_list')
                    </a>
                    @can('update-event')
                    <a href="{{ route('dashboard.events.edit', $event->id) }}" class="btn btn-primary btn-sm mr-2">
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
                                    <p class="text-muted">{{ $event->title ?? '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.status'):</label>
                                    <p>
                                        @if($event->status === 'pending')
                                            <span class="badge badge-warning">@lang('dashboard.pending')</span>
                                        @elseif($event->status === 'accepted')
                                            <span class="badge badge-success">@lang('dashboard.accepted')</span>
                                        @elseif($event->status === 'rejected')
                                            <span class="badge badge-danger">@lang('dashboard.rejected')</span>
                                        @else
                                            <span class="badge badge-secondary">@lang('dashboard.not_set')</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($event->status === 'rejected' && $event->rejected_reason)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold text-danger">@lang('dashboard.rejection_reason'):</label>
                                    <div class="bg-light-danger p-3 rounded">
                                        <p class="text-danger mb-0">{{ $event->rejected_reason }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.region'):</label>
                                    <p class="text-muted">{{ $event->region->name ?? '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.city'):</label>
                                    <p class="text-muted">{{ $event->city->name ?? '---' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.date_from'):</label>
                                    <p class="text-muted">{{ $event->date_from ? \Carbon\Carbon::parse($event->date_from)->format('Y-m-d') : '---' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.date_to'):</label>
                                    <p class="text-muted">{{ $event->date_to ? \Carbon\Carbon::parse($event->date_to)->format('Y-m-d') : '---' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.featured'):</label>
                                    <p>
                                        @if($event->featured)
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
                                        @if($event->active)
                                            <span class="badge badge-success">@lang('dashboard.active')</span>
                                        @else
                                            <span class="badge badge-secondary">@lang('dashboard.inactive')</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($event->ticket_link)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('dashboard.ticket_link'):</label>
                                    <p>
                                        <a href="{{ $event->ticket_link }}" target="_blank" class="text-primary">
                                            <i class="la la-external-link"></i> {{ $event->ticket_link }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($event->description)
                        <div class="form-group">
                            <label class="font-weight-bold">@lang('dashboard.description'):</label>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Status Management Card -->
                @can('update-event')
                <div class="card card-custom mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.status_management')</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dashboard.events.update_status', $event->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">@lang('dashboard.change_status'):</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="pending" {{ $event->status === 'pending' ? 'selected' : '' }}>@lang('dashboard.pending')</option>
                                            <option value="accepted" {{ $event->status === 'accepted' ? 'selected' : '' }}>@lang('dashboard.accepted')</option>
                                            <option value="rejected" {{ $event->status === 'rejected' ? 'selected' : '' }}>@lang('dashboard.rejected')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="rejection_reason_group" style="{{ $event->status === 'rejected' ? '' : 'display: none;' }}">
                                        <label class="font-weight-bold">@lang('dashboard.rejection_reason'):</label>
                                        <textarea name="rejected_reason" id="rejected_reason" class="form-control" rows="3" {{ $event->status === 'rejected' ? 'required' : '' }}>{{ $event->rejected_reason }}</textarea>
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
                @if($event->ownership_proof_file)
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
                                                <p class="mb-1 font-weight-bold">{{ basename($event->ownership_proof_file) }}</p>
                                                <div class="btn-group">
                                                    <a href="{{ $event->ownership_proof_file}}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="la la-eye"></i> @lang('dashboard.view_file')
                                                    </a>
                                                    <a href="{{ $event->ownership_proof_file }}" download class="btn btn-sm btn-success">
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
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Image Card -->
                @if($event->image)
                <div class="card card-custom mb-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">@lang('dashboard.image')</h3>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $event->image }}" alt="{{ $event->title }}" class="img-fluid rounded" style="max-height: 300px;">
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
                                    @if($event->status === 'pending')
                                        @lang('dashboard.pending')
                                    @elseif($event->status === 'accepted')
                                        @lang('dashboard.accepted')
                                    @elseif($event->status === 'rejected')
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
                                <span class="text-muted font-weight-bold">{{ $event->created_at->format('Y-m-d H:i') }}</span>
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
                                <span class="text-muted font-weight-bold">{{ $event->updated_at->format('Y-m-d H:i') }}</span>
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
                        @can('update-event')
                        <a href="{{ route('dashboard.events.edit', $event->id) }}" class="btn btn-primary btn-block mb-2">
                            <i class="la la-edit"></i> @lang('dashboard.edit_event')
                        </a>
                        @endcan

                        @can('delete-event')
                        <button class="btn btn-danger btn-block delete-button"
                                data-toggle="modal"
                                data-target="#delete_modal"
                                data-url="{{ route('dashboard.events.destroy', $event->id) }}"
                                data-item-id="{{ $event->id }}">
                            <i class="la la-trash"></i> @lang('dashboard.delete_event')
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
<!-- Event Not Found -->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-body text-center py-10">
                <div class="text-center">
                    <i class="la la-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    <h3 class="text-dark-75 mt-4">@lang('dashboard.event_not_found')</h3>
                    <p class="text-muted">@lang('dashboard.event_not_found_message')</p>
                    <a href="{{ route('dashboard.events.index') }}" class="btn btn-primary mt-4">
                        <i class="la la-arrow-left"></i> @lang('dashboard.back_to_events')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
