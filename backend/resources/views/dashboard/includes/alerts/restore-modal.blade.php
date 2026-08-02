<!--begin::restore Modal-->
<div id="restore_modal" class="modal fade" style="margin-top: 13%">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 16px">@lang('dashboard.confirm_restore')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 16px">
                    {{__('dashboard.restoreAction')}}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="restore-button" class="btn btn-success">@lang('dashboard.restore')</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">@lang('dashboard.close')</button>
            </div>
        </div>
    </div>
</div>
<!--end::restore Modal-->
