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
Route::get('login','Admin\AuthController@index');

Route::resource('employee', 'Employee\EmployeeLoginController');

Route::group(['prefix' =>'admin','middleware' => ['superAdmin','employee']], function () {

	Route::resource('notification','Admin\NotificationController');
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
	Route::get('view-attachments/{purchase_id}','Admin\PurchaseController@ViewPurchaseAttachments');
	Route::get('downlad-purchase-attachments/{attachment_id}','Admin\PurchaseController@DownloadPurchaseAttachment');
	Route::post('add-attachments','Admin\PurchaseController@AddAttachments');
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
	Route::get('puruchase_pdf/{purchase_id}','Admin\PurchaseController@PurchasePDF');
	Route::get('puruchase_print/{purchase_id}','Admin\PurchaseController@PurchasePrint');
	Route::post('search-vendor','Admin\PurchaseController@FindVendors');
	Route::get('purchase-history',['as'=>'purchase.history','uses'=>'Admin\PurchaseController@PurchaseHistory']);
	Route::get('purchase-email/{order_id}','Admin\PurchaseController@PurchaseEmail');
	Route::resource('purchase','Admin\PurchaseController');

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
	Route::post('vendor-product-price','Admin\WastageController@GetAllVendorProductPrice');
	Route::get('wastage-product-search',[
		'as'=>'wastage.product.search','uses'=>'Admin\WastageController@ProductSearch'
	]);
	Route::resource('wastage','Admin\WastageController');

	//RFQ
	Route::get('check-rfq-existing','Admin\RFQController@CheckRfqExistingPrice');
	Route::get('rfq-comments/{rfq_id}','Admin\RFQController@RFQComments');
	Route::get('view-rfq-comments-attachment/{rfq_cmt_id}','Admin\RFQController@ViewRFQCommentAttachments');
	Route::get('download-rfq-comments-attachment/{attach_id}','Admin\RFQController@DownloadRFQCommentAttachments');
	Route::post('rfq-comments-post','Admin\RFQController@RFQCommentsPost');
	Route::get('rfq_pdf/{rfq_id}','Admin\RFQController@RFQPDF');
	Route::get('rfq-delete/{id}',['as'=>'rfq.delete','uses'=>'Admin\RFQController@destroy']);
	Route::get('rfq-product',['as'=>'rfq.product','uses'=>'Admin\RFQController@ProductSearch']);
	Route::get('rfq-to-order/{id}','Admin\OrderController@rfqToOrder')->name('rfq.toOrder');
	Route::resource('rfq','Admin\RFQController');


	//Orders
	Route::get('order-email/{order_id}','Admin\OrderController@OrderEmail');
	Route::get('order-summary',['as'=>'order.summary','uses'=>'Admin\OrderController@SummaryReport']);
	Route::get('orders-product',['as'=>'orders.product','uses'=>'Admin\OrderController@ProductSearch']);
	Route::post('create_order_payment',[
		'as'=>'create.order.payment','uses'=>'Admin\OrderController@CreatePurchasePayment'
	]);
	Route::get('view_order_payment/{order_id}',[
		'as'=>'view.order.payment','uses'=>'Admin\OrderController@ViewPurchasePayment'
	]);
	Route::get('cop_print/{order_id}','Admin\OrderController@COEmployeePrint');
	Route::get('cop_pdf/{order_id}','Admin\OrderController@COPEmployeePdf');

	Route::get('cop_admin_print/{order_id}','Admin\OrderController@COAdminPrint');
	Route::get('cop_admin_pdf/{order_id}','Admin\OrderController@COAdminPdf');

	Route::get('order-purchase/{order_id}','Admin\OrderController@OrderPurchase');

	Route::post('update-order-status','Admin\OrderController@UpdateOrderStatus');
	Route::resource('orders','Admin\OrderController');
	Route::resource('new-orders','Admin\OrderController');
	Route::resource('assign-shippment','Admin\OrderController');
	Route::resource('assign-delivery','Admin\OrderController');
	Route::resource('completed-orders','Admin\OrderController');
	Route::resource('cancelled-orders','Admin\OrderController');

	//Customer
	Route::resource('customers','Admin\CustomerController');
	Route::get('edit-address-form','Admin\CustomerController@editAddressForm');
	Route::post('save-address-form','Admin\CustomerController@saveAddressForm')->name('save.address');
	Route::resource('vendor-products','Admin\VendorProdcutsController');
	Route::post('add-new-address','Admin\CustomerController@AddNewAddressController');
	Route::get('reject-or-block','Admin\CustomerController@rejectOrBlock')->name('reject.block');
	Route::get('new-customer-request','Admin\CustomerController@newCustomerList')->name('new.customer');
	Route::get('reject-customers','Admin\CustomerController@rejectCustomerList')->name('reject.customer');

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
	Route::post('delete-post-code',[
		'as'=>'delete.post.code','uses'=>'Admin\DeliveryZoneController@deletePostCode'
	]);

	//Static Page
	Route::resource('static-page','Admin\StaticPageController');
	Route::resource('static-page-slider','Admin\SliderController');
	Route::post('delete-slider-banner',[
		'as'=>'delete.slider.banner','uses'=>'Admin\SliderController@deleteSliderBanner'
	]);
	Route::resource('static-page-features','Admin\FeatureBlockController');

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
	
	Route::resource('general-settings','Admin\GeneralSettingsController');	


	//Get Sate and City
	Route::get('get-state-list','Admin\DashboardController@getStateList');
	Route::get('get-city-list','Admin\DashboardController@getCityList');
	Route::get('view_payment_history/{slip_date}','Admin\EmployeeController@ViewPaymentHistory');
	//Get Commission Value
	Route::get('get-commission-value','Admin\DashboardController@commissionValue');

	Route::get('get-delivery-address/{customer_id}','Admin\DashboardController@getDeliveryAddress');


/*	Route::get('cop_print/{order_id}','Admin\CompletedOrders@COPrint');
	Route::get('cop_pdf/{order_id}','Admin\CompletedOrders@COPPdf');
	Route::resource('delivery-assign','Admin\CompletedOrders');*/

	Route::resource('low-stocks','Admin\LowStockAlertController');
	Route::resource('low-stock-purchase','Admin\LowStockPurchaseController');
	
	Route::resource('verify-stock','Admin\StockVerifyController');
	Route::resource('delivery-methods','Admin\DeliveryMethodController');

});

//Error Page
Route::get('oops','Admin\DashboardController@errorPage')->name('error.page');	



//Customer Auth
Route::get('customer-login','front\AuthController@index')->name('customer.login');
Route::post('customer-login','front\AuthController@store')->name('customer.store');
Route::get('forget-password','front\AuthController@forgetPassword')->name('forget.password');
Route::post('reset-password','front\AuthController@resetPassword')->name('reset.password');

Route::get('new-customer','front\AuthController@newCustomerPage')->name('register.new.customer');
Route::post('new-customer','front\AuthController@newCustomerStore')->name('store.new.customer');

//Get Sate and City
Route::get('get-state','front\AddressController@getStateList');
Route::get('get-city','front\AddressController@getCityList');

Route::group(['middleware' => 'customer'], function () {
	//Customer Auth
	Route::get('customer-logout','front\AuthController@logout')->name('customer.logout');
	Route::post('change-customer-password','front\AuthController@changePassword')->name('change.cuspwd');
	
	//Customer Profile
	Route::resource('my-profile','front\ProfileController');

	//Customer Address
	Route::resource('my-address', 'front\AddressController');
	Route::get('set-primary-address/{address_id}','front\AddressController@setPrimaryAddress')->name('setPrimary.address');

	//Cart
	Route::resource('cart','front\CartController');
	Route::put('updatecart/{id}', 'front\CartController@update');
	//Wishlist
	Route::resource('wishlist', 'front\WishlistController');

	//My RFQ
	Route::get('request-rfq','front\MyRFQController@request')->name('request.rfq');
	Route::get('rfq-status','front\MyRFQController@status')->name('rfq.status');
	Route::get('send-rfq-approval/{rfq_id}', 'front\MyRFQController@sendRFQApproval')->name('send.rfq.approval');
	Route::get('child-rfq', 'front\MyRFQController@childRFQIndex')->name('child.rfq.index');
	Route::get('child-rfq-view/{rfq_id}', 'front\MyRFQController@show')->name('child.rfq.show');
	Route::post('response-child-rfq', 'front\MyRFQController@responseChild')->name('response.child.rfq');
	Route::get('my-rfq-order/{rfq_id}', 'front\MyRFQController@placeOrder')->name('my.rfq.order');
	//RFQ PDF
	Route::get('my-rfq-pdf/{rfq_id}','front\MyRFQController@RFQPDF')->name('my.rfq.pdf');
	//RFQ Comments
	Route::get('my-rfq-comments/{rfq_id}','front\MyRFQController@RFQComments')->name('my.rfq.comments');
	Route::post('my-rfq-comments-post','front\MyRFQController@RFQCommentsPost')->name('my.rfq.comments.post');
	Route::post('view-my-rfq-comments-attachment','front\MyRFQController@ViewRFQCommentAttachments')->name('view.my.rfq.comments.attachment');
	Route::get('download-my-rfq-comments-attachment/{attach_id}','front\MyRFQController@DownloadRFQCommentAttachments');
	Route::resource('my-rfq','front\MyRFQController');

	//Direct RFQ
	Route::post('proceed-rfq','front\MyRFQController@proceedRFQ')->name('proceed.rfq');	
	Route::get('proceed-rfq/{cart_id}','front\MyRFQController@proceedRFQGet')->name('proceed.rfq.get');

	//My Order
	Route::resource('my-orders','front\MyOrdersController');
});

//Home Page
Route::get('home','front\HomePageController@index');
Route::get('','front\HomePageController@index')->name('home.index');
//Home page Search Box
Route::post('search',['as'=>'seach.text','uses'=>'front\HomePageController@search']);
//Category
Route::get('{slug}/{category_id}','front\ShopController@category');
//Product
Route::get('{category_slug}/{product_slug}/{product_id}','front\ShopController@product');
//About Us
Route::get('about-us','front\AboutController@index');
