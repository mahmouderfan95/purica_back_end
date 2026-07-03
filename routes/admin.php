<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\AuthController;
use \App\Http\Controllers\Admin\CategoryController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
#auth
Route::prefix('auth')->group(function () {
    #login request
    Route::post('login', [AuthController::class, 'login']);
});
Route::group(['middleware' => ['auth:adminApi']], function () {
    #logout
    Route::post('auth/logout', [AuthController::class, 'logout']);
    #categories
    Route::group(['prefix' => 'categories'],function(){
        #get all categories
        Route::get('/',[CategoryController::class,'index']);
        #store category
        Route::post('/',[CategoryController::class,'store']);
        #show category details
        Route::get('/{id}',[CategoryController::class,'show']);
        #update category route
        Route::post('/{id}/update',[CategoryController::class,'update']);
        #delete category
        Route::delete('/{id}/delete',[CategoryController::class,'destroy']);
        #change status
        Route::post('/{id}/change-status',[CategoryController::class,'changeStatus']);
    });
});
