<div class="card card-custom">
 <div class="card-header">
  <div class="card-title">
            <span class="card-icon">
                <i class="flaticon2-chat-1 text-primary"></i>
            </span>
   <h3 class="card-label">
    {{ __('dashboard.general_setting') }}
   </h3>
  </div>
        <div class="card-toolbar">
            <a href="#" class="btn btn-primary font-weight-bold">
                <i class="flaticon2-send-1"></i> {{ __('dashboard.save') }}
            </a>
        </div>
 </div>
 <div class="card-body">
 <form class="form">
  <div class="card-body">
      <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
                <label>{{ __('dashboard.company_name') }}</label>
                <input type="text" class="form-control"/>
            </div>
            <div class="form-group">
                <label>{{ __('dashboard.company_phone') }}</label>
                <input type="text" class="form-control"/>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <label>{{ __('dashboard.company_photo') }}</label>
            <input type="file" class="form-control"/>
          </div>
          <hr>
          <div class="col-lg-6 col-md-6 col-sm-12">
            <label>{{ __('dashboard.address') }}</label>
            <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 text-center">
            <i class="flaticon-map-location icon-10x"></i>
          </div>
          <div class="col-12">
              <hr>
          </div>
          <h3 class="text-muted">{{ __('dashboard.general_setting') }}</h3>
          <div class="col-12">
            <label class="col-form-label">{{ __('dashboard.checkvisitorAllowed') }}</label>
            <span class="switch switch-icon">
                <label>
                <input type="checkbox" checked="checked" name="select"/>
                <span></span>
                </label>
            </span>
          </div>
          <div class="col-12">
          <label class="col-form-label">{{ __('dashboard.healthCheckRequire') }}</label>
            <span class="switch switch-icon">
                <label>
                <input type="checkbox" checked="checked" name="select"/>
                <span></span>
                </label>
            </span>
          </div>
          <div class="col-12">
              <hr>
          </div>
          <div class="col-12">
              <h3 class="text-muted">{{ __('dashboard.forms_setting') }}</h3>
          </div>
          <div class="col-12">
            <label class="col-form-label">{{ __('dashboard.email_require') }}</label>
            <span class="switch switch-icon">
                <label>
                <input type="checkbox" checked="checked" name="select"/>
                <span></span>
                </label>
            </span>
          </div>
          <div class="col-12">
            <label class="col-form-label">{{ __('dashboard.id_passport_require') }}</label>
            <span class="switch switch-icon">
                <label>
                <input type="checkbox" checked="checked" name="select"/>
                <span></span>
                </label>
            </span>
          </div>
          <div class="col-12">
            <label class="col-form-label">{{ __('dashboard.personal_photo_require') }}</label>
            <span class="switch switch-icon">
                <label>
                <input type="checkbox" name="select"/>
                <span></span>
                </label>
            </span>
          </div>
      </div>
  </div>
 </form>
 </div>
</div>
