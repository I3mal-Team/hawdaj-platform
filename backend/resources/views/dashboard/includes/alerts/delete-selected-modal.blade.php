<!--begin::Delete Modal-->
<div id="delete_selected_modal" class="modal fade" style="margin-top: 13%">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 16px">@lang('dashboard.confirm_delete')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 16px">
                    {{__('dashboard.deleteAction')}} هذه العناصر
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="delete_selected" class="btn btn-danger">@lang('dashboard.delete')</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">@lang('dashboard.close')</button>
            </div>
        </div>
    </div>
</div>
<!--end::Delete Modal-->
