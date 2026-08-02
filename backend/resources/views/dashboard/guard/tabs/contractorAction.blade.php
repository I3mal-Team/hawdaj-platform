<div class="modal fade" id="contractActionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('dashboard.fast_action') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                @php
                    $today_date = Carbon\Carbon::now();
                @endphp
                @if(($today_date >= $contractorData->contractRequest['from_date']) && ($today_date <= $contractorData->contractRequest['to_date']) )
                    <form class="form" id="target" action="{{route('dashboard.guard.contractVisitAction')}}" method="POST">
                    @csrf
                    <input type="hidden" name="contractor_request_id" class="form-control" value="{{ $contractorData->id }}">
                    <div id="formAction"></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ __('dashboard.some_note') }}:</label>
                            <textarea name="some_note" class="form-control form-control-solid" rows="10"></textarea>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                </form>
                @else
                    @if(!($today_date >= $contractorData->contractRequest['from_date']))
                        <p style="float:left">
                            your contract not active
                        </p>
                    @elseif(!($today_date <= $contractorData->contractRequest['to_date']))
                        <p style="float:left">
                            your request expired
                        </p>
                    @endif
                @endif
            </div>

            <div class="modal-footer justify-content-between">
                @php
                    $today_date = Carbon\Carbon::now();
                @endphp
                @if(($today_date >= $contractorData->contractRequest['from_date']) && ($today_date <= $contractorData->contractRequest['to_date']) )
                    @if (!empty($contractorData->last_checkin['status_action']) && $contractorData->last_checkin['status_action'] == 'checkin')
                        <p style="float:left"><strong>Last Check In:</strong>
                            {{dateFormat($contractorData['checkin']) .' '. timeFormat($contractorData['checkin'])}}
                        </p>
                        <a type="button" onclick="setActionValue('checkout')"
                           class="btn btn-success font-weight-bold">{{ __('dashboard.checkout') }}</a>
                    @else
                        <a type="button" onclick="setActionValue('rejected')"
                           class="btn btn-light-danger font-weight-bold">{{ __('dashboard.refuse') }}</a>
                        <a type="button" onclick="setActionValue('checkin')"
                           class="btn btn-success font-weight-bold">{{ __('dashboard.checkin') }}</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
