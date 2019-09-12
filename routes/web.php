<?php

use Illuminate\Routing\Router;

Route::get('/', function () {
    return view('welcome');
});

/** @var Router $router */

$router->namespace('Rp')->prefix('rp')->group(function (Router $router) {
    $router->namespace('Code')->prefix('code')->group(function (Router $router) {
        $router->get('rp-response_type-code', 'ResponseTypeCode');
        $router->get('rp-scope-userinfo-claims', 'ScopeUserinfoClaims');
        $router->get('rp-nonce-invalid', 'NonceInvalid');
        $router->get('rp-token_endpoint-client_secret_basic', 'TokenEndpointClientSecretBasic');
    });
    $router->namespace('Configuration')->prefix('configuration')->group(function (Router $router) {
        $router->get('rp-discovery-openid-configuration	', 'DiscoveryOpenIDConfiguration');
    });
});

$router->get('/callback', 'Callback');
$router->get('/callback/token-set', 'CallbackTokenSet');
$router->get('/callback/id-token', 'CallbackIdToken');
$router->get('/callback/user-info', 'CallbackUserInfo');
