<form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="POST"
      action="{{ route('dashboard.SaveCeo') }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <strong class="text-muted">{{ __('dashboard.ceo') }}</strong>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group validated">
                                <label>@lang('dashboard.title')</label>
                                <span class="text-danger"> * </span>
                                <div class="input-group">
                                    <input type="text" name="title" value="{{ $data->ceo->title ?? ''}}"
                                           class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                           placeholder="@lang('dashboard.enter') @lang('dashboard.title') "
                                           aria-describedby="basic-addon1">
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group validated">
                                <label>@lang('dashboard.link')</label>
                                <span class="text-danger"> * </span>
                                <div class="input-group">
                                    <input type="text" name="link" value="{{$data->ceo->link ?? ''}}"
                                           class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}"
                                           placeholder="@lang('dashboard.enter') @lang('dashboard.link') "
                                           aria-describedby="basic-addon1">
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->has('link') ? $errors->first('link') : '' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group validated">
                                <label>@lang('dashboard.description')</label>
                                <span class="text-danger"> * </span>
                                <div class="input-group">
                                            <textarea
                                                class="form-control description-seo {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                                name="description" rows="5"> {{ $data->ceo->description ?? '' }} </textarea>
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->has('description') ? $errors->first('description') : '' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group validated">
                                <label>@lang('dashboard.key_words')</label>
                                <span class="text-danger"> * </span>
                                <div class="input-group">
                                    <input type="hidden" name="parent_id" value="{{ $data->id }}" id="">
                                    <input type="hidden" name="type" value="{{ Request::segment(2) }}" id="">
                                    <select class="form-control insertion" name="key_words[]" multiple="multiple" >
                                        @if(isset($data->ceo->key_words) && count($data->ceo->key_words) > 0)
                                            @foreach($data->ceo->key_words as $ceo)
                                                <option selected="selected">{{$ceo?? ''}}</option>
                                            @endforeach
                                        @else


                                        @endif
                                    </select>


                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->has('key_words') ? $errors->first('key_words') : '' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 preview_ceo">
                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-hover-dark-o-10 text-hover-light">
                                <div class="card-body grid-padding-x">
                                    <div class="card-text">
                                        <h4 class="mb-4"><a href="#">{{ $data->ceo->title ?? '' }}</a></h4>
                                        <strong class="mb-4">{{ url($data->type.'/'.$data->id) ?? '' }}</strong>
                                        <p class="mb-4">{{ $data->ceo->description ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
            </div>
        </div>
    </div>
</form>
