<?php

namespace App\Http\Controllers\Rp\Code;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use OpenIDConnect\Client;
use OpenIDConnect\Issuer;
use OpenIDConnect\Metadata\ClientMetadata;

class ScopeUserinfoClaims
{
    public function __invoke(Request $request, ClientMetadata $clientMetadata)
    {
        $testUrl = 'https://rp.certification.openid.net:8080/oidcphp-rp.code/rp-scope-userinfo-claims';

        $issuer = Issuer::create($testUrl);
        $provider = $issuer->discover();
        $registration = $issuer->register($clientMetadata);

        $client = new Client($provider, $registration, app());

        $state = Str::random();

        $request->session()->put([
            'state' => $state,
            'provider' => $provider->toArray(),
            'registration' => $registration->toArray(),
        ]);

        return $client->createAuthorizeRedirectResponse([
            'redirect_uri' => 'http://localhost:8000/callback/user-info',
            'response_type' => 'code',
            'response_mode' => 'query',
            'scope' => 'openid profile email address phone',
            'state' => $state,
        ]);
    }
}
