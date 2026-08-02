<form class="form">
    <div class="form-group">
        <div class="row">
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.plate_ar') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->car->plate_ar}}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.plate_en') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->car->plate_en}}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.licence') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->car->licence}}" disabled/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>{{ __('dashboard.type') }}:</label>
                <input type="text" class="form-control form-control-solid" value="{{$carData->car->type}}" disabled/>
            </div>
            <div class="form-group col-md-12 col-sm-12">
                <label>{{ __('dashboard.description') }}:</label>
                <textarea disabled name="remarks" cols="30" rows="4" class="form-control" placeholder="{{ __('dashboard.commit_for_reception') }}">{{$carData->car->description}}</textarea>
            </div>
        </div>
    </div>
</form>
