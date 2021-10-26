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
Route::get('package/getById', [App\Http\Controllers\Api\PackageApiController::class, 'getById']);
Route::post('/package/create', [App\Http\Controllers\Api\PackageApiController::class, 'create']);
Route::post('/package/update', [App\Http\Controllers\Api\PackageApiController::class, 'update']);


// Api tracking route
Route::get('/track', [App\Http\Controllers\Api\TrackingApiController::class, 'index']);


//Api Auth Route
Route::get('/apiUser/all', [App\Http\Controllers\Api\ApiAuthApiController::class, 'index']);
Route::get('/apiUser/getByName', [App\Http\Controllers\Api\ApiAuthApiController::class, 'getByName']);
Route::post('/apiUser/create', [App\Http\Controllers\Api\ApiAuthApiController::class, 'create']);
