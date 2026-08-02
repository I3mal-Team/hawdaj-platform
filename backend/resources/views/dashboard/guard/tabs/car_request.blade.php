<form class="form">
    <div class="form-group">
        <div class=" row">
            {{--                    <div class="form-group col-md-6 col-sm-12">--}}
            {{--                        <label>{{ __('dashboard.type') }}:</label>--}}
            {{--                        <input type="text" class="form-control form-control-solid" value="{{ $materialData->type }}" disabled/>--}}
            {{--                    </div>--}}
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.branch') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{ $carData->site->name }}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.host_id') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{ $carData->host->first_name }}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.department_id') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{ $carData->department->name }}" disabled/>
            </div>
        <!-- <div class="form-group col-md-6 col-sm-12">
                        <label>{{ __('dashboard.company') }}:</label>
                        <input type="text" class="form-control form-control-solid" value="some data" disabled/>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label>{{ __('dashboard.contact_person') }}:</label>
                        <input type="text" class="form-control form-control-solid" value="some data" disabled/>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label>{{ __('dashboard.phone') }}:</label>
                        <input type="text" class="form-control form-control-solid" value="some data" disabled/>
                    </div> -->
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.delivery_date') }}:</label>
                <input type="date" class="form-control form-control-solid" value="{{ $carData->delivery_date }}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <div class="row">
                    <div class="form-group col-md-6 col-sm-12">
                        <label>{{ __('dashboard.delivery_to_time') }}:</label>
                        <input type="time" class="form-control form-control-solid" value="{{ $carData->delivery_from_time }}" disabled/>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <label>{{ __('dashboard.delivery_to_time') }}:</label>
                        <input type="time" class="form-control form-control-solid" value="{{ $carData->delivery_to_time }}" disabled/>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label>{{ __('dashboard.remarks') }}:</label>
                <textarea disabled name="remarks" cols="30" rows="4" class="form-control" placeholder="{{ __('dashboard.commit_for_reception') }}">{{$carData->remarks}}</textarea>
            </div>
        </div>
    </div>
</form>
