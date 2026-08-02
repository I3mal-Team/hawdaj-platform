<?php


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'maintanis']
], function () {

    Route::prefix('dashboard')->group(function () {
        Route::get('cars', 'CarModelController@index');
        Route::get('cars/{site}', 'CarModelController@index');
        Route::get('car-table/{site}', 'CarModelController@getCarTable');
        Route::post('car/setting', 'CarModelController@saveSetting');
        Route::post('cars/takeAction/{car}', 'CarModelController@takeAction');
        Route::post('export_file_car', 'CarModelController@exportFile');
        Route::resource('/car_notes', 'CarNotesController');
        Route::post('cars/live-mode', 'CarModelController@liveMode')->name('dashboard.car.live_mode');
    });
});
