
@extends('layouts.dashboard.master')
@push('css')
    <link href="{{asset('dashboard_assets/css/slick.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('dashboard_assets/custom/css/model.css')}}" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" type="text/css"/>
    <style>
        .imagePreview {
            display: none;
        }

        .imagePreview.active {
            display: block;
        }

        .imagesThumb {
            width: 100%;
            height: 90px;
            text-align: center;
            margin-top: 10px;
        }

        .imagesThumb img {
            width: 120px !important;
            height: 90px;
            margin: 0 5px;
            cursor: pointer;
            min-width: 100px !important;
            padding: 3px;
            border: 2px solid #DDD;
        }

        .imagesThumb img.active {
            border: 3px solid #000;
        }

        #imagePreviewModal .modal-body img {
            height: 65vh;
        }

        #myTab {
            margin-right: 65px;
        }
    </style>
    @if(app()->getLocale()=='ar')
        <style>
            .header-fixed.aside-fixed .wrapper {
                padding-right: 60px !important;
                padding-left: 0 !important;
            }
        </style>
    @endif
@endpush

@section('content')
    <div class="main" style="margin-top: -40px; padding-top: 10px;">
        <!--begin:: Notification  -->
        <div class="container-fluid custom-navs-cont">
            <a class="btn btn-icon btn-outline-secondary btn-sm notes-btn mr-25" target='_blank'
               title="{{trans('dashboard.notes')}}" data-toggle="tooltip"
               href="{{url('dashboard/car_notes?site_id='.$site_id)}}"
               style='{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'right:auto;' : ''}}'>
                <i class="flaticon2-notepad" style='font-size:20px !important;'></i>
            </a>
            <a class="btn btn-icon btn-outline-secondary btn-sm export-btn mr-25" target='_blank'
               title="{{trans('dashboard.exported_file')}}" data-toggle="tooltip"
               href="{{url('dashboard/report/CarModel/files?site_id='.$site_id)}}"
               style='{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'right:auto;' : ''}}'>
                <i class="flaticon2-sheet" style='font-size:20px !important;'></i>
            </a>
            @if(!$sites->isEmpty())
                <div class="row justify-content-center factory_cont">
                    <div class="text-dark live_mode-container col-2">
                        <div class="d-flex justify-content-center" style="margin-left: 65px">
                            <h3 class="mt-2 mr-2">@lang('dashboard.live_mode'):</h3>
                            <span class="switch switch-outline switch-icon switch-success">
                                <label>
                                    <input type="checkbox" id="live_mode"
                                           data-url="{{route('dashboard.car.live_mode')}}"
                                           @if(empty(request('car_export'))&&empty(request('redirect_id'))) checked
                                           @endif name="status">
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                    <div class="custom-nav col-md-8">
                        <ul class="region nav nav-pills scroll-horizontal-cont" id="myTab" role="tablist"
                            style="padding-left: 65px">
                            @foreach($sites as $key=>$site)
                                <li class="nav-item ">
                                    <a href="{{url('dashboard/cars/'.$site->id)}}"
                                       class="nav-link get_site @if($site_id == $site->id) active @endif"
                                       data-parent="true"
                                       data-id="{{$site->id}}">{{trans("dashboard.$site->name")}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="text-dark col-2">
                        <a target="_blank" href="{{url('dashboard/report/CarModel/filter')}}"
                           class="btn btn-sm btn-success"
                           style="top:16px; margin-top: 6px; float: right; margin-right: 5px">
                            <i class="menu-icon menu-icon flaticon2-browser-2"></i>
                            @lang('dashboard.build_report')
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <!--end:: Notification  -->

        <!-- Start Content -->
        <div class="tab-content main-content mt-1" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="open-door">
                    <div class="container-fluid p-md-0" style="max-width: 100% !important;overflow: hidden">
                        <div class="row wrapper wrapper right-100 left-100 py-5 custom-wrapper">
                            <div class="col-md-3 setting-col">
                                <span class="setting-icon-sho" data-toggle="tooltip" data-placement="top"
                                      title="{{__('dashboard.control_settings')}}">
                                  <i class="fas fa-cog"></i>
                                </span>
                                <div class="setting h-100">
                                    <div class="card side-card h-100">
                                        <div class="card-header text-center border-0 p-3">
                                            <h3>{{__('dashboard.settings')}}</h3>
                                        </div>
                                        <div class="card-body text-center">
                                            <h4 class="text-success">
                                                {{__('dashboard.control_settings')}}
                                            </h4>
                                            <form action="{{url('dashboard/car/setting')}}" method="post">
                                                {{csrf_field()}}
                                                <input type="hidden" id="site_id" name="site_id"
                                                       value="{{$site_id}}" class="form-control">

                                                <div class="form-group input-group input-daterange">
                                                    <div class="row col-12 p-0 m-0">
                                                        <p class="col">{{__('dashboard.start_date')}}</p>
                                                        <p class="col">{{__('dashboard.end_date')}}</p>
                                                    </div>
                                                    <input type="date"
                                                           @if($car_setting)value="{{$car_setting->start_date}}"
                                                           @endif name="start_date" class="form-control start_date">
                                                    <div class="input-group-addon">
                                                        <small>{{__('dashboard.to')}}</small>
                                                        <i class="fas fa-long-arrow-alt-right"></i>
                                                    </div>
                                                    <input type="date" name="end_date"
                                                           @if($car_setting)value="{{$car_setting->end_date}}"
                                                           @endif class="form-control end_date">
                                                </div>
                                                <div class="form-group input-group input-daterange">
                                                    <div class="row col-12 p-0 m-0">
                                                        <p class="col">{{__('dashboard.start_time')}}</p>
                                                        <p class="col">{{__('dashboard.end_time')}}</p>
                                                    </div>

                                                    <input type="time" required
                                                           @if($car_setting)value="{{$car_setting->start_time}}"
                                                           @endif name="start_time" class="form-control">

                                                    <div class="input-group-addon">
                                                        <small>{{__('dashboard.to')}}</small>
                                                        <i class="fas fa-long-arrow-alt-right"></i>
                                                    </div>
                                                    <input type="time" required
                                                           @if($car_setting)value="{{$car_setting->end_time}}"
                                                           @endif name="end_time" class="form-control">
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-6">
                                                        <div class="row justify-content-center">
                                                            <label
                                                                class="col-6 col-form-label px-0">@lang('dashboard.notification')</label>
                                                            <div class="col-6">
                                                              <span
                                                                  class="switch justify-content-center switch-outline switch-icon switch-primary">
                                                                  <label>
                                                                      <input type="checkbox" value="1"
                                                                             name="notification"
                                                                             @if($car_setting->notification??false) checked="checked" @endif/>
                                                                      <span></span>
                                                                </label>
                                                              </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 ">
                                                        <div class="row justify-content-center">
                                                            <label
                                                                class="col-6 col-form-label px-0">{{__('dashboard.screenshot')}}</label>
                                                            <div class="col-6">
                                                                <span
                                                                    class="switch justify-content-center switch-outline switch-icon switch-primary">
                                                                <label>
                                                                    <input type="checkbox" value="1" name="screenshot"
                                                                           @if($car_setting->screenshot??false) checked="checked"
                                                                        @endif />
                                                                  <span></span>
                                                                </label>
                                                              </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-center">
                                                    <button type="button"
                                                            class="btn btn-primary close-setting add_loading">
                                                        {{__('dashboard.save')}}
                                                    </button>
                                                </div>
                                            </form>
                                            <hr class="separator">
                                            <form action="{{url('dashboard/cars/'.$site_id)}}">
                                                <h4 class="text-success">{{__('dashboard.export_settings')}}</h4>
                                                <div class="form-group input-group input-daterange" id="notifytime">
                                                    <div class="row col-12 p-0 m-0">
                                                        <p class="col">{{__('dashboard.select_date')}}</p>
                                                    </div>
                                                    <select id="car_export" name="car_export"
                                                            class="form-control nice-select">
                                                        <option @if(request('car_export') == null) selected
                                                                @endif value="">
                                                            @lang('dashboard.select_date')
                                                        </option>
                                                        <option
                                                            @if(request('car_export') == 'today')
                                                            selected @endif value="today"
                                                        >
                                                            @lang('dashboard.today')
                                                        </option>

                                                        <option value="14"
                                                                @if(request('car_export') ==14) selected @endif
                                                        >
                                                            {{trans('dashboard.this_week')}}
                                                        </option>
                                                        <option value="13"
                                                                @if(request('car_export') ==13) selected @endif>
                                                            {{trans('dashboard.last_week')}}
                                                        </option>
                                                        <option value="16"
                                                                @if(request('car_export') ==16) selected @endif>
                                                            {{trans('dashboard.this_month')}}
                                                        </option>
                                                        <option value="15"
                                                                @if(request('car_export') ==15) selected @endif>
                                                            {{trans('dashboard.last_month')}}
                                                        </option>
                                                        @for($i=0;$i<date('m') ;$i++)
                                                            <option value="{{date('m', strtotime("-$i month"))}}"
                                                                    @if(request('car_export') ==date('m', strtotime("-$i month")))  selected @endif
                                                            >
                                                                <?php echo date('Y-m', strtotime("-$i month")); ?>
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="form-group row">

                                                    <div class="col-12 col-form-label">
                                                        <div class="radio-inline justify-content-center">
                                                            <label class="radio radio-primary">
                                                                <input type="radio" value="pdf" name="radios5"/>
                                                                <span></span>
                                                                @lang('dashboard.pdf')
                                                            </label>
                                                            <label class="radio radio-primary">
                                                                <input type="radio" value="xls" name="radios5"
                                                                       checked="checked"/>
                                                                <span></span>
                                                                @lang('dashboard.excel')
                                                            </label>

                                                        </div>

                                                    </div>
                                                </div>
                                                <button class="btn btn-primary add_loading"
                                                        id="search_key">{{__('dashboard.search')}}</button>
                                                <input class="btn btn-primary" type="button" onclick="ExportFile()"
                                                       value="{{__('dashboard.export')}}"/>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 data-cont">
                                <div class="item-section slider">
                                    <div>
                                        <div class="door-open">
                                            <div
                                                class="card item-card car-card {{ $car_status ? 'risk' : ''}}">
                                                <div class="card-body p-0">
                                                    <div class="text-center border-bottom px-2 pt-3 pb-1">
                                                        <div class="item_img-cont">
                                                            <img style="filter: invert(1);"
                                                                 src="{{asset('dashboard_assets/custom/images/CarModel.svg')}}"
                                                                 height="62" alt="item-1">
                                                        </div>
                                                        <div class="item-title py-3">@lang('dashboard.lpr')</div>
                                                        <div class="status-cont pb-2">
                                                            <span class="item-status">
                                                                <span class="label pulse pulse-danger mr-2">
                                                                    <span class="position-relative">
                                                                        <i class="fas fa-circle text-red"></i>
                                                                    </span>
                                                                    <span class="pulse-ring"></span>
                                                                </span>
                                                                <span class="label success">
                                                                    <i class="fas fa-circle"></i>
                                                                </span>
                                                                <span class="status-cont-text">
                                                                       @if($car_status)
                                                                        <span
                                                                            class="text-danger text">
                                                                              {{__('dashboard.cars_waiting')}}
                                                                          </span>
                                                                    @else
                                                                        <span class="text" style="color: green">
                                                                              {{__('dashboard.no_cars_waiting')}}
                                                                          </span>
                                                                    @endif
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-center  item-desc">
                                                        <div
                                                            class="border-right text-center p-2 w-50 risk-cont">
                                                            <p class="font-weight-bold mb-1 ">
                                                                {{__('dashboard.no_invitation')}}</p>
                                                            <p class="font-weight-bold mb-1 "
                                                               id="no_invitation">{{$car_days->no_invitation??0}}</p>
                                                            <p class="mb-1">{{__('dashboard.car')}}</p>
                                                        </div>
                                                        <div class="p-2 w-50 text-center  norisk-cont">
                                                            <p class="font-weight-bold mb-1 ">{{__('dashboard.invitation')}}</p>
                                                            <p class="font-weight-bold mb-1"
                                                               id="invitation">{{$car_days->invitation??0}}</p>
                                                            <p class="mb-1">{{__('dashboard.car')}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="main-card card mt-4">
                                    @if(true)
                                        <div class="card-body">
                                            <div class="filter-cont">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <select name="cameras" class="form-control nice-select"
                                                                id="camera-select">
                                                            <option
                                                                value="all">@lang('dashboard.all_cameras')</option>
                                                            @if($camera)
                                                                @foreach(array_filter($camera) as $camera_key)
                                                                    <option
                                                                        value="{{$camera_key}}"> @lang('dashboard.camera') {{$camera_key}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-2">
                                                        <select name="cameras" class="form-control nice-select"
                                                                id="status-select">
                                                            <option
                                                                value="all">@lang('dashboard.all_notice_status')</option>
                                                            <option
                                                                value="noticed">@lang('dashboard.noticed')</option>
                                                            <option
                                                                value="not_noticed"
                                                                selected>@lang('dashboard.not_noticed')</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-2">
                                                        <select name="invitation_status"
                                                                class="form-control nice-select"
                                                                id="invitation_status">
                                                            <option
                                                                value="all">@lang('dashboard.all_invitation_status')</option>
                                                            <option
                                                                value="invitation">@lang('dashboard.has_invitation')</option>
                                                            <option
                                                                value="no_invitation">@lang('dashboard.no_invitation')</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-2">
                                                        <select name="detection_status"
                                                                class="form-control nice-select"
                                                                id="detection_status">
                                                            <option
                                                                value="all">@lang('dashboard.all_detet_status')</option>
                                                            <option
                                                                value="pending">@lang('dashboard.pending')</option>
                                                            <option value="error">@lang('dashboard.error')</option>
                                                            <option
                                                                value="success">@lang('dashboard.success')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-cont">
                                                <table class="table " id="car_table_data">
                                                    <thead>
                                                    <tr>
                                                        <th class="th-sm">@lang('dashboard.date')</th>
                                                        <th class="th-sm">@lang('dashboard.timing')</th>
                                                        <th class="th-sm">@lang('dashboard.plate_en')</th>
                                                        <th class="th-sm">@lang('dashboard.plate_ar')</th>
                                                        <th class="th-sm">@lang('dashboard.notice_time')</th>
                                                        <th class="th-sm">@lang('dashboard.status')</th>
                                                        <th class="th-sm">@lang('dashboard.detection_status')</th>
                                                        <th class="th-sm">@lang('dashboard.id_camera')</th>
                                                        <th class="th-sm">@lang('dashboard.action')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="car_table">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-body">
                                            <div class="no_record">
                                                <img src="{{asset('dashboard_assets/media/no-data.png')}}"
                                                     alt="{{__('dashboard.no_image_available')}}">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3 show-image d-none">
                                <span class="new-img"></span>
                                <span class="show-image-icon-sho" data-toggle="tooltip" data-placement="top"
                                      title="{{__('dashboard.screenshots')}}">
                                    <i class="fas fa-camera"></i>
                                  </span>
                                @if($getScreenShots)
                                    <div class="screenshots h-100">
                                        <div class="card side-card h-100">
                                            <div class="card-header text-center border-0 p-3">
                                                <h3>
                                                    @lang('dashboard.screenshot')
                                                </h3>
                                                <div class="mt-5">
                                                    <ul class=" cameras-nav nav nav-tabs scroll-horizontal-cont md-tabs mt-1"
                                                        style="overflow-y: hidden" id="myTabJust" role="tablist">
                                                        @foreach($getScreenShots as $key=>$getScreen)
                                                            <li class="nav-item">
                                                                <a class="nav-link  @if($loop->first) active @endif"
                                                                   href="#home-{{$key}}"
                                                                   id="home-tab-{{$key}}" data-toggle="tab"
                                                                   role="tab" aria-controls="home-{{$key}}"
                                                                   aria-selected="true">@lang('dashboard.camera') {{$key}}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content  pt-3" id="myCameraTabContent">
                                                    @foreach($getScreenShots as $key2=>$getScreen)
                                                        <div
                                                            class="tab-pane fade @if($loop->first) show active @endif"
                                                            id="home-{{$key2}}" role="tabpanel"
                                                            aria-labelledby="home-tab-{{$key2}}">
                                                            <div class="screenshoot-content">
                                                                @if($getScreen)
                                                                    <div class="img-cont ">
                                                                        @foreach($getScreen as $key=>$screen)
                                                                            <div class="img" id="img-{{$screen->id}}">
                                                                                <img
                                                                                    src="{{resolvePhoto($screen->image,'none')}}"
                                                                                >
                                                                                <div class="date">
                                                                                    <p>{{dateFormat($screen->created_at).' '.timeFormat($screen->created_at)}}</p>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>

                                                                @else
                                                                    <div class="no_img-cont">
                                                                        <img
                                                                            src="{{ asset('dashboard_assets/media/no_image.jpg','none') }}"
                                                                        >
                                                                    </div>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image preview Modal-->
                <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog"
                     aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header row col-12 mx-0 px-0">
                                <div class="col">
                                    <h5 class="modal-title"
                                        id="imagePreviewModalLabel">@lang('dashboard.screenshot_image_preview')</h5>
                                </div>
                                <div class="col text-right">
                                    <button class="btn btn-icon" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-8 col-img-cont">
                                        <div class="img-cont">
                                            <div class="dragable" id="imgPreviwParent">
                                                <img id="img" class="imagePreview car_image active" alt=""/>
                                                <img id="img1" class="imagePreview plate_image" alt=""/>
                                            </div>
                                            <div class="imagesThumb">
                                                <img id="img" class="car_image active" alt=""/>
                                                <img id="img1" class="plate_image" alt=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 data-cont">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Image preview Modal-->

                <!-- ConfirmationModal-->
                <div class="modal fade mt-10" id="confirmationModal" tabindex="-1" role="dialog"
                     aria-labelledby="confirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmationModalLabel">
                                    @lang('dashboard.confirmation_record')
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body pt-3">
                                <form action="" class="notes-form">
                                    <div class="form-group mb-4">
                                        <label for="notesTextArea">@lang('dashboard.note')</label>
                                        <textarea class="form-control form-control-solid" id="notesTextArea"
                                                  rows="3"></textarea>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label for="file">@lang('dashboard.upload_file')</label>
                                        <input id="file" name="file" type="file" accept=".doc,.docx,.pdf, image/*"/>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success font-weight-bold confirm-btn"
                                        data-type="error" data-dismiss="modal">@lang('dashboard.skip')
                                </button>
                                <button type="button" class="btn btn-primary font-weight-bold confirm-btn"
                                        data-type="success" data-dismiss="modal">@lang('dashboard.confirm')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ConfirmationModal-->

                <!-- Notification Modal-->
                <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog"
                     aria-labelledby="notificationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="notificationModalLabel">@lang('dashboard.notification')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <i aria-hidden="true" class="ki ki-close"></i>
                                </button>
                            </div>
                            <div class="modal-body notification-modal-body">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                        data-dismiss="modal">@lang('dashboard.close')
                                </button>
                                <button type="button" class="btn btn-primary font-weight-bold confirm-btn"
                                        data-dismiss="modal">@lang('dashboard.change_status')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Notification Modal-->
            </div>
        </div>

        <div class="modal" id="carInvitation" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
             aria-hidden="true">

        </div>
    </div>
    <!-- End Content -->
@endsection

@push('js')
    <script>
        var siteID = "{{$site_id}}";
        var exportType = "{{request('car_export')}}";
        var redirect_id = "{{request('redirect_id')}}";
    </script>
    <script src="{{asset('dashboard_assets/js/slick.min.js')}}"></script>
    <script src="{{asset('dashboard_assets/custom/js/cars.js')}}"></script>
    <script src="{{asset('dashboard_assets/js/dmukaZoom/main.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#live_mode').on('change', function (e) {
                let url = '';
                let inputs = '';
                if ($(this).prop('checked') == true) {
                    url = `${HOST_URL}/${LANG}/dashboard/cars/${siteID}`;
                } else {
                    url = `${HOST_URL}/${LANG}/dashboard/cars/${siteID}`;
                    inputs = `<input name='car_export' value='today'>`;
                }

                $(`<form action=${url} method="get">${inputs}</form>`).appendTo('body').submit().remove();
            });
        });
        var zoom = new dmuka.Zoom({
            element: document.getElementById("imgPreviwParent")
        });

        function setActionValue(value) {
            $("#formAction").append("<input type='hidden' name='status_action' class='form-control' value='" + value + "'/>")
            $("#target").submit();
        }

    </script>

@endpush
