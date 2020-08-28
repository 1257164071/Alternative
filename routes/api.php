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
    config()->set('auth.defaults.guard', 'admin');

    Route::post('authorizations', 'AuthorizationsController@store')->name('admin.authorizations.store');

    Route::middleware('jwt.role:admin', 'jwt.auth')->group(function() {
        config()->set('auth.defaults.guard', 'admin');
        Route::get('admin', 'AdminsController@me');
    });

});
