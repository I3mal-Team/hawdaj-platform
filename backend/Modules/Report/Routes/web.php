<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [
    'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    Route::prefix('dashboard/report')->group(function () {
        Route::get('/builder', 'ReportController@index');
        Route::get('{type}/filter', 'ReportController@filter');
        Route::get('archive', 'ReportController@archive');
        Route::get('show', 'ReportController@show');
        Route::get('get_sites', 'ReportController@getsite')->name('dashboard.report.get_sites');
        Route::get('handle-report', 'ReportController@show')->name('dashboard.handle-report');
        Route::get('{modalType}/files', 'ArchiveFilesController@index')->name('dashboard.files.index');
        Route::get('files/{file}/download', 'ArchiveFilesController@download')->name('dashboard.files.download');
        Route::delete('files/destroy/{file}', 'ArchiveFilesController@destroy')->name('dashboard.files.destroy');
        Route::get('files/download/{file}', 'ArchiveFilesController@downloadFile');

        Route::resource('draft', 'DraftController')->except('show','store');
        Route::post('draft-this', 'DraftController@storeDraft');
        Route::get('draft/{id}/draw', 'DraftController@drawDraft');

        Route::resource('pinned', 'PinnedController')->except('show','store');
        Route::post('pinned/add-draft', 'PinnedController@addDraft');
        Route::get('pinned/get-related-draft', 'PinnedController@getRelatedDraft');
        Route::get('pinned/{id}/draw', 'PinnedController@drawPinned');
        Route::post('pinned/{id}/status', 'PinnedController@status')->name('pinned.status');
        Route::get('pinned/{id}/reload', 'PinnedController@reload')->name('pinned.reload');
    });

    Route::prefix('dashboard/config')->group(function () {
        Route::get('/', 'ConfigController@index')->name('dashboard.config.index');
        Route::post('update', 'ConfigController@update')->name('dashboard.config.update');
        Route::post('chart-details', 'ConfigController@updateDetails')->name('dashboard.config.chart_details');
    });
});
