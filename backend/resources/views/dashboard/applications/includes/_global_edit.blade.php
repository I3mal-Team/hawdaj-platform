<form id="form"  class="kt-form kt-form--label-right" method="post"
      action="{{ route('dashboard.applications.update', $application->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.title')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input type="text" name="title" value="{{$application->translate(default_lang())->title}}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.title') ">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.description')</label>
                    <span class="text-danger"> * </span>
                    <textarea class="form-control description {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" rows="5" placeholder="@lang('dashboard.enter') @lang('dashboard.description') ">{{ $application->translate(default_lang())->description }}</textarea>
                    <div class="invalid-feedback">
                        <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
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
                                <option value="{{ $category->id }}"
                                        @if(is_array($application->categories) && in_array($category->id ,$application->categories)) selected @endif>{{ $category->name ?? '---' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('categories.*') ? $errors->first('categories.*') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.type')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select id="app_type" class="form-control select2" name="type">
                            <option value="">{{ __('dashboard.select_type') }}</option>
                            <option value="app" {{ $application->type == 'app' ? 'selected ' : '' }}>
                                {{ __('dashboard.app_type') }}</option>
                            <option value="web" {{ $application->type == 'web' ? 'selected ' : '' }}>
                                {{ __('dashboard.web') }}</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('type') ? $errors->first('type') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div id="web" class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.application_link')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input type="text" name="link" value="{{$application->link ?? ''}}" class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.application_link') " aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('link') ? $errors->first('link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div id="ios" class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.application_ios_link')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input type="text" name="ios_link" value="{{$application->ios_link ?? ''}}" class="form-control {{ $errors->has('ios_link') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.application_link') " aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('ios_link') ? $errors->first('ios_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div id="android" class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.application_android_link')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input type="text" name="android_link" value="{{$application->android_link ?? ''}}" class="form-control {{ $errors->has('android_link') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.application_link') " aria-describedby="basic-addon1">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('android_link') ? $errors->first('android_link') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mt-4">
                <div class="form-group row validated">
                    <div class="col-md-10">
                        <label>{{__('dashboard.image')}}</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="flaticon2-image-file"></i>
                                </span>
                            </div>
                            <input type="file" name="image" accept=".png , .jpg, .jpeg" class="form-control file {{ $errors->has('image') ? 'is-invalid' : '' }}" placeholder="{{__('dashboard.enter')}} {{__('dashboard.image')}}">
                            <div class="invalid-feedback">
                                <strong>{{ $errors->has('image') ? $errors->first('image') : '' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 image">
                        <div class="image_prev_form thumb-output">
                            <img src="{{ asset($application->image) }}" />
                        </div>
                    </div>
                </div>
            </div>

            @include('dashboard.includes.partials._order_id_field', ['orderIdModel' => $application])

        </div>
    </div>
    <div class="card-footer">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
        </div>
    </div>

</form>
