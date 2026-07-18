<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\ForgetPasswordController;
use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\FavoriteController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\OrderController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'auth'], function () {
   #register
   Route::post('register', [AuthController::class, 'register']);
   #login
   Route::post('login', [AuthController::class, 'login']);
    #forget password
    Route::post('forgot-password', [ForgetPasswordController::class,'forgetPassword'])->middleware('throttle:5,1'); // 5 requests per minute
    #reset password
    Route::post('/reset-password', [ForgetPasswordController::class,'resetPassword']);
});
    #homepage api
    Route::get('homepage',[HomePageController::class, 'index']);
    #get categories
    Route::get('categories',[CategoryController::class, 'index']);
    #get products by category
    Route::get('categories/{id}',[CategoryController::class,'show']);
    #products
    Route::group(['prefix' => 'products'], function () {
        #offers page
        Route::get('offers',[ProductController::class,'offers']);
        #product details by slug
        Route::get('{slug}',[ProductController::class,'show']);
        #get product variant price by sku
    });
    Route::prefix('favorites')->group(function () {

        Route::get('/', [FavoriteController::class, 'index']);

        Route::post('/', [FavoriteController::class, 'store'])->middleware('guest.token');

    });

    #cart route api
    Route::group(['prefix' => 'cart'],function(){
        #toggle product in cart
        Route::post('/', [CartController::class, 'store'])->middleware('guest.token');
        #get cart list
        Route::get('/', [CartController::class, 'index']);
        #delete product from cart
        Route::delete('/{item_id}/delete', [CartController::class, 'destroy']);
    });

    Route::group(['middleware' => 'auth:api','throttle:api'],function(){
        Route::group(['prefix' => 'auth'], function () {
            #logout api
            Route::post('logout',[AuthController::class, 'logout']);
            #get profile
            Route::get('profile',[AuthController::class, 'profile']);
            #update profile
            Route::post('profile/update',[AuthController::class, 'update']);
        });
        #orders
        Route::group(['prefix' => 'orders'],function(){
            #store order
            Route::post('/', [OrderController::class, 'store']);
            #get all orders
            Route::get('/', [OrderController::class, 'index']);
            #order details
            Route::get('/{id}',[OrderController::class, 'show']);
            #cancel order
            Route::post('/cancel', [OrderController::class, 'cancel']);
        });
    });
