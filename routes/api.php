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
Route::get('home','Api\ShopController@home');

//Category
Route::get('category/{category_id}','Api\ShopController@category');

//Product
Route::get('products/{product_id}','Api\ShopController@product');
Route::get('country','Api\RFQApiController@AllCountries');
Route::get('state/{country_id}','Api\RFQApiController@StatesBasedCountry');
Route::get('city/{state_id}','Api\RFQApiController@CityBasedState');

//Authenticated API's
Route::middleware('auth:api')->group( function () {
	Route::get('logout', 'Api\AuthController@logout');
	Route::get('counts', 'Api\ShopController@getCounts');

	//Cart
	Route::resource('my-cart','Api\CartController');
	Route::get('delete-cart/{uniqid}','Api\CartController@DeleteCart');
	
	Route::get('update-primary-address/{address_id}','Api\RFQApiController@UpdatePrimaryAddress');

	//RFQ
	Route::resource('all-address','Api\AddressController');
	Route::resource('rfq-api','Api\RFQApiController');
	Route::get('rfq-list','Api\RFQApiController@RFQLIst');
	Route::post('add-address','Api\RFQApiController@AddAddress');
	Route::post('update-address','Api\RFQApiController@UpdateAddress');
	Route::get('delete-address/{address_id}','Api\AddressController@DeleteAddress');

	//Profile
	Route::get('profile','Api\ProfileController@index');
	Route::post('update-profile','Api\ProfileController@updateProfile');
	Route::post('update-bank-data','Api\ProfileController@updateBankData');
	Route::post('update-poc-data','Api\ProfileController@updatePOCData');

	//Wishlist
	Route::get('my-wishlist', 'Api\ProfileController@wishlistGet');
	Route::post('my-wishlist', 'Api\ProfileController@addWishlist');
});