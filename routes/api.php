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


Route::prefix('admin')->namespace('Admin')->group(function() {

    Route::post('authorizations', 'AuthorizationsController@store')->name('admin.authorizations.store');

    Route::middleware('jwt.role:admin', 'jwt.auth')->group(function() {
        config()->set('auth.defaults.guard', 'admin');
        Route::get('me', 'AdminsController@me');


        Route::get('auth_group', 'AuthGroupsController@index');

        Route::get('role', 'RolesController@index');
        Route::post('role', 'RolesController@store');
        Route::put('role/{role}', 'RolesController@update');
        Route::delete('role/{role}', 'RolesController@destroy');
        Route::get('auth_group_tree', 'AuthGroupsController@treeIndex');
        Route::post('role/{role}/auth-group', 'RolesController@bindAuthGroup');
        Route::get('role/{role}/auth-group', 'RolesController@roleAuthGroup');

        Route::get('admins', 'AdminsController@index');
        Route::post('admins', 'AdminsController@store');
        Route::delete('admins/{admin}', 'AdminsController@destroy');
        Route::put('admins/{admin}', 'AdminsController@update');
    });

});
