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
    Route::post('register', 'AuthController@register');
    Route::post('login'   , 'AuthController@login');
    Route::post('logout'  , 'AuthController@logout');
    Route::post('refresh' , 'AuthController@refresh');
    Route::post('me'      , 'AuthController@me');
});


Route::middleware(['jwt.auth', 'can:manage-users'])->group(function() {
    Route::apiResource('/users', 'UserController');
});

Route::middleware(['jwt.auth', 'can:manage-groups'])->group(function() {
    Route::resource('groups', 'GroupController');
    Route::post('group_user', 'GroupController@add');
});

Route::middleware(['jwt.auth', 'can:manage-messages'])->group(function() {
    Route::resource('messages', 'MessageController');
});

Route::resources([
    '/authors'      => 'AuthorController',
    '/publishers'   => 'PublisherController',
    '/books'        => 'BookController',
]);

Route::resource('/authors', 'AuthorController', ['except' => [
    'destroy'
]]);

Route::resource('/publishers', 'PublisherController', ['except' => [
    'destroy'
]]);

Route::resource('/books', 'BookController', ['except' => [
    'destroy'
]]);