@extends('dashboard.users.profile.index')
@section('profile_content')
    <div class="flex-row-fluid ml-lg-8">
         <form class="form" method="post" action="{{route('dashboard.profile.changePassword.update')}}">
                {{csrf_field()}}
             <div class="card card-custom card-stretch">
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark" style="padding-top: 9px;">{{__('dashboard.change_password')}}</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="submit" class="btn btn-success mr-2">{{__('dashboard.save')}}</button>
                        <button type="reset" class="btn btn-secondary">{{__('dashboard.cancel')}}</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label
                            class="col-xl-3 col-lg-3 col-form-label text-alert">{{__('dashboard.password')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" name="password"
                                   class="form-control form-control-lg form-control-solid mb-2"
                                   placeholder="{{__('dashboard.enter')}} {{__('dashboard.password')}}"/>
                            <div class="text-danger">
                                <strong>{{ $errors->has('password') ? $errors->first('password') : '' }}</strong>
                            </div>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label
                            class="col-xl-3 col-lg-3 col-form-label text-alert">{{__('dashboard.new_password')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" class="form-control form-control-lg form-control-solid"
                                   name="new_password"
                                   placeholder="{{__('dashboard.enter')}} {{__('dashboard.new_password')}}"/>
                            <div class="text-danger">
                                <strong>{{ $errors->has('new_password') ? $errors->first('new_password') : '' }}</strong>
                            </div>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label
                            class="col-xl-3 col-lg-3 col-form-label text-alert">{{__('dashboard.new_password_confirmation')}}</label>
                        <div class="col-lg-9 col-xl-6">
                            <input type="password" name="new_password_confirmation"
                                   class="form-control form-control-lg form-control-solid"
                                   placeholder="{{__('dashboard.enter')}} {{__('dashboard.new_password_confirmation')}}"/>
                            <div class="text-danger">
                                <strong>{{ $errors->has('new_password_confirmation') ? $errors->first('new_password_confirmation') : '' }}</strong>
                            </div>

                        </div>

                    </div>
                </div>
            </form>
    </div>
@endsection
