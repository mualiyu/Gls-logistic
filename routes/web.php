<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

// 
// root
// 
Route::get('/', function () {
	return view('home.index');
});



// 
// Delivery
// 

// Delivery home
Route::get('/delivery', [\App\Http\Controllers\MainController::class, 'index'])->name('main_home');

// sign in route
Route::get('/delivery/signin', [\App\Http\Controllers\CustomerAuthController::class, 'show_signin'])->name('main_signin');
Route::post('/delivery/signin', [\App\Http\Controllers\CustomerAuthController::class, 'signin'])->name('main_signin_customer');
Route::get('/delivery/signup', [\App\Http\Controllers\CustomerAuthController::class, 'show_signup'])->name('main_signup');
Route::post('/delivery/signup', [\App\Http\Controllers\CustomerAuthController::class, 'create_customer'])->name('main_create_customer');
Route::post('/delivery/customer_logout', [\App\Http\Controllers\CustomerAuthController::class, 'customer_logout'])->name('main_customer_logout');

// packages route
Route::get('/delivery/packages', [\App\Http\Controllers\PackageController::class, 'index'])->name('main_show_packages')->middleware('customerAuth');
Route::get('/delivery/package/add', [\App\Http\Controllers\PackageController::class, 'show'])->name('main_show_add_package')->middleware('customerAuth');
Route::post('/delivery/package/add', [\App\Http\Controllers\PackageController::class, 'create'])->name('main_create_package')->middleware('customerAuth');
Route::post('/delivery/package/{id}/activate', [\App\Http\Controllers\PackageController::class, 'activate_package'])->name('main_activate_package')->middleware('customerAuth');
Route::get('/delivery/package/get_to_region', [\App\Http\Controllers\PackageController::class, 'get_to_region'])->name('main_get_to_region');
Route::get('/delivery/package/search', [\App\Http\Controllers\PackageController::class, 'search_package'])->name('main_search_package')->middleware('customerAuth');

// show Add item to package
Route::get('/delivery/package/{id}', [\App\Http\Controllers\PackageController::class, 'show_activate_package'])->name('main_show_activate_package')->middleware('customerAuth');
Route::post('/delivery/package/item/add', [\App\Http\Controllers\PackageController::class, 'add_item'])->name('main_add_item_to_package')->middleware('customerAuth');

// Tracking
Route::get('/delivery/track', [\App\Http\Controllers\TrackingController::class, 'index'])->name('main_show_track_index');
Route::post('/delivery/track', [\App\Http\Controllers\TrackingController::class, 'get_track'])->name('main_get_track_info');
Route::get('/delivery/tracking/{t_id}', [\App\Http\Controllers\TrackingController::class, 'get_track_get'])->name('main_get_track_info_get');

// Excel main
Route::get('/delivery/excel/shipment', [\App\Http\Controllers\PackageController::class, 'export_summary_in_excel'])->name('main_excel_shipments_report')->middleware('customerAuth');

// PDF ROUTE 
Route::get('/delivery/pdf/search/shipment', [\App\Http\Controllers\PdfController::class, 'main_shipments_search'])->name('main_pdf_shipments_search');
Route::get('/delivery/pdf/label/{t_id}/shipment', [\App\Http\Controllers\TrackingController::class, 'ship_label'])->name('ship_label');

// confirm delivery otp
Route::post('/delivery/confirm/delivery', [\App\Http\Controllers\DeliveryController::class, 'index'])->name('main_confirm_delivery_index');
Route::post('/delivery/verify/delivery', [\App\Http\Controllers\DeliveryController::class, 'verify_delivery'])->name('main_verify_delivery_otp');

Route::get('up/up', function () {
	return view('up');
});
Route::post('/up/up', [\App\Http\Controllers\Admin\AdminLocationController::class, 'import_locations'])->name('dis');

Route::get('test/email', function () {


	$data2 = [
		'subject' => 'test',
		'email' => "mualiyuoox@gmail.com",
		// 'c_email' => $p->customer->email,
		'content' => 'tes info',
		// 'content' => "Bonjour Mr / Mme " . $tracking->package->name . ", votre commande est maintenant disponible. Vous serez contacté par un agent de liaison GLS.  \nVotre numéro tracking est " . $tracking->package->tracking_id . "\nMerci de suivile tracking de votre colis sur " . url('/') . ". \nRestant à votre disposition.",
	];

	try {
		Mail::send('main.email.c_receipt', $data2, function ($message) use ($data2) {
			$message->from('no-reply@glscam.com', 'GLS');
			$message->sender('no-reply@glscam.com', 'GLS');
			$message->to($data2['email']);
			$message->subject($data2['subject']);
		});

		return "yes";
	} catch (\Throwable $th) {
		return "no";
		// return back()->with('success', 'Package Has been Activated, Receipt is Not sent to contact Email');
	}
});

// 
// Admin Routes
// 

Auth::routes();
// home
Route::get('admin', [App\Http\Controllers\HomeController::class, 'index'])->name('admin_home');
// package
Route::get('admin/packages', [App\Http\Controllers\Admin\AdminPackageController::class, 'index'])->name('admin_packages');
Route::get('admin/package/{id}', [App\Http\Controllers\Admin\AdminPackageController::class, 'show_info'])->name('admin_package_info');
Route::get('admin/packages/search', [App\Http\Controllers\Admin\AdminPackageController::class, 'search_package'])->name('admin_package_search');
Route::post('admin/package/{id}/activate', [\App\Http\Controllers\Admin\AdminPackageController::class, 'activate_package'])->name('admin_activate_package');

// Users
Route::get('admin/users', [App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('admin_users');
Route::post('admin/users/{id}/update', [App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('admin_update_user');
Route::post('admin/users/{id}/delete', [App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('admin_delete_user');

// customers
Route::get('admin/customers', [App\Http\Controllers\Admin\AdminCustomerController::class, 'index'])->name('admin_customers');
// tracking
Route::get('admin/track', [App\Http\Controllers\Admin\AdminTrackController::class, 'index'])->name('admin_trackings');
Route::post('admin/track', [App\Http\Controllers\Admin\AdminTrackController::class, 'track'])->name('admin_track_package');
Route::post('admin/track/confirm', [App\Http\Controllers\Admin\AdminTrackController::class, 'confirm_tracking'])->name('admin_confirm_track_package');
Route::post('admin/track/confirm/delivery', [App\Http\Controllers\Admin\AdminTrackController::class, 'confirm_delivery'])->name('admin_confirm_package_delivery');


// locations route
Route::get('admin/locations', [App\Http\Controllers\Admin\AdminLocationController::class, 'index'])->name('admin_locations');
Route::get('admin/locations/create', [App\Http\Controllers\Admin\AdminLocationController::class, 'create'])->name('admin_create_location');
Route::post('admin/locations/create', [App\Http\Controllers\Admin\AdminLocationController::class, 'store'])->name('admin_store_location');
Route::get('admin/locations/{id}', [App\Http\Controllers\Admin\AdminLocationController::class, 'show'])->name('admin_show_location');
Route::post('admin/locations/{id}', [App\Http\Controllers\Admin\AdminLocationController::class, 'update'])->name('admin_update_location');
Route::post('admin/locations/{id}/charge', [App\Http\Controllers\Admin\AdminLocationController::class, 'update_charge'])->name('admin_update_charge_location');
Route::post('admin/locations/{id}/delete', [App\Http\Controllers\Admin\AdminLocationController::class, 'destroy'])->name('admin_delete_location');



// merchandises route
Route::get('admin/merchandises', [App\Http\Controllers\Admin\AdminMerchandiseController::class, 'index'])->name('admin_merchandises');
Route::get('admin/merchandises/create', [App\Http\Controllers\Admin\AdminMerchandiseController::class, 'create'])->name('admin_create_merchandise');
Route::post('admin/merchandises/create', [App\Http\Controllers\Admin\AdminMerchandiseController::class, 'store'])->name('admin_store_merchandise');
Route::get('admin/merchandises/{id}', [App\Http\Controllers\Admin\AdminMerchandiseController::class, 'show'])->name('admin_show_merchandise');
Route::post('admin/merchandises/{id}', [App\Http\Controllers\Admin\AdminMerchandiseController::class, 'update'])->name('admin_update_merchandise');
Route::post('admin/merchandises/{id}/delete', [App\Http\Controllers\Admin\AdminMerchandiseController::class, 'destroy'])->name('admin_delete_merchandise');
