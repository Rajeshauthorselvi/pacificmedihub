<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

//Admin Routes
Route::get('admin','Admin\AuthController@index')->name('admin.login');
Route::post('admin','Admin\AuthController@store')->name('admin.store');

Route::group(['prefix' => 'admin'], function () {
	Route::get('admin-dashboard','Admin\DashboardController@index')->name('admin.dashboard')->middleware('superAdmin');
	Route::get('admin-logout','Admin\AuthController@logout')->name('admin.logout')->middleware('superAdmin');
	Route::resource('product','Admin\ProductController')->middleware('superAdmin');
	Route::post('product_variant','Admin\ProductController@productVariant')->middleware('superAdmin');
	Route::resource('categories','Admin\CategoriesController')->middleware('superAdmin');
	Route::resource('options','Admin\OptionController')->middleware('superAdmin');
	Route::resource('option_values','Admin\OptionValueController')->middleware('superAdmin');
	Route::resource('brands','Admin\BrandController')->middleware('superAdmin');
	Route::resource('product-units','Admin\ProductUnitController')->middleware('superAdmin');
	Route::resource('vendor','Admin\VendorController')->middleware('superAdmin');
	Route::resource('delivery_zone','Admin\DeliveryZoneController')->middleware('superAdmin');
	Route::resource('comission_value','Admin\ComissionValueController')->middleware('superAdmin');
});
