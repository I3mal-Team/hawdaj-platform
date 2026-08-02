<div id="confirm_draft" class="modal fade" style="margin-top: 13%">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="font-size: 16px">{{trans('dashboard.draft_this_report')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="input-url" />
                <input type="hidden" class="input-id" />
                <p style="font-size: 16px">
                    {{trans('dashboard.draft_confirm')}}
                </p>
                <div class="form-group">
                    <label for="chart-title" class="col-form-label">@lang('dashboard.title')</label>
                    <input type="text" class="form-control" id="report-title">
                    <div class="invalid-feedback">@lang('dashboard.report_title_required')</div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="confirm-button" class="btn btn-primary">{{trans('dashboard.confirm')}}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('dashboard.close')}}</button>
            </div>
        </div>
    </div>
</div>
