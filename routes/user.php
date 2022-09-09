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
Route::prefix('recharge')->namespace('Api')->group(function () {
    // 用户注册
    Route::post('users', 'UsersController@store')
        ->name('users.store');
    // 第三方登录
    Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
        ->where('social_type', 'wechat')
        ->name('socials.authorizations.store');

    // 刷新token
    Route::put('authorizations/current', 'AuthorizationsController@update')
        ->name('authorizations.update');
    // 删除token
    Route::delete('authorizations/current', 'AuthorizationsController@destroy')
        ->name('authorizations.destroy');
    Route::middleware('jwt.role:user', 'jwt.auth')->group(function () {
        config()->set('auth.defaults.guard', 'user');
        Route::get('me', 'UsersController@me');
    });

});
