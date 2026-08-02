<div class="card card-custom gutter-b mb-2 card-shadowless">
    <div class="card-body">
        <div class="row ">
            <div class="col-md-12">
                <input type="hidden" name="id" id="visitorReqID" value="{{$visitorReq->id}}">
                <div class="form-group">
                        <textarea id="notes" class="form-control form-control-solid" rows="8" name="notes"
                                  placeholder="{{__('dashboard.remarks')}}">{{$visitorReq->notes}}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    @if (!empty($visitorReq->status_action) && $visitorReq->status_action== 'checkin')
                        <p style="float:left; margin-right: 50px"><strong>{{ __('dashboard.last_checkin') }}
                                :</strong>
                            {{dateFormat($visitorReq->checkin) .' action.blade.php'. timeFormat($visitorReq->checkin)}}
                        </p>
                        <a type="button" onclick="changeAction('checkout')"
                           class="btn btn-success text-left font-weight-bold">{{ __('dashboard.checkout') }}</a>
                    @else
                        <a type="button" onclick="changeAction('rejected')"
                           class="mx-4 btn btn-light-danger text-left font-weight-bold">{{ __('dashboard.refuse') }}</a>
                        <a type="button" onclick="changeAction('checkin')"
                           class="btn btn-success text-left font-weight-bold">{{ __('dashboard.checkin') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

