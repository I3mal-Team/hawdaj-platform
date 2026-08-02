<form class="form">
    <div class="">
        <div class="form-group">
            <div class="row">
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.type') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{ $materialData->type }}" disabled/>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.site_id') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{ $materialData->site->name }}" disabled/>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.host_id') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{ $materialData->host->first_name }}" disabled/>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>{{ __('dashboard.department_id') }}:</label>
                    <input type="text" class="form-control form-control-solid" value="{{ $materialData->department }}" disabled/>
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
                @if ($materialData->type == 'inward_non-returnable')
                    <div class="form-group col-md-6 col-sm-12">
                        <label>{{ __('dashboard.delivery_date') }}:</label>
                        <input type="date" class="form-control form-control-solid" value="{{ $materialData->delivery_date }}" disabled/>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label>{{ __('dashboard.delivery_to_time') }}:</label>
                                <input type="time" class="form-control form-control-solid" value="{{ $materialData->delivery_from_time }}" disabled/>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label>{{ __('dashboard.delivery_to_time') }}:</label>
                                <input type="time" class="form-control form-control-solid" value="{{ $materialData->delivery_to_time }}" disabled/>
                            </div>
                        </div>
                    </div>
                @elseif($materialData->type == 'inward_returnable')
                    <div class="form-group col-md-6 col-sm-12">
                        <label>{{ __('dashboard.retrned') }}:</label>
                        <input type="date" class="form-control form-control-solid" value="{{$materialData->return_date}}" disabled/>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label>{{ __('dashboard.delivery_to_time') }}:</label>
                                <input type="time" class="form-control form-control-solid" value="{{$materialData->return_from_time}}" disabled/>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label>{{ __('dashboard.delivery_to_time') }}:</label>
                                <input type="time" class="form-control form-control-solid" value="{{$materialData->return_to_time}}" disabled/>
                            </div>
                        </div>
                    </div>
                @elseif($materialData->type == 'outward_non-returnable')
                    <div class="form-group col-md-6 col-sm-12">
                        <label>{{ __('dashboard.dispatch_date') }}:</label>
                        <input type="date" class="form-control form-control-solid" value="{{$materialData->dispatch_date}}" disabled/>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label>{{ __('dashboard.delivery_to_time') }}:</label>
                                <input type="time" class="form-control form-control-solid" value="{{$materialData->dispatch_from_time}}" disabled/>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label>{{ __('dashboard.delivery_to_time') }}:</label>
                                <input type="time" class="form-control form-control-solid" value="{{$materialData->dispatch_to_time}}" disabled/>
                            </div>
                        </div>
                    </div>
                @elseif($materialData->type == 'outward_returnable')
                    <div class="form-group col-md-6 col-sm-12">
                        <label>{{ __('dashboard.retrned') }}:</label>
                        <input type="date" class="form-control form-control-solid" value="{{$materialData->return_date}}" disabled/>
                    </div>
                    <div class="form-group col-md-6 col-sm-12">
                        <div class="row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label>{{ __('dashboard.delivery_to_time') }}:</label>
                                <input type="time" class="form-control form-control-solid" value="{{$materialData->return_from_time}}" disabled/>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label>{{ __('dashboard.delivery_to_time') }}:</label>
                                <input type="time" class="form-control form-control-solid" value="{{$materialData->return_to_time}}" disabled/>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-group col-md-12 col-sm-12">
                    <label>{{ __('dashboard.remarks') }}:</label>
                    <textarea disabled name="remarks" cols="30" rows="4" class="form-control" placeholder="{{ __('dashboard.commit_for_reception') }}">{{$materialData->remarks}}</textarea>
                </div>
            </div>
        </div>
    </div>
</form>
