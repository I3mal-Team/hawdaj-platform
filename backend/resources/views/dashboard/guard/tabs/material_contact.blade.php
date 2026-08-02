<form class="form">
    <div class="">
        <div class="form-group">
            <div class="row ">
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.company') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{$materialData->transporter->company}}" disabled/>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.contact_person') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{$materialData->transporter->contact_person}}" disabled/>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.phone') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{$materialData->transporter->phone}}" disabled/>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.email') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{$materialData->transporter->email}}" disabled/>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.people_count') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{$materialData->transporter->people_count}}" disabled/>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.vehicle_details') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{$materialData->transporter->vehicle_details}}" disabled/>
                </div>
            </div>
        </div>
    </div>
</form>
