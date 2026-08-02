<div>
    <div class="card card-custom gutter-b mb-2">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">{{__('dashboard.contact_details')}}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="text-center">
                <form action="{{route('dashboard.carRequestAction')}}" id="target" method="post">
                    @csrf
                    <input type="hidden" name="car_request_id" value="{{$carRequest->id}}">
                    <div id="formAction"></div>
                    <div class="form-group">
                        <textarea class="form-control form-control-solid" rows="7" name="some_note" placeholder="{{__('dashboard.remarks')}}"></textarea>
                    </div>
                    <div class="container">
                        @if (!empty($carRequest['status_action']) && $carRequest['status_action'] == 'checkin')
                            <p style="float:left"><strong>Last Check In:</strong>
                                {{dateFormat($carRequest['checkin']) .' '. timeFormat($carRequest['checkin'])}}
                            </p>
                            <a type="button" onclick="setActionValue('checkout')" class="btn btn-success font-weight-bold">{{ __('dashboard.checkout') }}</a>
                        @elseif(empty($carRequest['status_action']))
                            <a type="button" onclick="setActionValue('rejected')" class="btn btn-light-danger font-weight-bold">{{ __('dashboard.refuse') }}</a>
                            <a type="button" onclick="setActionValue('checkin')" class="btn btn-success font-weight-bold">{{ __('dashboard.checkin') }}</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
