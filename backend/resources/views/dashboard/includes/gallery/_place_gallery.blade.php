<div class="card">
    <div class="card-header">
        <div class="card-title">
            <strong class="text-muted">{{ __('dashboard.gallery') }}</strong>
        </div>
    </div>

    <div class="card-card-footer">
        <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="POST"
              action="{{ route('dashboard.SaveGallery') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                <div class="row">
                    <input type="hidden" name="parent_id" value="{{ $data->id }}" id="">
                    <input type="hidden" name="type" value="{{ Request::segment(2) }}" id="">
                    <div class="col-md-12">
                        <div class="form-group validated">
                            <label>@lang('dashboard.image')</label>
                            <span class="text-danger"> * </span>
                            <div class="form-group">
                                <div id="dpz-multiple-files" class="dropzone dropzone-area">
                                    <div class="dz-message">يمكنك رفع اكثر من صوره هنا </div>
                                </div>
                                <br><br>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="card-body">
                <div class="row">
                    @if(count($data->galleries ?? []) > 0)
                        @foreach($data->galleries as $gallery)
                            <div class="col-md-4 col-sm-12 mb-3" id="row-{{$gallery->id}}">
                                <div class="container place_gallery_file">
                                    <img src="{{asset($gallery->file) }}" alt="imageNotFound" class="img-thumbnail">
                                    <div class="overlay"></div>
                                    <div class="button">
                                        <a class="btn btn-sm btn-clean btn-icon btn-icon-md delete-button"
                                           style="position: absolute; left: 4%;"
                                           data-toggle="modal" data-target="#delete_modal"
                                           data-url="{{ route('dashboard.deleteGallery',$gallery->id) }}"
                                           data-item-id="{{ $gallery->id }}">
                                            <i class="flaticon2-trash text-danger"></i></a></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>



            </div>


            <div class="card-footer">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                </div>
            </div>

        </form>
    </div>
</div>
@include('dashboard.includes.alerts.delete-modal')

