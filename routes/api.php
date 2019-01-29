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

//Route::get('/update-currency', function (Request $request) {
//    return 'GOOD';
//});