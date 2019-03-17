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

Route::resources([
    '/authors'      => 'AuthorController',
    '/publishers'   => 'PublisherController',
    '/books'        => 'BookController',
    '/users'        => 'UserController',
    '/groups'       => 'GroupController',
    '/messages'     => 'MessageController'
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