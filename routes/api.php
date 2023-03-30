<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");


Route::get("products/shop", [ProductController::class, "getAllWithPng"]);
Route::resource('products', ProductController::class);
Route::get("media/images/{filename}", [MediaController::class, "serveFile"]);
//
Route::post("/orders/generate-stripe-client", [OrderController::class, "generateClient"]);
Route::post("/orders", [OrderController::class, "paymentSuccess"]);
