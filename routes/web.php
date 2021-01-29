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


Route::group(['prefix' => 'admin','middleware' => 'superAdmin'], function () {
	Route::get('admin-dashboard','Admin\DashboardController@index')->name('admin.dashboard');
	Route::get('admin-logout','Admin\AuthController@logout')->name('admin.logout');
	Route::resource('product','Admin\ProductController');
	Route::post('product_variant','Admin\ProductController@productVariant');
	Route::post('delete-product-image',['as'=>'remove.pimage','uses'=>'Admin\ProductController@removeImage']);
	Route::post('delete-product-variant',['as'=>'delete.variant','uses'=>'Admin\ProductController@removeVariant']);
	Route::resource('categories','Admin\CategoriesController');
	Route::resource('options','Admin\OptionController');
	Route::resource('option_values','Admin\OptionValueController');
	Route::resource('brands','Admin\BrandController');
	Route::resource('product-units','Admin\ProductUnitController');
	Route::resource('vendor','Admin\VendorController');
	Route::resource('delivery_zone','Admin\DeliveryZoneController');
	Route::resource('comission_value','Admin\ComissionValueController');
	
	Route::resource('departments','Admin\DepartmentController');
	Route::resource('employees','Admin\EmployeeController');

	Route::get('salary-list','Admin\EmployeeController@salaryList')->name('salary.list');
	Route::get('payment_form','Admin\EmployeeController@paymentForm');
	Route::post('confirm_salary','Admin\EmployeeController@confirmSalary')->name('confirm.salary');

	Route::get('get-state-list','Admin\DashboardController@getStateList');
	Route::get('get-city-list','Admin\DashboardController@getCityList');

	Route::resource('settings','Admin\SettingsController');
	Route::resource('payment_method','Admin\PaymentMethodsController');
	Route::resource('purchase','Admin\PurchaseController');
	Route::get('product-search',['uses'=>'product.search','uses'=>'Admin\PurchaseController@ProductSearch']);

	Route::resource('currency','Admin\CurrencyController');
	Route::resource('return','Admin\PurchaseReturnController');

	Route::resource('access-control','Admin\AccessController');	

	Route::resource('stock-in-transit','Admin\StockInTransitController');
	Route::resource('wastage','Admin\WastageController');

	Route::get('wastage-product-search',['uses'=>'wastage.product.search','uses'=>'Admin\WastageController@ProductSearch']);	
	Route::resource('customer','Admin\CustomerController');
	Route::resource('vendor-products','Admin\VendorProdcutsController');
	Route::post('add-new-address','Admin\CustomerController@AddNewAddressController');
});