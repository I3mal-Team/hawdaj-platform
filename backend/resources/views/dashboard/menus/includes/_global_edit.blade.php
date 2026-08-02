<form id="form"  class="kt-form kt-form--label-right" method="post"
      action="{{ route('dashboard.menu.update', $menu->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
{{-- novalidate="novalidate" --}}
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex flex-column-fluid">
                <div class="container-fluid ">
                    <div class="tab-danger pr-10">
                        <!-- Nav Tabs -->
                        <ul class="nav nav-tabs custom-nav-tabs" id="myTab1" role="tablist">
                            @foreach(locales() as $locale)
                                <li class="nav-item">
                                    <a class="nav-link {{$locale == "ar" ? "active" :""}}"
                                       id="tab-{{$locale}}"
                                       data-toggle="tab"
                                       href="#{{$locale}}">
                                        @lang("dashboard." . $locale)
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <!-- Tab Panels -->
                        <div class="tab-content p-4" id="myTabContent">
                            @foreach(locales() as $locale)
                                <div
                                    role="tabpanel"
                                    class="tab-pane fade {{$locale == 'ar' ? 'show active' : ''}}"
                                    aria-labelledby="tab-{{$locale}}"
                                    id="{{$locale}}"
                                >
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.title')</label>
                                        <span class="text-danger"> * </span>
                                        <div class="input-group">
                                            <input type="text" name="{{$locale}}[title]"
                                                   value="{{$menu->translate($locale)->title ?? ''}}"
                                                   class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                                   placeholder="@lang('dashboard.enter') @lang('dashboard.title') ">
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Body -->
                                    <div class="form-group validated">
                                        <label>@lang('dashboard.description')</label>
                                        <span class="text-danger"> * </span>
                                        <input
                                            class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                            name="{{$locale}}[description]"
                                            value="{{ $menu->translate($locale)->description ?? '' }}">
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- End Tab Panels -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.zad_elgadels')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="zad_id" class="form-control select2" id="" >
                            @foreach($zads as $zad)
                                <option value="{{ $zad->id }}"
                                        @if($zad->id == $menu->zad_id) selected @endif>{{ $zad->title ?? '---' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('zad_id.*') ? $errors->first('zad_id.*') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.price')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input value="{{ $menu->price }}" type="text" name="price" class="form-control">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('price') ? $errors->first('price') : '' }}</strong>
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
                            <img src="{{ asset('uploads/'.$menu->image ) }}" />
                        </div>
                    </div>
                </div>
            </div>

            @include('dashboard.includes.partials._order_id_field', ['orderIdModel' => $menu])

        </div>
    </div>
    <div class="card-footer">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
        </div>
    </div>

</form>
