<form id="form"  class="kt-form kt-form--label-right" method="post"
      action="{{ route('dashboard.offer.update', $offer->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card-body">
        <div class="row">

            <div class="col-md-12">
                <div class="form-group validated">
                    <label>@lang('dashboard.title')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input type="text" name="title" value="{{$offer->translate(default_lang())->title}}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" placeholder="@lang('dashboard.enter') @lang('dashboard.title') ">
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
                    <textarea class="form-control description {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" rows="5" placeholder="@lang('dashboard.enter') @lang('dashboard.description') ">{{ $offer->translate(default_lang())->description }}</textarea>
                    <div class="invalid-feedback">
                        <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.zad_elgadels')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <select name="menu_id" class="form-control select2" id="" >
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}"
                                        @if($menu->id == $offer->menu_id) selected @endif>{{ $menu->title ?? '---' }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('menu_id.*') ? $errors->first('menu_id.*') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.discount')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input value="{{ $offer->discount }}" type="text" name="discount" class="form-control">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('discount') ? $errors->first('discount') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.from')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input value="{{ $offer->from }}" type="date" name="from" class="form-control">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('from') ? $errors->first('from') : '' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group validated">
                    <label>@lang('dashboard.to')</label>
                    <span class="text-danger"> * </span>
                    <div class="input-group">
                        <input value="{{ $offer->to }}" type="date" name="to" class="form-control">
                        <div class="invalid-feedback">
                            <strong>{{ $errors->has('to') ? $errors->first('to') : '' }}</strong>
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
                            <img src="{{ asset('uploads/'.$menu->image ) }}"" />
                        </div>
                    </div>
                </div>
            </div>

            @include('dashboard.includes.partials._order_id_field', ['orderIdModel' => $offer])

        </div>
    </div>
    <div class="card-footer">
        <div class="kt-form__actions">
            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
        </div>
    </div>

</form>
