<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/update-currency', 'CurrencyController@updateList');

Route::get('/delete-monitoring-list', 'CurrencyController@deleteList');

Route::get('/delete-currency/{id}', 'CurrencyController@deleteCurrency');

Route::post('/update-settings', 'CurrencyController@updateCurrencySettings');

Route::post('/add-currency', 'CurrencyController@addCurrency');

Route::get('/add-all-currency', 'CurrencyController@addAllCurrencyList');

Route::post('/add-global-settings', 'SettingsController@saveGlobalSettings');

Route::post('/cast-create', 'ProcessingController@castCreate');

Route::post('/cast-delete', 'ProcessingController@castDelete');

Route::get('/start-monitoring-process', 'ProcessingController@startMonitoringProcess');

Route::get('/stop-monitoring-process', 'ProcessingController@stopMonitoringProcess');

Route::post('/alarm-save', 'AlarmsController@saveAlarm');

Route::get('/alarm-save-all', 'AlarmsController@saveAll');

Route::post('/alarm-delete', 'AlarmsController@deleteAlarm');

Route::get('/alarm-delete-all', 'AlarmsController@deleteAll');

Route::post('/list-name-create', 'CurrencyController@newListCreate');

//Route::get('/update-currency', function (Request $request) {
//    return 'GOOD';
//});