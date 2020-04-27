<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'UserController@register')->name('api.register');
Route::post('login', 'UserController@authenticate');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('user', 'UserController@getAuthenticatedUser');
});

Route::group(['middleware' => ['check.server']], function () {

    Route::post('/search', '\App\Http\Controllers\API\CustomerController@findSuspiciousCustomer');
    Route::post('/search/organization',
    '\App\Http\Controllers\API\CustomerController@findSuspiciousAllyOrganization');
//
Route::prefix('research')->namespace('Customers')->group(function () {
    Route::post('/customerVsSuspects', '\App\Http\Controllers\API\CustomerController@clientsVsSuspectsResearch')->name('customerVsSuspects');
    Route::post('/suspectsVsCustomers', '\App\Http\Controllers\API\CustomerController@suspectsVsCustomersResearch')->name('suspectsVsCustomers');
    Route::post('/exemine_mia_suspects', '\App\Http\Controllers\API\CustomerController@exemineMIASuspectsVsCustomers')->name('exemine_mia_suspects');
    Route::post('/exemine_un_suspects', '\App\Http\Controllers\API\CustomerController@exemineUNSuspectsVsCustomers')->name('exemine_un_suspects');
    Route::post('/exemine_ip_suspects', '\App\Http\Controllers\API\CustomerController@exemineIPSuspectsVsCustomers')->name('exemine_ip_suspects');

});

Route::prefix('suspicious')->namespace('Customers')->group(function () {
    Route::post('/', '\App\Http\Controllers\API\CustomerController@getSuspiciousCustomers');
});

Route::prefix('clients')->namespace('Customers')->group(function () {

    Route::post('/save', '\App\Http\Controllers\API\CustomerController@saveCrmClient');
    Route::put('/update', '\App\Http\Controllers\API\CustomerController@updateCrmClient');

});

});





