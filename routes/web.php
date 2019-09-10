<?php

use Illuminate\Routing\Router;

Route::get('/', function () {
    return view('welcome');
});

/** @var Router $router */

$router->namespace('Rp')->prefix('rp')->group(function (Router $router) {
    $router->namespace('Code')->prefix('code')->group(function (Router $router) {
        $router->get('rp-response_type-code', 'RpResponseTypeCode');
    });
});

$router->get('/callback', 'Callback');
