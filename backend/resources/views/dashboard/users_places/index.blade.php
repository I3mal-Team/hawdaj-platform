@extends('layouts.dashboard.master')

@push('js')
<script>
    $('.accept-btn').click(function() {
        var place_id = $(this).data('item-id');
        var $btn = $(this);
        
        if ($btn.prop('disabled')) {
            return false;
        }
        
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> جاري المعالجة...');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{Route('dashboard.users_places.active')}}",
            method: 'POST',
            data: {
                'checked': 1,
                'place_id': place_id,
            },
            success: function(data) {
                if (data.status) {
                    toastr.success(data.message);
                    
                    if (data.deleted && data.id) {
                        $('#row-' + data.id).fadeOut(500, function() {
                            $(this).remove();
                            
                            if ($('tbody tr').length === 0) {
                                location.reload();
                            }
                        });
                    }
                } else {
                    toastr.error(data.message || 'حدث خطأ ما');
                    $btn.prop('disabled', false).html('قبول');
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON?.message || 'حدث خطأ ما';
                toastr.error(message);
                $btn.prop('disabled', false).html('قبول');
            }
        });
    });
</script>
@endpush

@section('page_header')
<style>
    span.select2-selection.select2-selection--single {
        height: 40px;
    }
</style>
<h5 class="text-dark font-weight-bold my-1 mr-5">{{ $title }}</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">{{ $title }}</a>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{__('dashboard.places_list')}}</h3>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('dashboard.users_places.index') }}" method="get" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('dashboard.type')</label>
                                <select name="type" class="form-control">
                                    <option value="">الكل</option>
                                    <option value="place" {{ request('type') == 'place' ? 'selected' : '' }}>@lang('dashboard.place')</option>
                                    <option value="store" {{ request('type') == 'store' ? 'selected' : '' }}>@lang('dashboard.store')</option>
                                    <option value="zad" {{ request('type') == 'zad' ? 'selected' : '' }}>@lang('dashboard.zad')</option>
                                    <option value="swalef" {{ request('type') == 'swalef' ? 'selected' : '' }}>@lang('dashboard.swalef')</option>
                                    <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>@lang('dashboard.event')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('dashboard.search')</label>
                                <input type="text" name="search" class="form-control" 
                                       value="{{ request('search') }}" 
                                       placeholder="@lang('dashboard.search') (@lang('dashboard.name') / @lang('dashboard.mobile') / @lang('dashboard.user_name'))">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-primary">@lang('dashboard.search')</button>
                                    <a href="{{ route('dashboard.users_places.index') }}" class="btn btn-secondary">@lang('dashboard.reset')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @if(!empty($places->first()))
                <div style="width: 200px;display: inline-flex;margin-bottom: 30px;">
                    <span style="width: 50%;padding-top: 10px;">Show Entries</span>
                    <select class="form-control" id="show-enteries" style="display: inline-block;width:50%">
                        <option {{ Request::get('per_page') == '10' ? 'selected' : '' }} value="10">10</option>
                        <option {{ Request::get('per_page') == '25' ? 'selected' : '' }}  value="25">25</option>
                        <option {{ Request::get('per_page') == '50' ? 'selected' : '' }}  value="50">50</option>
                        <option {{ Request::get('per_page') == '-1' ? 'selected' : '' }}  value="-1">All</option>
                    </select>
                </div>
                <!--begin: Datatable-->
                <table class="table table-bordered dataTable1" id="">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('dashboard.type')</th>
                            <th>@lang('dashboard.name')</th>
                            <th>@lang('dashboard.description')</th>
                            <th>@lang('dashboard.address')</th>
                            <th>@lang('dashboard.image')</th>
                            <th>@lang('dashboard.user_name')</th>
{{--                            @if(auth()->user()->can('patch-users_place'))--}}
                                <th>@lang('dashboard.status')</th>
{{--                            @endif--}}
                            <th>@lang('dashboard.action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($places->first()))
                            @foreach($places as $index => $place)
                            <tr id="row-{{$place->id}}">
                                <td>{{($index + 1) + ((request('page' , 1)-1) * request('per_page' , 10)) }}</td>
                                <td>{{ trans('dashboard.' . $place->type) ?? '---'}}</td>
                                <td>{{ $place->title ?? '---'}}</td>
                                <td>
                                    @php
                                        $description = strip_tags($place->description ?? '');
                                        if ($description) {
                                            preg_match_all('/[^.!?]+[.!?]+/', $description, $matches);
                                            $sentences = $matches[0] ?? [];
                                            $firstThree = array_slice($sentences, 0, 3);
                                            echo trim(implode('', $firstThree)) ?: substr($description, 0, 100) . '...';
                                        } else {
                                            echo '---';
                                        }
                                    @endphp
                                </td>
                                <td>{{ $place->address ?? '---'}}</td>
                                <td>
                                    @php
                                        $imageUrl = null;
                                        if ($place->hasMedia('image')) {
                                            $imageUrl = $place->getFirstMediaUrl('image');
                                        } elseif ($place->image) {
                                            $imageUrl = resolvePhoto($place->image);
                                        } else {
                                            $descriptionImage = extractImageFromHtml($place->description ?? '');
                                            if ($descriptionImage) {
                                                $imageUrl = $descriptionImage;
                                            }
                                        }
                                        
                                        $defaultImage = asset('front_assets/imgs/zad1.jpg');
                                    @endphp
                                    @if($imageUrl)
                                    <a href="{{ $imageUrl }}" target="_blank">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-60 flex-shrink-0">
                                                <img src="{{ $imageUrl }}" alt="{{ $place->title }}" 
                                                     onerror="this.src='{{ $defaultImage }}'"
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                            </div>
                                        </div>
                                    </a>
                                    @else
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-60 flex-shrink-0">
                                            <img src="{{ $defaultImage }}" alt="Default" 
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; opacity: 0.5;">
                                        </div>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if($place->user)
                                        {{ $place->user->first_name . ' ' . $place->user->last_name }}
                                        @if($place->user->phone)
                                            <br><small class="text-muted">{{ $place->user->phone }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">---</span>
                                    @endif
                                </td>
                                    <td class="text-center">
                                        @if(!$place->active)
                                            <button type="button" class="btn btn-sm btn-success accept-btn" data-item-id="{{$place->id}}">
                                                قبول
                                            </button>
                                        @else
                                            <span class="badge badge-success">مفعل</span>
                                        @endif
                                    </td>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md" title="عرض التفاصيل" href="{{ route('dashboard.users_places.show', $place->id) }}">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button" title="{{__('dashboard.delete')}}" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('dashboard.users_places.force_destroy',$place->id) }}" data-item-id="{{ $place->id }}">
                                        <i class="flaticon2-trash trash-icon"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
                {!! str_replace('/?', '?', $places->withQueryString()->render()) !!}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- begin: delete modal -->
@include('dashboard.includes.alerts.delete-modal')
@include('dashboard.includes.alerts.delete-selected-modal')
@include('dashboard.includes.alerts.restore-modal')
<!-- end:: delete modal -->
@endsection
