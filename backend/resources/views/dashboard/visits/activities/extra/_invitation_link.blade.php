<div class="modal fade" id="invitationLink" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('dashboard.Send_Message_or_Invitation_Link') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <button class="btn btn-lg btn-light-success m-3 resend_link " data-type="invitation">
                        {{ __('dashboard.Re_send_Invitation_Code') }}
{{--                        Re-send Invitation Code--}}
                    </button>
                    <button class="btn btn-lg btn-light-success m-3 resend_link " data-type="permission">
                        {{ __('dashboard.Re_send_Permission_Code') }}
{{--                        Re-send Permission Code--}}
                    </button>
                </div>
                <hr>
                <form action="{{url('dashboard/visits-activities/send-invitation')}}" method="post">
                    @csrf
                    <label for="message_area" class="mt-2 font-weight-bold mb-3">{{ __('dashboard.Message_To_Visitor') }} </label>
                    <textarea id="message_area" name="message" class="form-control" cols="50" rows="8"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">{{ __('dashboard.close') }}</button>
                <button type="button" class="btn btn-light-success font-weight-bold" id="send_message">{{ __('dashboard.send') }}</button>
            </div>
        </div>
    </div>
</div>
