<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <strong class="text-muted">{{ __('dashboard.gallery') }}</strong>
                </div>
            </div>
                <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
                      action="{{ route('dashboard.places.updatemap', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="card-body">

                    <div id="latlong_related" >
                        <div class="col-md-6">
                            <div class="form-group validated">
                                <label>@lang('dashboard.latitude')</label>
                                <span class="text-danger"> * </span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                        </span>
                                    </div>
                                    <input type="number" name="lat" id="lat" value="{{old("lat") ?? ''}}"
                                           class="form-control {{ $errors->has('lat') ? 'is-invalid' : '' }}"
                                           placeholder="@lang('dashboard.enter') @lang('dashboard.lat') "
                                           aria-describedby="basic-addon1">
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->has('lat') ? $errors->first('lat') : '' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group validated">
                                <label>@lang('dashboard.longitude')</label>
                                <span class="text-danger"> * </span>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="la la-exclamation-triangle flaticon-exclamation-1"></i>
                                                        </span>
                                    </div>
                                    <input type="number" name="long" id="lng" value="{{old("long") ?? ''}}"
                                           class="form-control {{ $errors->has('long') ? 'is-invalid' : '' }}"
                                           placeholder="@lang('dashboard.enter') @lang('dashboard.long') "
                                           aria-describedby="basic-addon1">
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->has('long') ? $errors->first('long') : '' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group ">
                        <div class="input-group ">

                            <input type="text" id="pac-input" class="form-control " name="address_search" placeholder="Search Box"
                                   aria-describedby="basic-addon1">

                            <div class="invalid-feedback">
                                <strong>{{ $errors->has('lat') ? $errors->first('lat') : '' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div id="googleMap" style="width:100%;height:400px;"> </div>
                    <div id="current">Nothing yet...</div>

                    </div>
                    <div class="card-footer">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>
