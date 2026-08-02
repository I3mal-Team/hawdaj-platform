<form class="form">
    <div class="form-group">
        <div class=" row">
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.id_number') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->driver->id_number}}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.contact_person_name') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->driver->contact_person_name}}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.phone') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->driver->phone}}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.email') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->driver->email}}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.licence') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->driver->licence}}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.vehicle_details') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->driver->vehicle_details}}" disabled/>
            </div>
        </div>
    </div>
</form>
