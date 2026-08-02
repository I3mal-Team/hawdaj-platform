@extends('layouts.dashboard.master')
@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">@lang('dashboard.menu_templates')</h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="{{url('dashboard/mail_template')}}" class="text-muted">@lang('dashboard.all_templates')</a>
        </li>

        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{$title}}</a>
        </li>


    </ul>
@endsection

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('.mail_editor'))
            .catch(error => {
                console.error(error);
            });
    </script>

@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">{{$title}}</h3>
                    </div>
                </div>
                <form id="form" novalidate="novalidate" class="kt-form kt-form--label-right" method="post"
                      action="{{ route('dashboard.mail_template.update' , $template->id) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="hidden" name="id" value="{{ $template->id }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <strong>{{ $template->title ?? '' }}</strong>
                                <label for="">{{ __('dashboard.mail_title') }}</label>
                                {{--                                <input type="text" name="title" value="{{$template->title ?? ''}}"--}}
                                {{--                                       class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"--}}
                                {{--                                       placeholder="@lang('dashboard.enter') @lang('dashboard.title') "--}}
                                {{--                                       aria-describedby="basic-addon1">--}}
                                {{--                                <div class="invalid-feedback">--}}
                                {{--                                    <strong>{{ $errors->has('title') ? $errors->first('title') : '' }}</strong>--}}
                                {{--                                </div>--}}
                            </div>
                            <div class="col-12 mb-3 ml-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label for=""><strong>{{__('dashboard.key_words')}}</strong></label>
                                    </div>
                                    <div class="col-12">
                                        @foreach(json_decode($template->key_words) as $word)
                                            %{{ $word }}%
                                        @endforeach
                                    </div>
                                    <div class="col-md-2 col-sm-4"></div>
                                </div>
                            </div>
                            {{--                            <div class="col-12 mb-3">--}}
                            {{--                                <label for="">{{ __('dashboard.mail_subject') }}</label>--}}
                            {{--                                <input type="text" name="subject" value="{{$template->subject ?? ''}}"--}}
                            {{--                                       class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}"--}}
                            {{--                                       placeholder="@lang('dashboard.enter') @lang('dashboard.subject') "--}}
                            {{--                                       aria-describedby="basic-addon1">--}}
                            {{--                                <div class="invalid-feedback">--}}
                            {{--                                    <strong>{{ $errors->has('subject') ? $errors->first('subject') : '' }}</strong>--}}
                            {{--                                </div>--}}

                            {{--                            </div>--}}
                            <div class="col-12">
                                <textarea
                                    class="mail_editor form-control {{ $errors->has('content') ? 'is-invalid' : '' }}"
                                    name="content">{{ $template->content ?? '' }}</textarea>
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->has('content') ? $errors->first('content') : '' }}</strong>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="kt-form__actions">
                            <button type="submit" class="btn btn-primary">@lang('dashboard.submit')</button>
                            <button type="reset" class="btn btn-secondary">@lang('dashboard.cancel')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
