<?php

use App\Http\Controllers\Api\ApplicationCategoryController;
use App\Http\Controllers\Api\PropertiesController;
use App\Http\Controllers\Api\SwalefCategoryController;
use App\Http\Controllers\Api\ApplicationsController;
use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\Auth\SocialController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactusController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LandMarksController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\PlaceController;
use App\Http\Controllers\Api\PriceController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SavedController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\StoreCategoryController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StoryController;
use App\Http\Controllers\Api\SwalefController;
use App\Http\Controllers\Api\ZadCategoryController;
use App\Http\Controllers\Api\ZadFoodCategoryController;
use App\Http\Controllers\Api\ZadController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\EnhancedTripController;
use App\Http\Controllers\Api\EnhancedTripV2Controller;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GuideController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\SubscribeController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SliderController;

Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('logout', [AuthenticationController::class, 'logout']);
Route::post('social/callback', [SocialController::class, 'callback']);

// Forget password
Route::post('forgot-password', [ForgotPasswordController::class, 'forgot']);
Route::post('verify-email-code', [ForgotPasswordController::class, 'verify']);

// Reset password
//Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset']);

/*========================= Front Routes ==============================*/
Route::group(['as' => 'front.', 'namespace' => 'Front'], function () {

    // home
    Route::get('home', [HomeController::class,'getHome']);

    Route::get('sliders', [SliderController::class, 'index']);

    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{event}', [EventController::class, 'show']);

    Route::get('applications', [ApplicationsController::class, 'index']);
//    Route::get('applications/{application}', [ApplicationsController::class, 'show']);
    Route::get('applications/categories', [ApplicationCategoryController::class, 'index']);

    Route::post('properties', [PropertiesController::class, 'store']);
    Route::post('zad-elgadel', [PropertiesController::class, 'store_zad_elgadel'])->name('api.zad-elgadel.store');
    Route::get('my-properties', [PropertiesController::class, 'index']);

    Route::get('places', [PlaceController::class, 'index']);
    Route::get('places/places-data-for-map', [PlaceController::class, 'placesDataForMap']);
    Route::get('places/{place}/related-places', [PlaceController::class, 'related_places']);
    Route::get('places/{place}', [PlaceController::class, 'show']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);
    Route::get('sub-categories/{parent_id}', [CategoryController::class, 'getSubCategories']);

    Route::get('prices', [PriceController::class, 'index']);

    Route::get('regions', [RegionController::class, 'index']);
    Route::get('regions/{id}', [RegionController::class, 'show']);
    Route::get('regions/{id}/cities', [RegionController::class, 'citiesByRegionId']);

    Route::get('cities', [CityController::class, 'index']);
    Route::get('cities/{city}', [CityController::class, 'show']);

    Route::get('languages', [LanguageController::class, 'index']);
    Route::get('languages/{city}', [LanguageController::class, 'show']);

    Route::get('stores', [StoreController::class, 'index']);
    Route::get('stores/categories', [StoreCategoryController::class, 'index']);
    Route::get('stores/stats', [StoreController::class, 'stats']);
    Route::get('stores/{store}/related-stores', [StoreController::class, 'related_stores']);
    Route::get('stores/{store}', [StoreController::class, 'show']);

    Route::get('zads', [ZadController::class, 'index']);
    Route::get('zads/categories', [ZadCategoryController::class, 'index']);
    Route::get('zads/food-categories', [ZadFoodCategoryController::class, 'index']);
    Route::get('zads/{zadElgadel}', [ZadController::class, 'show']);
    Route::get('zads/{zad}/offers', [OfferController::class,'offers']);

    Route::get('zads/{zad}/menus' , [MenuController::class , 'getMenusByZad']);
    Route::get('/menus/{menu}' , [MenuController::class , 'show']);

    Route::get('swalefs', [SwalefController::class, 'index']);
    Route::get('swalefs/{id}', [SwalefController::class, 'show']);
    Route::get('swalefs-categories', [SwalefCategoryController::class, 'index']);

    Route::post('contactus/send', [ContactusController::class, 'sendFormData']);

    Route::post('/trips/prepare',[TripController::class,'prepare_trip']);
    Route::get('/trips/prepare/{token}',[TripController::class,'get_prepare_trip']);
    Route::get('/get-my-trips',[TripController::class,'get_my_trips']);
    Route::delete('/trips/prepare/{token}',[TripController::class,'delete_prepare_trip']);

    Route::post('/trips/store',[TripController::class,'save_trip']);
    Route::post('/trips/save-trip-to-email',[TripController::class,'save_trip_to_email']);

    Route::get('/my-trips',[TripController::class,'my_trips']);
    Route::get('/view_trip/{token}', [TripController::class,'view_trip']);
    Route::delete('/delete_trip/{id}', [TripController::class,'delete_trip']);

    // Enhanced Trip Routes (V2) - New improved system with morning/evening periods using existing tables
    Route::prefix('v2/trips')->group(function () {
        Route::post('/prepare', [EnhancedTripV2Controller::class, 'prepareEnhancedTrip']);
        Route::post('/reprepare', [EnhancedTripV2Controller::class, 'reprepareEnhancedTrip']);
        Route::get('/prepare/{token}', [EnhancedTripV2Controller::class, 'getEnhancedPreparedTrip']);
        Route::delete('/prepare/{token}', [EnhancedTripV2Controller::class, 'deleteEnhancedPreparedTrip']);

        Route::post('/save', [EnhancedTripV2Controller::class, 'saveEnhancedTrip']);
        Route::get('/my-trips', [EnhancedTripV2Controller::class, 'getMyEnhancedTrips']);
        Route::get('/my-prepared-trips', [EnhancedTripV2Controller::class, 'getMyEnhancedPreparedTrips']);
        Route::get('/view/{token}', [EnhancedTripV2Controller::class, 'viewEnhancedTrip']);
        Route::delete('/{token}', [EnhancedTripV2Controller::class, 'deleteEnhancedTrip']);
        Route::get('/statistics', [EnhancedTripV2Controller::class, 'getEnhancedTripStatistics']);
    });

    Route::post('/rates',[RateController::class,'store']);
    Route::delete('/rates/{id}', [RateController::class,'delete']);

    Route::post('/favorites',[FavoriteController::class,'store']);
    Route::get('/get-my-favorites',[FavoriteController::class,'listMyFavorites']);
    Route::get('/get-my-saved',[SavedController::class,'listMySaved']);
    Route::post('/saved',[SavedController::class,'store']);

    Route::post('/subscribe', [SubscribeController::class,'subscribe'])->name('subscribe');

    Route::get('services', [ServiceController::class,'services']);

    Route::get('global-search', [SearchController::class,'search']);
    Route::get('global-map-data', [SearchController::class,'global_map_data']);
    Route::get('get-social-media', [SettingController::class,'getSocialMedia']);

    // home
    Route::get('home', [HomeController::class,'getHome']);

    // landmark
    Route::post('landmark/store', [LandMarksController::class,'store']);
    Route::get('landmark/list', [LandMarksController::class,'index']);
    Route::get('landmark/myLandMarks', [LandMarksController::class,'myLandMarks']);
    Route::get('landmark/show/{id}', [LandMarksController::class,'show']);

    // stories
    Route::post('stories/store', [StoryController::class,'store']);
    Route::get('stories/list', [StoryController::class,'index']);
    Route::get('stories/myStories', [StoryController::class,'myStories']);
    Route::get('stories/myLastDayStories', [StoryController::class,'myLastDayStories']);
    Route::get('stories/show/{id}', [StoryController::class,'show']);
    Route::delete('stories/{id}/delete', [StoryController::class,'delete']);

    // profile
    Route::get('get-profile', [ProfileController::class,'getProfile']);
    Route::get('get-profile-page', [ProfileController::class,'getProfilePageData']);
    Route::post('update-profile', [ProfileController::class,'updateProfile']);
    Route::post('update-password', [ProfileController::class,'updatePassword']);
    Route::post('update-social', [ProfileController::class,'updateSocial']);
    Route::post('update-photo', [ProfileController::class,'updatePhoto']);
    Route::post('update-location', [ProfileController::class,'updateLocation']);
    Route::post('update-fcm-token', [ProfileController::class,'updateFcmToken']);
    Route::delete('delete-profile', [ProfileController::class,'deleteProfile']);

    // guide
    Route::get('guides', [GuideController::class, 'index']);
    Route::get('guides/{id}', [GuideController::class, 'show']);

    Route::post('store-guide', [GuideController::class,'storeGuide']);
    Route::get('get-guide', [GuideController::class,'getGuide']);
    Route::post('update-guide', [GuideController::class,'updateGuide']);
    Route::post('update-guide-social', [GuideController::class,'updateSocial']);
    Route::post('update-guide-photo', [GuideController::class,'updatePhoto']);

    Route::post('save-gallery', [GalleryController::class,'saveGallery']);
    Route::get('list-gallery', [GalleryController::class,'listGallery']);


    // Route::post('send', 'HomeController@uploadMessage')->name('send');

    // Route::get('sync_places_data', 'HomeController@sync_places_data');
    // Route::get('selected_places', 'HomeController@selected_places');

    // Route::post('/save_trip_to_email', 'HomeController@save_trip_to_email')->name('save_trip_to_email');
    // Route::get('/swalef/{id}', 'HomeController@one_page_swalefs')->name('swalefsonepage');
    // Route::get('/notFount', 'HomeController@notFount')->name('notFount');
    // Route::post('/add_suggest', 'ItemController@add_suggest')->name('add_suggest');
});
/*========================= Front Routes ==============================*/
