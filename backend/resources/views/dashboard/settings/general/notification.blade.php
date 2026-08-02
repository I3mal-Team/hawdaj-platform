<div class="card card-custom">
 <div class="card-header">
  <div class="card-title">
            <span class="card-icon">
                <i class="flaticon2-chat-1 text-primary"></i>
            </span>
   <h3 class="card-label text-muted">
    {{ __('dashboard.sms_notifi_setting') }}
   </h3>
  </div>
 </div>
 <div class="card-body">
     <div class="row">
         <div class="col-12">
            <label class="col-form-label">{{ __('dashboard.active_sms_channel') }}</label>
            <span class="switch switch-icon">
                <label>
                <input type="checkbox" checked="checked" name="select"/>
                <span></span>
                </label>
            </span>
         </div>
         <div class="col-12">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>{{ __('dashboard.notifi_title') }}</th>
                        <th>{{ __('dashboard.reclplent') }}</th>
                        <th>{{ __('dashboard.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                    </tr>
                </tbody>
            </table>
         </div>
     </div>
 </div>
</div>