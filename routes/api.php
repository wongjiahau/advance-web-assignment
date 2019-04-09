<?php

use Illuminate\Http\Request;

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


Route::middleware('api')->namespace('Auth')->prefix('auth')->group(function() {
    Route::post('login'    , 'AuthController@login');
    Route::post('logout'   , 'AuthController@logout');
    Route::post('refresh'  , 'AuthController@refresh');
    Route::post('register' , 'AuthController@register');
});


Route::middleware(['jwt.auth', 'can:manage-users'])->group(function() {
    Route::apiResource('/users', 'UserController');
});

Route::middleware(['jwt.auth', 'can:manage-profiles'])->group(function() {
    Route::apiResource('/profiles', 'ProfileController')->except([
        'index',
        'store',
        'destroy'
    ]);
});


Route::middleware(['jwt.auth', 'can:manage-groups'])->group(function() {
    Route::resource('groups'         , 'GroupController');
    Route::post    ('group_user'     , 'GroupController@add');
    Route::put     ('group_user'     , 'GroupController@promote');
    Route::delete  ('group_user/{id}', 'GroupController@exit');
    Route::patch   ('group_user'     , 'GroupController@kick');
});

Route::middleware(['jwt.auth', 'can:manage-messages'])->group(function() {
    Route::post  ('messages'     , 'MessageController@store');
    Route::get   ('messages/{id}', 'MessageController@retrieve');
    Route::put   ('messages/{id}', 'MessageController@update');
    Route::delete('messages/{id}', 'MessageController@destroy');
});
