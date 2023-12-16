<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryProductController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::get('response-error', function () {
    return response()->json(
        [
            'code' => 403,
            'success' => false,
            'status' => 'Not Authorized',
            // 'message' => 'Please send header Authorization'
        ]
    );
})->name("response-error");

Route::post('validate-token', function (Request $request) {
    $data = auth()->guard('api')->user();
    $test = auth()->guard('api')->check();

    if ($data && $test) {
        return response()->json(true);
    }
    return response()->json(false);
});

Route::middleware('auth:api')->group(function () {
    Route::resource('transactions', TransactionController::class);
    Route::resource('products', ProductController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('product-categories',  CategoryProductController::class);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
