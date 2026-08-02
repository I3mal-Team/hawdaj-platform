<?php

use App\Models\Permission;
use App\Models\Rate;
use App\Models\Role;
use App\Models\Trip;
use Doctrine\DBAL\Events;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Stichoza\GoogleTranslate\GoogleTranslate;

Route::get('test-push' , function(){
    return 'test push xxx';
});

Route::get('resize-images' , function(){
    Artisan::call('resize:images');
    return 'Images Resized Successfully';
});

Auth::routes(['verify' => false]);

Route::get('get-all-data', 'GetAllModulesDataController@index');


Route::redirect('/', 'ar');
Route::redirect('en', 'ar');
Route::redirect('zh', 'ar');
Route::redirect('ru', 'ar');

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localize' , 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'maintanis']], function () {
    /*========================= Dashboard Routes ==============================*/
    Route::group(['as' => 'dashboard.', 'namespace' => 'Dashboard'], function () {

        //Global Routes
        Route::get('/', 'HomeController@index')->name('index');
        Route::get('/check-site', 'HomeController@checkSite')->name('check_site');
        Route::post('/check-site', 'HomeController@saveSite')->name('saveSite');

        Route::post('save-gallery', 'GalleryController@save')->name('SaveGallery');
        Route::post('save-img', 'GalleryController@saveImg')->name('SaveImg');
        Route::post('save-ceo', 'CeoController@save')->name('SaveCeo');
        Route::delete('delete-gallery/{id}', 'GalleryController@destroy')->name('deleteGallery');

        /************************************ places Request **********************************/

        Route::resource('users_places', 'UsersPlacesController')->except('show');
        Route::get('users_places/{users_place}', 'UsersPlacesController@show')->name('users_places.show');
        Route::post('users_places/{id}/approve', 'UsersPlacesController@approve')->name('users_places.approve');
        Route::delete('users_places_force_destroy/{id}', 'UsersPlacesController@force_destroy')->name('users_places.force_destroy');
        Route::post('users_places_activation', 'UsersPlacesController@activate')->name('users_places.active');

        Route::resource('places', 'PlacesController');
        Route::post('related_places/{id}', 'PlacesController@related')->name('places.related');
        Route::post('near_stores/{id}', 'PlacesController@near')->name('places.near');
        Route::delete('destroy_selected', 'PlacesController@destroy_selected')->name('places.destroy_selected');
        Route::delete('force_destroy/{id}', 'PlacesController@force_destroy')->name('places.force_destroy');
        Route::get('restore/{id}', 'PlacesController@restore')->name('places.restore');
        Route::post('activation', 'PlacesController@activate')->name('places.active');
        Route::post('update_status/{id}', 'PlacesController@updateStatus')->name('places.update_status');
        Route::get('reviews/{id}', 'PlacesController@reviews')->name('places.reviews');
        Route::delete('reviews/{id}', 'PlacesController@destroy_reviews')->name('places.reviews.destroy_reviews');
        Route::get('places/export', 'PlacesController@export')->name('places.export');
        /************************************ end places     **********************************/

        /************************************ store Request **********************************/

        Route::resource('stores', 'StoreController');
        Route::post('related_stores/{id}', 'StoreController@related')->name('stores.related');
        Route::post('activate_store', 'StoreController@activate')->name('stores.active');
        Route::post('update_store_status/{id}', 'StoreController@updateStatus')->name('stores.update_status');
        Route::delete('destroy_stores', 'StoreController@destroy_selected')->name('stores.destroy_selected');
        Route::post('near_places/{id}', 'StoreController@near')->name('stores.near');
        Route::get('restore_stores/{id}', 'StoreController@restore')->name('stores.restore');
        Route::get('stores/export', 'StoreController@export')->name('stores.export');

        /************************************ end stores     **********************************/


        /************************************ zad_elgadel Request **********************************/

        Route::resource('zad_elgadels', 'ZadElgadelController');
        Route::post('related_zad_elgadels/{id}', 'ZadElgadelController@related')->name('zad_elgadels.related');
        Route::post('activate_zad_elgadel', 'ZadElgadelController@activate')->name('zad_elgadels.active');
        Route::post('update_zad_elgadel_status/{id}', 'ZadElgadelController@updateStatus')->name('zad_elgadels.update_status');
        Route::delete('destroy_zad_elgadels', 'ZadElgadelController@destroy_selected')->name('zad_elgadels.destroy_selected');
        Route::post('near_placess/{id}', 'ZadElgadelController@near')->name('zad_elgadels.near');
        Route::get('restore_zad_elgadels/{id}', 'ZadElgadelController@restore')->name('zad_elgadels.restore');
        Route::get('zad_elgadels/export', 'ZadElgadelController@export')->name('zad_elgadels.export');

        /************************************ end zad_elgadels     **********************************/

        Route::resource('/menu' , 'MenuController');
        Route::get('zads/{zad}/menus' , 'MenuController@index')->name('zads.menus');
        Route::post('related_menu/{id}', 'MenuController@related')->name('menu.related');
        Route::post('activate_menu', 'MenuController@activate')->name('menu.active');
        Route::delete('destroy_menu', 'MenuController@destroy_selected')->name('menu.destroy_selected');
        Route::get('restore_menu/{id}', 'MenuController@restore')->name('menu.restore');

        Route::resource('/offer' , 'OfferController');
        Route::get('restore_menu/{id}', 'MenuController@restore')->name('menu.restore');


        /**************************************** Applications Routes *************************************/
        Route::resource('/applications' , 'ApplicationController');

        /**************************************** Sliders (home slider) *************************************/
        Route::resource('/sliders', 'SliderController');

        /**************************************** guides Routes *************************************/
        Route::resource('/guides' , 'GuidesController');
        Route::post('guides-showInHome', 'GuidesController@showInHome')->name('guides.showInHome');
        Route::post('guides-active', 'GuidesController@active')->name('guides.active');

        /**************************************** Users Routes *************************************/
        Route::group(['namespace' => 'Users'], function () {
            //Profile Routes
            Route::group(['prefix' => 'profile'], function () {
                Route::get('', 'ProfileController@index')->name('profile.index');
                Route::post('update', 'ProfileController@update')->name('profile.update');
                Route::group(['prefix' => 'changePassword'], function () {
                    Route::get('', 'ProfileController@changePassword')->name('profile.changePassword');
                    Route::post('update', 'ProfileController@updatePassword')->name('profile.changePassword.update');
                });
            });

            //Users && Permissions && Roles Routes
            Route::resource('users', 'UsersController')->except('show');
            Route::resource('roles', 'RolesController')->except('show');
            Route::resource('permissions', 'PermissionsController')->except('show');
            Route::get('permissions/{model}/edit', 'PermissionsController@edit')->name('permissions.edit');
            Route::put('permissions/{model}/update', 'PermissionsController@update')->name('permissions.update');
            Route::delete('permissions/{model}/destroy', 'PermissionsController@destroy')->name('permissions.destroy');
        });

        /************************************* Users Routes **************************************/

        /************************************* vendors Routes **************************************/
        Route::resource('vendors', 'VendorController')->except('show');
        /************************************* vendors Routes **************************************/

        /************************************* opinions Routes **************************************/
        Route::resource('opinions', 'OpinionController');
        /************************************* opinions Routes **************************************/

        /************************************* swalefs Routes **************************************/
        Route::resource('swalefs', 'SwalefController')->except('show');
        Route::post('swalefs-showInHome', 'SwalefController@showInHome')->name('swalefs.showInHome');

        /************************************* swalefs Routes **************************************/

        Route::resource('suggests', 'SuggestController');
        Route::get('activate/{id}', 'SuggestController@activate')->name('suggests.activate');


        /************************************* Caravans Routes **************************************/
        Route::resource('caravans', 'CaravanController')->except('show');
        /************************************* Caravans Routes **************************************/

        /************************************* Caravans Routes **************************************/
        Route::resource('events', 'EventController');
        Route::delete('destroy_events', 'EventController@destroy_selected')->name('events.destroy_selected');
        Route::post('related_events/{id}', 'EventController@related')->name('events.related');
        Route::post('activate_event', 'EventController@activate')->name('events.active');
        Route::post('update_event_status/{id}', 'EventController@updateStatus')->name('events.update_status');
        Route::get('restore_events/{id}', 'EventController@restore')->name('events.restore');
        /************************************* Caravans Routes **************************************/

        /************************************* Settings Routes **************************************/
        Route::group(['prefix' => 'setting', 'namespace' => 'Settings'], function () {
            Route::resource('categories', 'CategoryController')->except('show');
            Route::resource('features', 'FeatureController')->except('show');
            //            Route::get('translations', 'TranslationController@index')->name('settings.translations');
            Route::resource('store-categories', 'CategoryOfStoreController')->except('show');
            Route::resource('application-categories', 'CategoryOfApplicationController')->except('show');
            Route::resource('swalef-categories', 'CategoryOfSwalefController')->except('show');
            Route::resource('zad_elgadel-categories', 'CategoryOfZadElgadelController')->except('show');
            Route::resource('zad_elgadel-food-categories', 'FoodCategoryOfZadElgadelController')->except('show');
            Route::resource('regions', 'RegionController')->except('show');
            Route::resource('cities', 'CityController')->except('show');
            Route::resource('prices', 'PricesController')->except('show');
            Route::resource('sites', 'SiteController')->except('show');
            Route::resource('questions', 'HealthCheckController')->except('show');
            Route::resource('visit-types', 'VisitTypeController')->except('show');
            Route::resource('visit-reasons', 'VisitReasonController')->except('show');
            Route::resource('gates', 'GateController')->except('show');
            Route::resource('companies', 'CompanyController')->except('show');
            Route::resource('contract-types', 'ContractTypeController')->except('show');
            Route::get('/edit', 'SettingController@getAllSettings')->name('settings.edit');
            Route::post('/update-setting', 'SettingController@updateAllSettings')->name('settings.updateSetting');
            Route::get('get-cities', 'CityController@getCities')->name('getCities');
        });
        /************************************* Settings Routes **************************************/

        /************************************ Visits Request **********************************/
        Route::group(['namespace' => 'Visits'], function () {
            //Visits Requests
            Route::resource('visits', 'VisitRequestController')->except('show');
            Route::post('visits/{visit}/status', 'VisitRequestController@status')->name('visits.status');

            //Visit Activities
            Route::get('visits-activities/{id}/visitorData', 'VisitActivityController@visitorData')->name('visits-activities.visitorData');
            Route::post('visits-activities/storeVisitor', 'VisitActivityController@storeVisitor')->name('visits-activities.storeVisitor');
            Route::post('visits-activities/send-message', 'VisitActivityController@sendMessage')->name('visits-activities.sendMessage');
            Route::post('visits-activities/resend-link', 'VisitActivityController@resendLink')->name('visits-activities.resendLink');
            Route::put('visits-activities/{id}/action', 'VisitActivityController@takeAction')->name('visits-activities.takeAction');
            Route::post('visits-activities/storeVisit', 'VisitActivityController@storeVisit')->name('visits-activities.storeVisit');
            Route::get('visits-activities/newVisit', 'VisitActivityController@newVisit')->name('visits-activities.new');
            Route::get('visits-activities', 'VisitActivityController@index')->name('visits-activities');

            //Visitors Request
            Route::resource('visitors', 'VisitorController')->except('show');
            Route::get('visitors-by-id', 'VisitorController@getVisitorByIds');
            Route::get('get-visitor', 'VisitorController@getVisitor');
        });
        /************************************ Visits Request ********************************/

        /************************************ Materials Request **********************************/
        Route::group(['namespace' => 'Materials'], function () {
            //Materials Requests
            Route::resource('material-requests', 'MaterialRequestController')->except('show');
            Route::post('material-requests/{materialRequest}/material-status', 'MaterialRequestController@status')->name('materialRequests.status');
            Route::post('material-requests/{materialRequest}/approved-status', 'MaterialRequestController@approvedStatus')->name('materialRequests.approvedStatus');

            //Materials Activities
            Route::get('material-activities', 'MaterialActivityController@materialRequesterTasks')->name('materialActivities');
            Route::get('material-info/{id}', 'MaterialActivityController@getRequesterMaterialInfo')->name('getRequesterMaterialInfo');
            Route::put('material-action', 'MaterialActivityController@materialAction')->name('materialAction');

            //Materials Request
            Route::resource('materials', 'MaterialController')->except('show');
            Route::get('get-material', 'MaterialController@getMaterial');
            Route::get('materials-by-id', 'MaterialController@getMaterialByIds');
        });
        /************************************ Materials Request ********************************/

        /************************************ Contracts Request **********************************/
        Route::group(['namespace' => 'Contracts'], function () {
            //Contract Requests
            Route::resource('contract-requests', 'ContractRequestController')->except('show');

            //Contract Crud
            Route::get('get-supervisors/{company_id}', 'ContractController@getSupervisors')->name('getSupervisors');
            Route::get('get-contracts/{company_id}', 'ContractController@getContracts')->name('getContracts');
            Route::get('get-contract-dates/{contract_id}', 'ContractController@getContractDates')->name('getContractDates');
            Route::resource('settings/contracts', 'ContractController')->except('show');
        });
        /************************************ Contracts Request ********************************/

        /************************************ Cars Request **********************************/
        Route::group(['namespace' => 'Cars'], function () {
            //Car Requests
            Route::resource('car-requests', 'CarRequestController')->except('show');
            Route::post('car-requests/uploadExcel', 'CarRequestController@uploadExcel')->name('carRequests.uploadExcel');
            Route::post('car-requests/{carRequest}/approved-status', 'CarRequestController@approvedStatus')->name('carRequests.approvedStatus');

            //Car Request Details
            Route::get('search-car', 'CarRequestDetailsController@searchWithCar')->name('searchWithCar');
            Route::get('get-car-request/{id}', 'CarRequestDetailsController@getCarRequest')->name('getCarRequest');
            Route::post('car-request-action', 'CarRequestDetailsController@carAction')->name('carRequestAction');
        });
        /************************************ Cars Request ********************************/

        //Permissions
        Route::get('permission-meetings', 'PermissionController@index')->name('permissionMeetings');

        //Meeting info
        Route::get('meetings-invitation/{visit_id}/{visitor_id}/{secret_code}', 'MeetingController@index')->name('visitor-get-request');
        Route::get('contractor-meetings-invitation/{contract_request_id}/{contractor_id}/{secret_code}', 'MeetingController@contractRequest')->name('contractor-get-request');
        Route::get('material-meetings-invitation', 'MeetingController@materialRequest')->name('material-get-request');
        Route::post('meeting-confirmation', 'MeetingController@confirm');
        Route::post('contract-meeting-confirmation', 'MeetingController@confirmContractRequest');
        Route::get('meeting-cancel/{id}', 'MeetingController@cancel')->name('meeting-cancel');
        Route::get('contract-meeting-cancel/{id}', 'MeetingController@contractRequestcancel')->name('contract-meeting-cancel');

        //Guard
        Route::get('guard', 'GuardController@index')->name('guard.index');
        Route::get('guard/visit-scan', 'GuardController@scan')->name('guard.visit_scan');
        Route::get('guard/visit-data', 'GuardController@search')->name('guard.visit_search');
        Route::post('guard/action', 'GuardController@takeAction')->name('guard.action');
        Route::get('materialguard', 'GuardController@materialIndex')->name('materialguard.index');
        Route::get('materialguard/material-data', 'GuardController@materialSearch')->name('materialguard.material_search');
        Route::post('materialguard/action', 'GuardController@materialAction')->name('materialguard.action');
        Route::post('carguard/action', 'GuardController@carAction')->name('carguard.action');
        Route::get('carguard/car-data', 'GuardController@carSearch')->name('carguard.car_search');
        Route::get('guard/contract-visit-data', 'GuardController@ContractSearch')->name('guard.contract_visit_search');
        Route::post('guard/contractor-action', 'GuardController@takeContractorAction')->name('guard.contractVisitAction');

        //Tasks
        Route::get('tasks', 'TaskController@index')->name('tasks');
        Route::get('task/{id}', 'TaskController@getTaskInfo')->name('getTaskInfo');
        Route::put('task-action', 'TaskController@taskAction')->name('taskAction');
        Route::get('material-task/{id}', 'TaskController@getMaterialTaskInfo')->name('getMaterialTaskInfo');
        Route::put('material-task-action', 'TaskController@materialTaskAction')->name('materialTaskAction');
        Route::get('car-task/{id}', 'TaskController@getCarTaskInfo')->name('getCarTaskInfo');
        Route::put('car-task-action', 'TaskController@carTaskAction')->name('carTaskAction');
        Route::get('contractor-task/{id}', 'TaskController@getContractorTaskInfo')->name('getContractorTaskInfo');
        Route::put('contractor-task-action', 'TaskController@contractorTaskAction')->name('contractorTaskAction');

        //Notification Routes
        Route::get('notifications', 'NotificationController@index')->name('notifications');
        Route::delete('notifications/{id}/destroy', 'NotificationController@destroy')->name('notifications.destroy');
        Route::get('/update_notification', 'HomeController@updateNotification');

        //Mail Templates
        Route::resource('mail_template', 'MailTemplateController');
    });
    /*========================= Dashboard Routes ==============================*/
});



//Route::get('add-data', function (){
//    \App\Models\Guide::where('id', '!=', 0)->delete();
//    $guides = [
//        [
//            'name' => 'علي أحمد',
//            'nickName' => 'علي',
//            'description' => 'خبير في تسلق الجبال مع أكثر من 15 عامًا من الخبرة.',
//            'experience' => 15,
//            'regions' => [1, 3],
//            'languages' => [1, 2],
//            'facebook' => 'https://facebook.com/ali',
//            'twitter' => 'https://twitter.com/ali',
//            'instagram' => 'https://instagram.com/ali',
//            'linkedin' => 'https://linkedin.com/in/ali',
//            'personal_account' => 'https://ali.com',
//            'user_id' => 6,
//        ],
//        [
//            'name' => 'محمد حسن',
//            'nickName' => 'محمد',
//            'description' => 'مرشد سياحي متخصص في الجولات التاريخية.',
//            'experience' => 8,
//            'regions' => [2],
//            'languages' => [1, 2],
//            'facebook' => 'https://facebook.com/mohammed',
//            'twitter' => 'https://twitter.com/mohammed',
//            'instagram' => 'https://instagram.com/mohammed',
//            'linkedin' => 'https://linkedin.com/in/mohammed',
//            'personal_account' => 'https://mohammed.com',
//            'user_id' => 19,
//        ],
//        [
//            'name' => 'فاطمة علي',
//            'nickName' => 'فاطمة',
//            'description' => 'خبيرة في الطبيعة والحياة البرية.',
//            'experience' => 10,
//            'regions' => [1, 2],
//            'languages' => [1, 2],
//            'facebook' => 'https://facebook.com/fatma',
//            'twitter' => 'https://twitter.com/fatma',
//            'instagram' => 'https://instagram.com/fatma',
//            'linkedin' => 'https://linkedin.com/in/fatma',
//            'personal_account' => 'https://fatma.com',
//            'user_id' => 22,
//        ],
//        [
//            'name' => 'عمر عبد الله',
//            'nickName' => 'عمر',
//            'description' => 'خبير في الجولات الثقافية وجولات الطعام.',
//            'experience' => 6,
//            'regions' => [3],
//            'languages' => [1, 2],
//            'facebook' => 'https://facebook.com/omar',
//            'twitter' => 'https://twitter.com/omar',
//            'instagram' => 'https://instagram.com/omar',
//            'linkedin' => 'https://linkedin.com/in/omar',
//            'personal_account' => 'https://omar.com',
//            'user_id' => 23,
//        ],
//        [
//            'name' => 'مريم خالد',
//            'nickName' => 'مريم',
//            'description' => 'متخصصة في جولات المشي والمغامرة.',
//            'experience' => 12,
//            'regions' => [1, 4],
//            'languages' => [1, 2],
//            'facebook' => 'https://facebook.com/mariam',
//            'twitter' => 'https://twitter.com/mariam',
//            'instagram' => 'https://instagram.com/mariam',
//            'linkedin' => 'https://linkedin.com/in/mariam',
//            'personal_account' => 'https://mariam.com',
//            'user_id' => 27,
//        ],
//        [
//            'name' => 'خالد إبراهيم',
//            'nickName' => 'خالد',
//            'description' => 'خبير في الجولات الريفية والثقافية.',
//            'experience' => 9,
//            'regions' => [2, 3],
//            'languages' => [1, 2],
//            'facebook' => 'https://facebook.com/khaled',
//            'twitter' => 'https://twitter.com/khaled',
//            'instagram' => 'https://instagram.com/khaled',
//            'linkedin' => 'https://linkedin.com/in/khaled',
//            'personal_account' => 'https://khaled.com',
//            'user_id' => 29,
//        ]
//    ];
//
//    foreach ($guides as $guide) {
//        $guide_record = \App\Models\Guide::create($guide);
//        $guide_record->translateOrNew('ar')->name = $guide['name'];
//        $guide_record->translateOrNew('ar')->nickName = $guide['nickName'];
//        $guide_record->translateOrNew('ar')->description = $guide['description'];
//        $locales = locales("ar");
//        foreach ($locales as $locale) {
//            $name = GoogleTranslate::trans($guide['name'] ?? '', $locale);
//            $nickName = GoogleTranslate::trans($guide['nickName'] ?? '', $locale);
//            $description = GoogleTranslate::trans($guide['description'] ?? '', $locale);
//            $guide_record->translateOrNew($locale)->name = $name;
//            $guide_record->translateOrNew($locale)->nickName = $nickName;
//            $guide_record->translateOrNew($locale)->description = $description;
//        }
//
//            $guide_record->save();
//    }
//
//    return 'done';
//});


Route::get('fix-events-slug', function (){

    $events = \App\Models\Event::all();
    foreach ($events as $event) {
        if (! $event->slug) {
            if ($event->translate('en')){
                $event->slug = Str::slug($event->translate('en')->title);
            }elseif($event->translate('ar')){
                $title = GoogleTranslate::trans($event->translate('ar')->title ?? '', 'en');
                $event->slug = Str::slug($title);
            }
            $event->save();
        }
    }

    return 'success';
});

Route::get('update-rate-type', function (){
    Rate::where('type', 'places')->update(['type' => 'place']);
    Rate::where('type', 'stores')->update(['type' => 'store']);
    Rate::where('type', 'zad_elgadels')->update(['type' => 'zad']);
    Rate::where('type', 'swalefs')->update(['type' => 'swalef']);
    Rate::where('type', 'events')->update(['type' => 'event']);

    return 'success';
});



Route::get('get-trip/{token}', function ($token){
    $trip = Trip::where('token', $token)->first();
    return response()->json($trip);
});


//Route::get('create-permission', function (){
//    $records = [
//        [
//            'name' => 'patch-places',
//            'label' => 'Patch places',
//            'model' => 'places',
//            'guard_name' => 'web',
//        ],
//        [
//            'name' => 'patch-users_place',
//            'label' => 'Patch Users_places',
//            'model' => 'Users_places',
//            'guard_name' => 'web',
//        ],
//        [
//            'name' => 'patch-store',
//            'label' => 'Patch store',
//            'model' => 'store',
//            'guard_name' => 'web',
//        ],
//        [
//            'name' => 'patch-zadElgadel',
//            'label' => 'Patch zadElgadel',
//            'model' => 'zadElgadel',
//            'guard_name' => 'web',
//        ],
//        [
//            'name' => 'patch-swalefs',
//            'label' => 'Patch swalefs',
//            'model' => 'swalefs',
//            'guard_name' => 'web',
//        ],
//        [
//            'name' => 'patch-event',
//            'label' => 'Patch event',
//            'model' => 'event',
//            'guard_name' => 'web',
//        ],
//        [
//            'name' => 'patch-guides',
//            'label' => 'Patch Guides',
//            'model' => 'Guides',
//            'guard_name' => 'web',
//        ],
//    ];
//
//    foreach ($records as $record) {
//        \App\Models\Permission::create($record);
//    }
//
//    return 'done';
//});
//
//
//Route::get('attach-permissions-to-super-admin', function () {
//    $role = Role::where('name', 'root')->firstOrFail();
//
//    $permissions = Permission::whereIn('name', [
//        'patch-places',
//        'patch-users_place',
//        'patch-store',
//        'patch-zadElgadel',
//        'patch-swalefs',
//        'patch-event',
//        'patch-guides',
//    ])->get();
//
//    $role->givePermissionTo($permissions);
//
//    return 'permissions attached to super admin';
//});
