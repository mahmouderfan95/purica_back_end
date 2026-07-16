<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\AuthController;
use \App\Http\Controllers\Admin\CategoryController;
use \App\Http\Controllers\Admin\RolePermissionController;
use \App\Http\Controllers\Admin\SettingController;
use \App\Http\Controllers\Admin\BrandController;
use \App\Http\Controllers\Admin\SliderController;
use \App\Http\Controllers\Admin\AttributeController;
use \App\Http\Controllers\Admin\AttributeOptionController;
use \App\Http\Controllers\Admin\ProductController;
use \App\Http\Controllers\Admin\CountryController;
use \App\Http\Controllers\Admin\CityController;
use \App\Http\Controllers\Admin\RegionController;
use \App\Http\Controllers\Admin\ShippingCompanyController;
use \App\Http\Controllers\Admin\ShippingPriceController;

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
#get shipping companies
Route::get('get-shipping-companies',[ShippingCompanyController::class, 'getShippingCompanies']);
#get Cities
Route::get('get-cities', [CityController::class, 'getCities']);
#get areas by city_id
Route::get('get-regions/{city_id}',[RegionController::class, 'getRegions']);
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
    #sliders
    Route::group(['prefix' => 'sliders'],function(){
        #get all categories
        Route::get('/',[SliderController::class,'index']);
        #store category
        Route::post('/',[SliderController::class,'store']);
        #show category details
        Route::get('/{id}',[SliderController::class,'show']);
        #update category route
        Route::post('/{id}/update',[SliderController::class,'update']);
        #delete category
        Route::delete('/{id}/delete',[SliderController::class,'destroy']);
        #change status
        Route::post('/{id}/change-status',[SliderController::class,'changeStatus']);
    });
    #attributes
    Route::group(['prefix' => 'attributes'],function(){
        #get all categories
        Route::get('/',[AttributeController::class,'index']);
        #store category
        Route::post('/',[AttributeController::class,'store']);
        #show category details
        Route::get('/{id}',[AttributeController::class,'show']);
        #update category route
        Route::post('/{id}/update',[AttributeController::class,'update']);
        #delete category
        Route::delete('/{id}/delete',[AttributeController::class,'destroy']);
    });
    #attribute options
    Route::group(['prefix' => 'attribute-options'],function(){
        Route::get('/{id}',[AttributeOptionController::class,'index']);
        #store category
        Route::post('/',[AttributeOptionController::class,'store']);
        #show category details
        Route::get('/{id}/details',[AttributeOptionController::class,'show']);
        #update category route
        Route::post('/{id}/update',[AttributeOptionController::class,'update']);
        #delete category
        Route::delete('/{id}/delete',[AttributeOptionController::class,'destroy']);
    });
    #products
    Route::group(['prefix' => 'products'],function(){
        Route::get('/',[ProductController::class,'index']);
        #store category
        Route::post('/',[ProductController::class,'store']);
        #show category details
        Route::get('/{id}',[ProductController::class,'show']);
        #update category route
        Route::post('/{id}/update',[ProductController::class,'update']);
        #delete category
        Route::delete('/{id}/delete',[ProductController::class,'destroy']);
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
    #countries
    Route::group(['prefix' => 'countries'],function(){
        #get all countries
        Route::get('/',[CountryController::class,'index']);
        #add new country
        Route::post('/',[CountryController::class,'store']);
        #update
        Route::post('/{id}/update',[CountryController::class,'update']);
        #delete country
        Route::delete('/{id}/destroy',[CountryController::class,'destroy']);
    });
    #cities
    Route::group(['prefix' => 'cities'],function(){
        #get all cities
        Route::get('/',[CityController::class,'index']);
        #add new city
        Route::post('/',[CityController::class,'store']);
        #update
        Route::post('/{id}/update',[CityController::class,'update']);
        #delete city
        Route::delete('/{id}/destroy',[CityController::class,'destroy']);
    });
    #areas
    Route::group(['prefix' => 'regions'],function(){
        #get all cities
        Route::get('/',[RegionController::class,'index']);
        #add new city
        Route::post('/',[RegionController::class,'store']);
        #update
        Route::post('/{id}/update',[RegionController::class,'update']);
        #delete city
        Route::delete('/{id}/destroy',[RegionController::class,'destroy']);
    });
    #shipping companies
    Route::group(['prefix' => 'shipping-companies'],function(){
        #get all cities
        Route::get('/',[ShippingCompanycontroller::class,'index']);
        #add new city
        Route::post('/',[ShippingCompanycontroller::class,'store']);
        #update
        Route::post('/{id}/update',[ShippingCompanycontroller::class,'update']);
        #delete city
        Route::delete('/{id}/destroy',[ShippingCompanycontroller::class,'destroy']);
    });
    #shipping prices
    Route::group(['prefix' => 'shipping-prices'],function(){
        #get all cities
        Route::get('/',[ShippingPriceController::class,'index']);
        #add new city
        Route::post('/',[ShippingPriceController::class,'store']);
        #update
        Route::post('/{id}/update',[ShippingPriceController::class,'update']);
        #delete city
        Route::delete('/{id}/destroy',[ShippingPriceController::class,'destroy']);
    });
});
