<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


$router->group([
    'prefix' => 'api_logins',
    'namespace' => 'App\Http\Controllers'
], function() use($router) {

    $router->post('/customers/login', [
        'uses' => 'CustomersController@login'
    ])->withoutMiddleware([App\Http\Middleware\PassportAuthentication::class]);

});


$router->group([
    'prefix' => 'api_customers',
    'namespace' => 'App\Http\Controllers'
], function() use($router) {

    $router->get('/customers', [
        'uses' => 'CustomersController@index'
    ]);

    $router->post('/customers/create', [
        'uses' => 'CustomersController@store'
    ]);
});


$router->group([
    'prefix' => 'api_rooms',
    'namespace' => 'App\Http\Controllers'
], function() use($router) {
    
    $router->get('/rooms', [
        'uses' => 'RoomsController@index'
    ]);

    $router->post('/rooms/create', [
        'uses' => 'RoomsController@store'
    ]);

    $router->get('/rooms/{id}/show', [
        'uses' => 'RoomsController@show'
    ]);
});


$router->group([
    'prefix' => 'api_bookings',
    'namespace' => 'App\Http\Controllers'
], function() use($router) {

    $router->get('/bookings', [
        'uses' => 'BookingsController@index'
    ]);

    $router->post('/bookings/create', [
        'uses' => 'BookingsController@store'
    ]);
});


$router->group([
    'prefix' => 'api_payments',
    'namespace' => 'App\Http\Controllers'
], function() use($router) {

    $router->post('/payments/create', [
        'uses' => 'PaymentsController@store'
    ]);
});


// test routes
$router->prefix('api_tests')->withoutMiddleware([App\Http\Middleware\PassportAuthentication::class])->group(function () use ($router) {
    
    $router->get('/bookings/test', [
        'uses' => 'App\Http\Controllers\BookingsController@index'
    ]);

    $router->post('/bookings/test/create', [
        'uses' => 'App\Http\Controllers\BookingsController@store'
    ]);

    $router->post('/customers/test/login', [
        'uses' => 'App\Http\Controllers\CustomersController@login'
    ]);

    $router->post('/customers/test/create', [
        'uses' => 'App\Http\Controllers\CustomersController@store'
    ]);

    $router->get('/customers/test', [
        'uses' => 'App\Http\Controllers\CustomersController@index'
    ]);

    $router->post('/payments/test/create', [
        'uses' => 'App\Http\Controllers\PaymentsController@store'
    ]);

    $router->get('/rooms/test', [
        'uses' => 'App\Http\Controllers\RoomsController@index'
    ]);

    $router->post('/rooms/test/create', [
        'uses' => 'App\Http\Controllers\RoomsController@store'
    ]);

    $router->get('/rooms/{id}/test/show', [
        'uses' => 'App\Http\Controllers\RoomsController@show'
    ]);
});
