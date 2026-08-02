<div class="modal fade" id="actionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                <form class="form" id="target" action="{{route('dashboard.guard.action')}}" method="POST">
                    @csrf
                    <input type="hidden" name="visitor_request_id" class="form-control" value="{{ $visitorData->id }}">
                    <div id="formAction"></div>
                    <div class="form-group">
                        <label>{{ __('dashboard.some_note') }}:</label>
                        <textarea name="some_note" class="form-control form-control-solid" rows="10"></textarea>
                        <span class="form-text text-muted"></span>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-end">
                @if (!empty($visitorData['status_action']) && $visitorData['status_action'] == 'checkin')
                    <p style="float:left"><strong>{{ __('dashboard.last_checkin') }} :</strong>
                        {{dateFormat($visitorData['checkin']) .' '. timeFormat($visitorData['checkin'])}}
                    </p>
                    <a type="button" onclick="setActionValue('checkout')"
                       class="btn btn-success font-weight-bold">{{ __('dashboard.checkout') }}</a>
                @else
                    <a type="button" onclick="setActionValue('rejected')"
                       class="btn btn-light-danger font-weight-bold">{{ __('dashboard.refuse') }}</a>
                    <a type="button" onclick="setActionValue('checkin')"
                       class="btn btn-success font-weight-bold">{{ __('dashboard.checkin') }}</a>
                @endif
            </div>
        </div>
    </div>
</div>
