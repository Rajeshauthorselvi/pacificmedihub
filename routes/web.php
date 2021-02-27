<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
Route::get('login','WhoYouAreController@index');
Route::resource('employee', 'Employee\EmployeeLoginController');

Route::group(['prefix' =>'admin','middleware' => ['superAdmin','employee']], function () {
	//Admin
	Route::get('admin-dashboard','Admin\DashboardController@index')->name('admin.dashboard');
	Route::get('admin-logout','Admin\AuthController@logout')->name('admin.logout');
	Route::resource('admin-profile','Admin\AdminProfileController');

	//Products
	Route::resource('products','Admin\ProductController');
	Route::post('product_variant','Admin\ProductController@productVariant');
	Route::post('delete-product-image',['as'=>'remove.pimage','uses'=>'Admin\ProductController@removeImage']);
	Route::post('delete-product-variant',['as'=>'delete.variant','uses'=>'Admin\ProductController@removeVariant']);
	Route::resource('categories','Admin\CategoriesController');
	Route::resource('options','Admin\OptionController');
	Route::post('delete-option-value',[
		'as'=>'delete.option.value','uses'=>'Admin\OptionController@deleteOptionValue'
	]);
	Route::resource('brands','Admin\BrandController');
	Route::get('product-import',['as'=>'product.import','uses'=>'Admin\ProductController@ProductImportController']);
	Route::post('post-product-import',[
		'as'=>'post.product.import','uses'=>'Admin\ProductController@ProductImportPostController'
	]);
	Route::get('product-sample-sheet','Admin\ProductController@DownloadSampleImportSheet');
	
	//Purchase
	Route::resource('purchase','Admin\PurchaseController');
	Route::get('product-search',['uses'=>'product.search','uses'=>'Admin\PurchaseController@ProductSearch']);
	Route::get('view_purchase_payment/{purchase_id}',[
		'as'=>'view.purchase.payment','uses'=>'Admin\PurchaseController@ViewPurchasePayment'
	]);
	Route::post('update_purchase_payment',[
		'as'=>'edit.purchase.payment','uses'=>'Admin\PurchaseController@UpdatePurchasePayment'
	]);
	Route::post('create_purchase_payment',[
		'as'=>'create.purchase.payment','uses'=>'Admin\PurchaseController@CreatePurchasePayment'
	]);
	Route::post('search-vendor','Admin\PurchaseController@FindVendors');
	Route::get('purchase-history',['as'=>'purchase.history','uses'=>'Admin\PurchaseController@PurchaseHistory']);
	
	//Stock In Transit
	Route::post('purchase-stock-history', 'Admin\StockInTransitController@ListPurchaseStockHistory');
	Route::resource('stock-in-transit','Admin\StockInTransitController');
	Route::resource('return','Admin\PurchaseReturnController');
	Route::get('search-purchase-no','Admin\PurchaseReturnController@searchPurchaseNo');
	Route::get('view_return_payment/{return_id}',[
		'as'=>'view.return.payment','uses'=>'Admin\PurchaseReturnController@ViewReturnPayment'
	]);
	Route::post('create_return_payment',[
		'as'=>'create.return.payment','uses'=>'Admin\PurchaseReturnController@CreatePurchasePayment'
	]);
	Route::resource('wastage','Admin\WastageController');
	Route::get('wastage-product-search',[
		'as'=>'wastage.product.search','uses'=>'Admin\WastageController@ProductSearch'
	]);

	//RFQ
	Route::resource('rfq','Admin\RFQController');
	Route::get('rfq-delete/{id}',['as'=>'rfq.delete','uses'=>'Admin\RFQController@destroy']);
	Route::get('rfq-product',['as'=>'rfq.product','uses'=>'Admin\RFQController@ProductSearch']);
	Route::get('rfq-to-order/{id}','Admin\OrderController@rfqToOrder')->name('rfq.toOrder');

	//Orders
	Route::resource('orders','Admin\OrderController');
	Route::get('orders-product',['as'=>'orders.product','uses'=>'Admin\OrderController@ProductSearch']);
	Route::post('create_order_payment',[
		'as'=>'create.order.payment','uses'=>'Admin\OrderController@CreatePurchasePayment'
	]);
	Route::get('view_order_payment/{order_id}',[
		'as'=>'view.order.payment','uses'=>'Admin\OrderController@ViewPurchasePayment'
	]);

	//Customer
	Route::resource('customers','Admin\CustomerController');
	Route::get('edit-address-form','Admin\CustomerController@editAddressForm');
	Route::post('save-address-form','Admin\CustomerController@saveAddressForm')->name('save.address');
	Route::resource('vendor-products','Admin\VendorProdcutsController');
	Route::post('add-new-address','Admin\CustomerController@AddNewAddressController');


	//Vendor
	Route::resource('vendor','Admin\VendorController');
	
	//Employee	
	Route::resource('employees','Admin\EmployeeController');
	Route::get('salary-list/{date}','Admin\EmployeeController@salaryList')->name('salary.list');
	Route::get('payment_form','Admin\EmployeeController@paymentForm');
	Route::post('confirm_salary','Admin\EmployeeController@confirmSalary')->name('confirm.salary');
	Route::get('salary-view/{emp_id}/{page}','Admin\EmployeeController@salaryView')->name('salary.view');
	Route::get('pay-slip/{emp_id}/{page}','Admin\EmployeeController@salaryView')->name('pay.slip');
	Route::get('employee-commission-list/{emp_id}','Admin\EmployeeController@commissionList')->name('emp.commission.list');


	//Delivery Zone
	Route::resource('delivery_zone','Admin\DeliveryZoneController');


	/* Settings */
	//Department
	Route::resource('departments','Admin\DepartmentController');
	//Commission
	Route::resource('comission_value','Admin\ComissionValueController');
	//Prefix
	Route::resource('settings','Admin\SettingsController'); //Old
	Route::resource('settings-prefix','Admin\PrefixController'); //New
	//Tax
	Route::resource('tax','Admin\TaxController');
	//Currency
	Route::resource('currency','Admin\CurrencyController');
	//Payment Method
	Route::resource('payment_method','Admin\PaymentMethodsController');
	//Access Control	
	Route::resource('access-control','Admin\AccessController');	


	//Get Sate and City
	Route::get('get-state-list','Admin\DashboardController@getStateList');
	Route::get('get-city-list','Admin\DashboardController@getCityList');

	//Get Commission Value
	Route::get('get-commission-value','Admin\DashboardController@commissionValue');

});

//Error Page
Route::get('oops','Admin\DashboardController@errorPage')->name('error.page');	
Route::get('about-us','front\AboutController@index');
Route::get('home','front\HomePageController@index');
Route::get('','front\HomePageController@index');
Route::get('{slug}','front\ShopController@category');
Route::get('{category_slug}/{product_slug}','front\ShopController@product');
/*Route::resource('home','HomeController');
Route::get('/','HomeController@index');*/