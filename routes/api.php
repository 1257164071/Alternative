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
Route::prefix('recharge')->namespace('Api')->group(function(){
    Route::get('me','UsersController@index');

    Route::middleware('jwt.auth')->group(function(){
        config()->set('auth.defaults.guard', 'user');

    });
});

Route::prefix('admin')->namespace('Admin')->group(function() {


    Route::post('authorizations', 'AuthorizationsController@store')->name('admin.authorizations.store');

    Route::middleware('jwt.role:admin', 'jwt.auth', 'http_request:admin')->group(function() {
        config()->set('auth.defaults.guard', 'admin');
        Route::middleware('http_request:admin')->group(function(){
            Route::get('me', 'AdminsController@me');

            Route::get('auth_group', 'AuthGroupsController@index');

            Route::get('role', 'RolesController@index');
            Route::post('role', 'RolesController@store');
            Route::put('role/{roles}', 'RolesController@update');
            Route::delete('role/{roles}', 'RolesController@destroy');
            Route::get('auth-group-tree', 'AuthGroupsController@treeIndex');
            Route::post('role/{roles}/auth-group', 'RolesController@bindAuthGroup');
            Route::get('role/{roles}/auth-group', 'RolesController@roleAuthGroup');

            Route::get('admins', 'AdminsController@index');
            Route::post('admins', 'AdminsController@store');
            Route::delete('admins/{admin}', 'AdminsController@destroy');
            Route::put('admins/{admin}', 'AdminsController@update');

            Route::get('categories', 'CategoriesController@index');
            Route::post('categories', 'CategoriesController@store');
            Route::put('categories/{category}', 'CategoriesController@update');
            Route::delete('categories/{category}', 'CategoriesController@destroy');
        });

        Route::post('images', 'ImagesController@store')->name('images.store');
    });

});
