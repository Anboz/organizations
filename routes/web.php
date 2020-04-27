<?php

Route::get('logout', 'Auth\LoginController@logout');
Auth::routes(['register' => true]);

//Route::middleware('auth')
   // ->group(function () {

        Route::get('export', '\App\Http\Controllers\BladesControllers\XmlExcelImporterController@export')->name('export');
        Route::get('importExportView', '\App\Http\Controllers\BladesControllers\XmlExcelImporterController@importExportView');

        // upload suspect list or organization xls list

        Route::prefix('import')->group(function () {
            Route::get('/', '\App\Http\Controllers\BladesControllers\XmlExcelImporterController@importExportView');
            Route::post('/', '\App\Http\Controllers\BladesControllers\XmlExcelImporterController@import')->name('import');
            Route::post('/organization',
                '\App\Http\Controllers\BladesControllers\XmlExcelImporterController@importOrganizations')->name('import.organizations');
            Route::post('/excels', '\App\Http\Controllers\BladesControllers\XmlExcelImporterController@importOtherExcels')->name('import.excels');
            Route::post('/xml', '\App\Http\Controllers\BladesControllers\XmlExcelImporterController@importXml')->name('import.xml');
        });

        // delete suspects according to their organization

        Route::prefix('delete')->group(function () {
            Route::post('/interpol', '\App\Http\Controllers\BladesControllers\BladeController@deleteInterpolList')->name('delete.interpol');
            Route::post('/un', '\App\Http\Controllers\BladesControllers\BladeController@deleteUnitedNationsList')->name('delete.un');
            Route::post('/mia',
                '\App\Http\Controllers\BladesControllers\BladeController@deleteMinistryOfInternalAffairsList')->name('delete.mia');
        });

        Route::get('/', '\App\Http\Controllers\BladesControllers\BladeController@index')->name('list');
        Route::get('/scanner', '\App\Http\Controllers\BladesControllers\BladeController@scanner')->name('scanner');
        Route::get('/order', '\App\Http\Controllers\BladesControllers\BladeController@order')->name('order');
        Route::get('/column', '\App\Http\Controllers\BladesControllers\BladeController@column')->name('column');
        Route::get('/search', '\App\Http\Controllers\BladesControllers\BladeController@search')->name('search');
        Route::get('/search_organization', '\App\Http\Controllers\BladesControllers\BladeController@searchOrganization')->name('search.organization');
        //===================================
        Route::post('/parse',['uses'=>'Parsing\ParsController@pars',"as"=>'pars']);
		Route::post('/parsesait',['uses'=>'Parsing\ParsingSaitController@pars',"as"=>'parsSait']);
        Route::get('/home', 'HomeController@index')->name('home');
   // });
