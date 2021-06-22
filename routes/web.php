<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return config('app.name');
});

$router->group(['prefix' => 'api/v1/'], function () use ($router) {
    // Auth
    $router->post('login', 'AuthController@login');
});

$router->group([
    'prefix'     => 'api/v1/',
    'middleware' => 'auth'
], function () use ($router) {
    // Auth
    $router->get('me', 'AuthController@me');
    $router->get('refresh', 'AuthController@refresh');
    $router->get('logout', 'AuthController@logout');

    // Movement Category
    $router->get('movements/categories', 'MovementCategoryController@index');

    // Movement
    $router->get('movements', 'MovementController@index');
    $router->post('movements', 'MovementController@store');
    $router->get('movements/{id}', 'MovementController@show');
    $router->delete('movements/{id}', 'MovementController@destroy');
});
