<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\AuthController;
use \App\Http\Controllers\Admin\CategoryController;
use \App\Http\Controllers\Admin\RolePermissionController;
use \App\Http\Controllers\Admin\SettingController;
use \App\Http\Controllers\Admin\BrandController;

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
    #brands
    Route::group(['prefix' => 'brands'],function(){
        #get all categories
        Route::get('/',[BrandController::class,'index']);
        #store category
        Route::post('/',[BrandController::class,'store']);
        #show category details
        Route::get('/{id}',[BrandController::class,'show']);
        #update category route
        Route::post('/{id}/update',[BrandController::class,'update']);
        #delete category
        Route::delete('/{id}/delete',[BrandController::class,'destroy']);
        #change status
        Route::post('/{id}/change-status',[BrandController::class,'changeStatus']);
    });
    //////////////////////////// Role And Permissions //////////////////////////////////////
    Route::group(['prefix' => 'roles'],function(){
        #get all roles
        Route::get('/',[RolePermissionController::class,'index']);
        #add role
        Route::post('/',[RolePermissionController::class,'store']);
        #show role
        Route::get('/{id}',[RolePermissionController::class,'show']);
        #update role
        Route::post('/{id}/update',[RolePermissionController::class,'update']);
        #delete role
        Route::delete('/{id}/destroy',[RolePermissionController::class,'destroy']);
        #get permissions
    });
    Route::get('get-permissions',[RolePermissionController::class,'getPermissions']);
    #settings
    Route::group(['prefix' => 'settings'],function(){
        #setting route api
        Route::get('/',[SettingController::class,'index']);
        #update
        Route::post('update',[SettingController::class,'update']);
    });
});
