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

$router->namespace('App\Domains\Blog\User\Controllers')
    ->prefix('/users')->group(function ($router) {
        $router->get('/', 'UserController@index');
        $router->get('/{id}', 'UserController@getUserById');
        $router->post('/', 'UserController@create');
        $router->put('/{id}', 'UserController@updateUser');
        $router->delete('/{id}', 'UserController@deleteUser');
    });
