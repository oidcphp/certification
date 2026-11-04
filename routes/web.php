<?php

use App\Http\Controllers\Callback;
use App\Http\Controllers\CallbackIdToken;
use App\Http\Controllers\CallbackTokenSet;
use App\Http\Controllers\CallbackUserInfo;
use App\Http\Controllers\Rp\Code;
use App\Http\Controllers\Rp\Configuration;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('rp/code')->group(function () {
    Route::get('rp-response_type-code', Code\ResponseTypeCode::class);
    Route::get('rp-scope-userinfo-claims', Code\ScopeUserinfoClaims::class);
    Route::get('rp-nonce-invalid', Code\NonceInvalid::class);
    Route::get('rp-token_endpoint-client_secret_basic', Code\TokenEndpointClientSecretBasic::class);
    Route::get('rp-id_token-kid-absent-single-jwks', Code\IdTokenKidAbsentSingleJwks::class);
    Route::get('rp-id_token-iat', Code\IdTokenIat::class);
    Route::get('rp-id_token-aud', Code\IdTokenAud::class);
    Route::get('rp-id_token-sig-none', Code\IdTokenSigNone::class);
});

Route::prefix('rp/configuration')->group(function () {
    Route::get('rp-discovery-openid-configuration', Configuration\DiscoveryOpenIDConfiguration::class);
});

Route::get('/callback', Callback::class);
Route::get('/callback/token-set', CallbackTokenSet::class);
Route::get('/callback/id-token', CallbackIdToken::class);
Route::get('/callback/user-info', CallbackUserInfo::class);
