<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Frontend\CropController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\VideoController;
use App\Http\Controllers\Frontend\EnquiryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\UserAddressController;
use App\Http\Controllers\Frontend\UserProductController;
use App\Http\Controllers\Frontend\CropCategoryController;
use App\Http\Controllers\Frontend\ProductCategoryController;
use App\Http\Controllers\Frontend\GovernmentSchemeController;

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

Route::group(['middleware' => ['cors', 'json.response']], function () {

    // public routes
    Route::post('register', [AuthController::class, 'register']);

    Route::post('login', [AuthController::class, 'login']);

    Route::post('google-login', [AuthController::class, 'googleLogin']);

    //Private Media
    Route::get('media/{type}/{filename}', [MediaController::class, 'index'])->name('index');

    // Admin Routes
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['middleware' => ['auth:api', 'scope:admin']], function () {
            // our routes to be protected will go in here
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    // User Routes
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {

        Route::group(['middleware' => ['auth:api', 'scope:user']], function () {
            // our routes to be protected will go in here

            Route::post('update-profile', [UserController::class, 'update']);

            Route::post('change-password', [AuthController::class, 'changePassword']);

            Route::post('logout', [AuthController::class, 'logout']);

            Route::apiResource('news', NewsController::class);

            Route::apiResource('videos', VideoController::class);

            Route::apiResource('crop-categories', CropCategoryController::class);

            Route::apiResource('crops', CropController::class);

            Route::apiResource('government-schemes', GovernmentSchemeController::class);

            Route::apiResource('user-address', UserAddressController::class);

            Route::apiResource('enquiries', EnquiryController::class);

            Route::apiResource('product-categories', ProductCategoryController::class);

            Route::apiResource('products', ProductController::class);

            Route::apiResource('user-products', UserProductController::class);

            Route::apiResource('orders', OrderController::class);

        });
    });
});
