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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Api Package route
Route::get('/package/all', [App\Http\Controllers\Api\PackageApiController::class, 'index']);
Route::get('/package/getById', [App\Http\Controllers\Api\PackageApiController::class, 'getById']);
Route::post('/package/create', [App\Http\Controllers\Api\PackageApiController::class, 'create']);
Route::post('/package/update', [App\Http\Controllers\Api\PackageApiController::class, 'update']);


// Api tracking route
Route::get('/track', [App\Http\Controllers\Api\TrackingApiController::class, 'index']);
Route::post('/track/update', [App\Http\Controllers\Api\TrackingApiController::class, 'update']);


//Api Auth Route
Route::get('/apiUser/all', [App\Http\Controllers\Api\ApiAuthApiController::class, 'index']);
Route::get('/apiUser/getByName', [App\Http\Controllers\Api\ApiAuthApiController::class, 'getByName']);
Route::post('/apiUser/create', [App\Http\Controllers\Api\ApiAuthApiController::class, 'create']);


// locations route
Route::get('/location/all', [App\Http\Controllers\Api\LocationApiController::class, 'index']);
Route::get('/location/getById', [App\Http\Controllers\Api\LocationApiController::class, 'getById']);
Route::post('/location/create', [App\Http\Controllers\Api\LocationApiController::class, 'create']);
Route::post('/location/destroy', [App\Http\Controllers\Api\LocationApiController::class, 'destroy']);


// locations route
Route::get('/itemType/all', [App\Http\Controllers\Api\ItemTypeApiController::class, 'index']);
// Route::get('/location/getById', [App\Http\Controllers\Api\ItemTypeApiController::class, 'getById']);
Route::post('/itemType/create', [App\Http\Controllers\Api\ItemTypeApiController::class, 'create']);
Route::post('/itemType/destroy', [App\Http\Controllers\Api\ItemTypeApiController::class, 'destroy']);


// Api Agent login route
Route::get('/agent/login', [App\Http\Controllers\Api\AgentApiController::class, 'login']);

// Api Delivery route
Route::post('/track/update/delivery', [App\Http\Controllers\Api\DeliveryController::class, 'index']);
