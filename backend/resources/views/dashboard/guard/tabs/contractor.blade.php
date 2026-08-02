<form class="form">
    <div class="form-group">
        <div class=" row">
            <div class="col-md-4 col-sm-12">
                <img src="/dashboard_assets/media/avatar.png" alt="" width="100%">
            </div>
            <div class="col-md-8 col-sm-12" style="margin:auto">
                <div class="mb-2">
                    <label>{{ __('dashboard.first_name') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{ $contractorData['contractor']['first_name'] }}" placeholder="Enter full name" disabled/>
                </div>
                <div class="mb-2">
                    <label>{{ __('dashboard.last_name') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{ $contractorData['contractor']['last_name'] }}" placeholder="Enter full name" disabled/>
                </div>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.id_type') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{ $contractorData['contractor']['id_type'] }}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.id_number') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{ $contractorData['contractor']['id_number'] }}" disabled/>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label>{{ __('dashboard.vehicle_detail') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{ $contractorData['contractor']['vehicle_detail'] }}" disabled/>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label>{{ __('dashboard.vehicle_material') }}:</label>
                <input type="text" class="form-control form-control-solid"  value="{{ $contractorData['contractor']['vehicle_material'] }}" disabled/>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label>{{ __('dashboard.contract_manager') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{ $contractorData['contractRequest']['contract_manager']['first_name'] }}" disabled/>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label>{{ __('dashboard.department') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{ $contractorData['contractRequest']['contract']['department']['name'] }}" disabled/>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label>{{ __('dashboard.visit_date_&_time') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{ $contractorData['contractRequest']['from_date'] }}" disabled/>
            </div>
        </div>

    </div>
</form>
