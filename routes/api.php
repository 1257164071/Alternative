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
        Route::get('admin', 'AdminsController@me');


        Route::get('auth_group', 'AuthGroupsController@index');

        Route::get('role', 'RolesController@index');
        Route::post('role', 'RolesController@store');

        Route::post('role/{role}/auth-group', 'RolesController@bindAuthGroup');
        Route::get('role/{role}/auth-group', 'RolesController@roleAuthGroup');
    });

});
