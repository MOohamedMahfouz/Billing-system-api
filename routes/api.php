<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix'=>'v1','namespace'=>'\App\Http\Controllers\Api\V1','middleware'=>'auth:sanctum'],function(){
    Route::apiResource('customers',CustomerController::class);
    Route::apiResource('invoices',InvoiceController::class)->except('store');

    Route::post('invoices/bulk',['uses' => 'InvoiceController@bulkStore']);
});


Route::post('login',[RegisterController::class,'login']);
Route::post('register',[RegisterController::class,'register']);


Route::middleware('auth:sanctum')->post('/logout', 'App\Http\Controllers\Api\V1\RegisterController@logout');
