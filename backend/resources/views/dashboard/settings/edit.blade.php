@extends('layouts.dashboard.master')

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.dashboard')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>

        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">{{ handleTrans($title) }}</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.general_setting')</a>
        </li>
    </ul>
@endsection

@push('js')
    <script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <script src="{{ asset('front_assets/js/repeater.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });

        $('.repeater').repeater({
            repeaters: [{
                selector: '.inner-repeater'
            }]
        });
    </script>
@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid ">
            <div class="card card-custom gutter-b ">
                <div class="card-body">
                    <div class="example-preview   mb-15">
                        <form novalidate="novalidate" method="post" class="custom-nav"
                            action="{{ route('dashboard.settings.updateSetting') }}" enctype="multipart/form-data">
                            @csrf


                            <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">

                                @forelse($settings as $key => $setting)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $key === 'app' ? 'active' : '' }}" href="#{{ $key }}"
                                            id="{{ $key }}-tab-1" data-toggle="tab">
                                            <span class="nav-icon">
                                                @if ($key === 'mail')
                                                    <i class="flaticon-mail-1 text-muted"></i>
                                                @elseif($key === 'mail_template')
                                                    <i class="text-muted">
                                                        <svg style="width:16px; height: 16px;" fill="currentColor"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="20px"
                                                            height="20px" viewBox="0 0 20 20" version="1.1">
                                                            <g id="surface1">
                                                                <path style=" stroke:none;fill-rule:evenodd;fill-opacity:1;"
                                                                    d="M 3 0 L 3 4.558594 L 0 6.820312 L 0 20 L 20 20 L 20 6.820312 L 17 4.558594 L 17 0 Z M 4 1 L 16 1 L 16 9.761719 L 11.988281 12.78125 L 10.953125 12 L 9.046875 12 L 8.007812 12.78125 L 4 9.761719 Z M 5 4 L 5 5 L 15 5 L 15 4 Z M 3 5.992188 L 3 9.007812 L 1 7.5 Z M 17 5.992188 L 19 7.5 L 17 9.007812 Z M 5 6 L 5 7 L 15 7 L 15 6 Z M 5 8 L 5 9 L 15 9 L 15 8 Z M 1 8.753906 L 7.179688 13.410156 L 1 18.066406 Z M 19 8.753906 L 19 18.066406 L 12.820312 13.410156 Z M 9.625 13 L 10.375 13 L 18.339844 19 L 1.664062 19 Z M 9.625 13 " />
                                                            </g>
                                                        </svg>
                                                    </i>
                                                @else
                                                    <i class="flaticon-settings text-muted"></i>
                                                @endif
                                            </span>
                                            <span class="nav-text">{{ __("dashboard.$key") }}</span>
                                        </a>
                                    </li>
                                @empty
                                @endforelse
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent1">
                                @forelse($settings as $group => $kSettings)
                                    <div class="tab-pane fade {{ $group === 'app' ? 'show active' : '' }}"
                                        id="{{ $group }}" role="tabpanel" aria-labelledby="{{ $group }}-tab">
                                        @if ($group == 'main_services')
                                            <div class="repeater col-12">
                                                <div data-repeater-list="SECTION_CONTENT" class="col-12">
                                        @endif
                                        @foreach ($kSettings as $index => $setting)
                                            <div class="row">
                                                @if ($setting->type === 'boolean')
                                                    <div class="col-md-2" style="margin-top: 7px">
                                                        <label
                                                            for="{{ $setting->key }}">{{ __("dashboard." .strtolower($setting->key)) }}</label>
                                                    </div>
                                                    <div class="form-group col-md-4" style="margin-top: 5px">
                                                        <label class="col" for="{{ $setting->type }}">
                                                            <input type="radio" name="{{ $setting->key }}"
                                                                value="{{ $setting->value }}"
                                                                selected="{{ $setting->value === 'true' ? 'selected' : '' }}">
                                                            True
                                                        </label>
                                                        <label class="col" for="{{ $setting->type }}">
                                                            <input type="radio" name="{{ $setting->key }}"
                                                                value="{{ $setting->value }}"
                                                                selected="{{ $setting->value === 'true' ? 'selected' : '' }}">
                                                            True
                                                        </label>
                                                    </div>
                                                @elseif($setting->type === 'textarea')
                                                    <div class="col-md-2" style="margin-top: 7px">
                                                        <label
                                                            for="{{ $setting->key }}">{{ __("dashboard." .strtolower($setting->key)) }}</label>
                                                    </div>
                                                    <div class="form-group col-md-4" style="margin-top: 5px">
                                                        <textarea class="form-control" name="{{ $setting->key }}" cols="20" rows="10">{{ $setting->value }}
                                                        </textarea>
                                                    </div>
                                                @elseif($setting->type === 'file')
                                                    <div class="col-md-2" style="margin-top: 7px">
                                                        <label
                                                            for="{{ $setting->key }}">{{ str_replace('_', ' ', __("dashboard.$setting->key")) }}</label>
                                                    </div>
                                                    <div class="form-group col-md-4" style="margin-top: 5px">
                                                        @if ($setting->value != null)
                                                            <img src="{{ env('APP_URL') . Storage::url($setting->value) }}"
                                                                style="max-height: 60px;cursor: pointer; object-fit:scale-down;
                                                                    width:200px;
                                                                    height:300px;
                                                                    border: solid 1px #CCC"
                                                                alt="{{ $setting->key }}">
                                                            <input type="file" class="form-control"
                                                                id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                                value="{{ $setting->value }}" />
                                                        @else
                                                            <img src="{{ env('APP_URL') . Storage::url('media/default_logo.png') }}"
                                                                style="max-height: 60px;cursor: pointer; object-fit:scale-down;
                                                                    width:200px;
                                                                    height:300px;
                                                                    border: solid 1px #CCC"
                                                                alt="{{ $setting->key }}">
                                                            <input type="file" class="form-control"
                                                                id="{{ $setting->key }}" name="{{ $setting->key }}"
                                                                value="{{ $setting->value }}" />
                                                        @endif

                                                    </div>
                                                @elseif($setting->type === 'text' || $setting->type === 'number' || $setting->type === 'password')
                                                    <div class="col-md-2" style="margin-top: 7px">
                                                        <label
                                                            for="{{ $setting->key }}">{{ __("dashboard." .strtolower($setting->key)) }}</label>
                                                    </div>
                                                    <div class="form-group col-md-4" style="margin-top: 5px">
                                                        <input class="form-control" type="{{ $setting->type }}"
                                                            name="{{ $setting->key }}" value="{{ $setting->value }}">
                                                    </div>
                                                @elseif($setting->type === 'repeater')
                                                    <div data-repeater-item class="row col-12">
                                                        <div class="form-group col-2 ">
                                                            <label
                                                                for="{{ $setting->key }}">{{ str_replace('_', ' ', __("dashboard.$setting->key")) }}</label>
                                                        </div>
                                                        <div class="col-4">
                                                            <input class="form-control" type="{{ $setting->type }}"
                                                                name="SECTION_CONTENT"
                                                                value="{{ $setting->value }}">

                                                        </div>
                                                        <a class="col-2" href="javascript:void(0);"
                                                            data-repeater-delete>Remove
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if ($group == 'main_services')
                                    </div>

                                    <div class="form-group">
                                        <div class="col p-0">
                                            <button id="repeater-add" class="btn btn-primary" data-repeater-create
                                                type="button"><i class="os-icon os-icon-folder-plus"></i>
                                                Add
                                            </button>
                                        </div>
                                    </div>
                            </div>
                            @endif
                    </div>
                @empty
                    @endforelse
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">@lang('dashboard.update')</button>
                </div>

                </form>
            </div>
        </div>
    </div>


    </div>
    </div>
@endsection
