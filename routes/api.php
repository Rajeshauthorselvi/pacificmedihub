<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


//Authentication
Route::post('register', 'Api\AuthController@registerUser');
Route::post('login', 'Api\AuthController@login');

//Forget password
Route::post('send-code', 'Api\AuthController@sendCode');
Route::post('forget-password', 'Api\AuthController@forgetPassword');

//Country, State, City
Route::get('country-list','Api\AddressController@countryList');
Route::get('state-or-city-list/{id}','Api\AddressController@stateORCityList');

//Home
Route::get('home','Api\HomeController@index');

//Authenticated API's
Route::middleware('auth:api')->group( function () {

	
});