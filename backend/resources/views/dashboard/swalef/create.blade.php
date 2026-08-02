@extends('layouts.dashboard.master')

@section('page_header')
<h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.swalefs')</h5>
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.dashboard')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="{{url('swalefs')}}" class="text-muted">@lang('dashboard.swalefs')</a>
    </li>
    <li class="breadcrumb-item text-muted">
        <a href="javascript:;" class="text-muted">@lang('dashboard.create_swalef')</a>
    </li>
</ul>
@endsection

@push('js')
<script>
    $(function() {
        $('#type').on('change', function() {
            let selected_option = $(this).find(':selected').data('name');

            if (selected_option == 'text') {
                $("#textarea").hide();
                $("#textarea").attr('disabled' , 'disabled');
                $(".file_input").hide();
                $(".file_input").attr('disabled' , 'disabled');
                $("#text").show();
                $("#text").removeAttr('disabled');
                $("#content_section").show();

            } else if (selected_option == 'textarea') {
                $("#text").hide();
                $("#text").attr('disabled' , 'disabled');

                $(".file_input").hide();
                $(".file_input").attr('disabled' , 'disabled');

                $("#textarea").show();
                $("#textarea").removeAttr('disabled');

                $("#content_section").show();
            } else if (selected_option == 'file') {
                $("#textarea").hide();
                $("#textarea").attr('disabled' , 'disabled');

                $("#text").hide();
                $("#text").attr('disabled' , 'disabled');

                $(".file_input").show();
                $(".file_input").removeAttr('disabled');

                $("#content_section").show();
            } else {
                $("#content_section").hide();
            }
        });

    });
</script>
@endpush

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-custom gutter-b ">
                    <div class="card-header">
                        <h3 class="card-title">@lang('dashboard.create_swalef')</h3>
                        <div class="card-toolbar">
                            <a href="{{route('dashboard.swalefs.index')}}" class="btn btn-primary ">
                                <i class="flaticon2-reply-1" style="font-size: 1rem;"></i> @lang('dashboard.back')
                            </a>
                        </div>
                    </div>
                    <form novalidate="novalidate" class="kt-form kt-form--label-right" method="post" action="{{ route('dashboard.swalefs.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.title') </label> <span class="text-danger"> *</span>
                                        <div class="input-group">
                                            <input type="text" name="title" value="{{old("title")}}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.title') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.description') </label> <span class="text-danger"> *</span>
                                        <div class="input-group">
                                            <textarea class="description form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description">{{ old('description') }}</textarea>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.content') </label> <span class="text-danger"></span>
                                        <div class="input-group">
                                            <textarea class="description form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" name="content">{{ old('content') }}</textarea>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('content') ? $errors->first('content') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang("dashboard.type")</label>
                                        <span class="text-danger"> *</span>
                                        <select id="type" class="form-control select2 " name="type">
                                            <option value="">@lang('dashboard.select_type')</option>
                                            @foreach($types as $k => $type)
                                            <option value="{{$type}}" data-name="{{strtolower($type)}}" {{ $type == old("type") ? 'selected' : '' }}>
                                                {{$k}}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger" style="margin-right: 6px !important; margin-top: 11px; ">
                                            <strong>{{ $errors->has('type') ? $errors->first('type') : '' }}</strong>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.categories')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <select name="categories[]" class="form-control select2" id="" multiple>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{in_array($category->id, old('categories') ?? []) ? 'selected' : ''}}>
                                                        {{ $category->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('categories') ? $errors->first('categories') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.timePeriod') </label>
                                        <div class="input-group">
                                            <input type="text" name="timePeriod" value="{{old("timePeriod")}}" class="form-control {{ $errors->has('timePeriod') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.timePeriod') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('timePeriod') ? $errors->first('timePeriod') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.location_name') </label>
                                        <div class="input-group">
                                            <input type="text" name="location" value="{{old("location")}}" class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.location_name') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('location') ? $errors->first('location') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.location_map') </label>
                                        <div class="input-group">
                                            <input type="text" name="address" value="{{old("address")}}" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.location_map') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('address') ? $errors->first('address') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.storyImportance') </label>
                                        <div class="input-group">
                                            <input type="text" name="storyImportance" value="{{old("storyImportance")}}" class="form-control {{ $errors->has('storyImportance') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.storyImportance') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('storyImportance') ? $errors->first('storyImportance') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.relevanceToPresent') </label>
                                        <div class="input-group">
                                            <input type="text" name="relevanceToPresent" value="{{old("relevanceToPresent")}}" class="form-control {{ $errors->has('relevanceToPresent') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.relevanceToPresent') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('relevanceToPresent') ? $errors->first('relevanceToPresent') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.source') </label>
                                        <div class="input-group">
                                            <input type="text" name="source" value="{{old("source")}}" class="form-control {{ $errors->has('source') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.source') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('source') ? $errors->first('source') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.audioStoryTitle') </label>
                                        <div class="input-group">
                                            <input type="text" name="audioStoryTitle" value="{{old("audioStoryTitle")}}" class="form-control {{ $errors->has('audioStoryTitle') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.audioStoryTitle') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('audioStoryTitle') ? $errors->first('audioStoryTitle') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.audioStoryLink') </label>
                                        <div class="input-group">
                                            <input type="text" name="audioStoryLink" value="{{old("audioStoryLink")}}" class="form-control {{ $errors->has('audioStoryLink') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.audioStoryLink') " aria-describedby="basic-addon1" />
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('audioStoryLink') ? $errors->first('audioStoryLink') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.mainCharacters')</label>
                                        <div class="input-group">
                                            <input type="text" name="mainCharacters" value="{{ old('mainCharacters') }}" class="form-control {{ $errors->has('mainCharacters') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.mainCharacters')" aria-describedby="basic-addon1" />
                                        </div>
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $errors->has('mainCharacters') ? $errors->first('mainCharacters') : '' }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.images')</label> <span class="text-danger">*</span>
                                        <div class="input-group">
                                            <input
                                                type="file"
                                                name="images[]"
                                                id="images"
                                                class="form-control {{ $errors->has('images') ? 'is-invalid' : '' }}"
                                                placeholder="@lang('dashboard.uploadImages')"
                                                aria-describedby="basic-addon1"
                                                multiple
                                                accept="image/*"
                                            />
                                        </div>
                                        <div class="invalid-feedback d-block">
                                            <strong>{{ $errors->has('images') ? $errors->first('images') : '' }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div id="preview-images" class="mt-2"></div>



                                <div class="col-md-6">
                                    <div class="form-group row validated">
                                        <div class="col-md-10">
                                            <label>{{__('dashboard.image')}}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">
                                                        <i class="flaticon2-image-file"></i>
                                                    </span>
                                                </div>
                                                <input type="file" name="image" class="form-control file {{ $errors->has('image') ? 'is-invalid' : '' }}" placeholder="{{__('dashboard.enter')}} {{__('dashboard.swalef_image')}}" aria-describedby="basic-addon1">
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->has('image') ? $errors->first('image') : '' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 image">
                                            <div class="image_prev_form thumb-output">
                                                <img src="{{resolvePhoto('dashboard_assets/media/swalefs/default.jpg')}}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <span class="switch switch-outline switch-icon switch-success">
                                        <label style="margin:15px">@lang('dashboard.status') : </label>
                                        <label>
                                            <input type="checkbox"  {{old('active' , '') ? 'checked' : ''}} name="active"  />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>

                                <div class="col-3">

                                    <span class="switch switch-outline switch-icon switch-success">
                                        <label style="margin:15px">@lang('dashboard.featured') : </label>
                                        <label>
                                            <input type="checkbox" {{old('featured' , '') ? 'checked' : ''}} name="featured" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>

                                @include('dashboard.includes.partials._order_id_field')

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                            <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('js')
    <script>
        document.getElementById('images').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('preview-images');
            previewContainer.innerHTML = ''; // Clear previews

            Array.from(event.target.files).forEach(file => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.margin = '5px';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });
    </script>

@endpush
