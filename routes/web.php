<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [\App\Http\Controllers\MainController::class, 'index'])->name('main_home');


Route::get('/signin', [\App\Http\Controllers\CustomerAuthController::class, 'show_signin'])->name('main_signin');
Route::post('/signin', [\App\Http\Controllers\CustomerAuthController::class, 'signin'])->name('main_signin_customer');
Route::get('/signup', [\App\Http\Controllers\CustomerAuthController::class, 'show_signup'])->name('main_signup');
Route::post('/signup', [\App\Http\Controllers\CustomerAuthController::class, 'create_customer'])->name('main_create_customer');
Route::post('/customer_logout', [\App\Http\Controllers\CustomerAuthController::class, 'customer_logout'])->name('main_customer_logout');


Route::get('/packages', [\App\Http\Controllers\PackageController::class, 'index'])->name('main_show_packages')->middleware('customerAuth');
Route::get('/package/add', [\App\Http\Controllers\PackageController::class, 'show'])->name('main_show_add_package')->middleware('customerAuth');
Route::post('/package/add', [\App\Http\Controllers\PackageController::class, 'create'])->name('main_create_package')->middleware('customerAuth');
Route::post('/package/{id}/activate', [\App\Http\Controllers\PackageController::class, 'activate_package'])->name('main_activate_package')->middleware('customerAuth');
Route::get('/package/get_to_region', [\App\Http\Controllers\PackageController::class, 'get_to_region'])->name('main_get_to_region');

// show Add item to package
Route::get('/package/{id}/item/add', [\App\Http\Controllers\PackageController::class, 'show_add_item'])->name('main_show_add_item')->middleware('customerAuth');
Route::post('/package/item/add', [\App\Http\Controllers\PackageController::class, 'add_item'])->name('main_add_item_to_package')->middleware('customerAuth');

// Tracking
Route::get('/track', [\App\Http\Controllers\TrackingController::class, 'index'])->name('main_show_track_index');
Route::post('/track', [\App\Http\Controllers\TrackingController::class, 'get_track'])->name('main_get_track_info');



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
// customers
Route::get('admin/customers', [App\Http\Controllers\Admin\AdminCustomerController::class, 'index'])->name('admin_customers');
// tracking
Route::get('admin/track', [App\Http\Controllers\Admin\AdminTrackController::class, 'index'])->name('admin_trackings');
Route::post('admin/track', [App\Http\Controllers\Admin\AdminTrackController::class, 'track'])->name('admin_track_package');
Route::post('admin/track/confirm', [App\Http\Controllers\Admin\AdminTrackController::class, 'confirm_tracking'])->name('admin_confirm_track_package');