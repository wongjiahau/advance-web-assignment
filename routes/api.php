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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
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