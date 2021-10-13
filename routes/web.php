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

Route::get('/', [\App\Http\Controllers\MainController::class, 'index'])->name('main_home');


Route::get('/signin', [\App\Http\Controllers\CustomerAuthController::class, 'show_signin'])->name('main_signin');
Route::post('/signin', [\App\Http\Controllers\CustomerAuthController::class, 'signin'])->name('main_signin_customer');
Route::get('/signup', [\App\Http\Controllers\CustomerAuthController::class, 'show_signup'])->name('main_signup');
Route::post('/signup', [\App\Http\Controllers\CustomerAuthController::class, 'create_customer'])->name('main_create_customer');
Route::post('/customer_logout', [\App\Http\Controllers\CustomerAuthController::class, 'customer_logout'])->name('main_customer_logout');


Route::get('/packages', [\App\Http\Controllers\PackageController::class, 'index'])->name('main_show_packages')->middleware('customerAuth');
Route::get('/package/add', [\App\Http\Controllers\PackageController::class, 'show'])->name('main_show_add_package');
Route::post('/package/add', [\App\Http\Controllers\PackageController::class, 'create'])->name('main_create_package');
Route::post('/package/{id}/activate', [\App\Http\Controllers\PackageController::class, 'activate_package'])->name('main_activate_package')->middleware('customerAuth');
Route::get('/package/get_to_region', [\App\Http\Controllers\PackageController::class, 'get_to_region'])->name('main_get_to_region');

// show Add item to package
Route::get('/package/{id}/item/add', [\App\Http\Controllers\PackageController::class, 'show_add_item'])->name('main_show_add_item')->middleware('customerAuth');
Route::post('/package/item/add', [\App\Http\Controllers\PackageController::class, 'add_item'])->name('main_add_item_to_package')->middleware('customerAuth');

// Tracking
Route::get('/track', [\App\Http\Controllers\TrackingController::class, 'index'])->name('main_show_track_index');
Route::post('/track', [\App\Http\Controllers\TrackingController::class, 'get_track'])->name('main_get_track_info');
