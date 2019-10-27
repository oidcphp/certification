<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use OpenIDConnect\Client;
use OpenIDConnect\Issuer;
use OpenIDConnect\Metadata\ClientMetadata;

/**
 * @see RpTest::testNonceInvalid()
 */
class NonceInvalid
{
    use CertificationTrait;

    public function __invoke(Request $request, ClientMetadata $clientMetadata)
    {
        $url = $this->createAuthorizationUrl('code', 'rp-nonce-invalid');

        $issuer = Issuer::create($url);
        $provider = $issuer->discover();
        $registration = $issuer->register($clientMetadata);

        $client = new Client($provider, $registration, app());

        $nonce = Str::random();
        $state = Str::random();

        $request->session()->put([
            'nonce' => $nonce,
            'state' => $state,
            'provider' => $provider->toArray(),
            'registration' => $registration->toArray(),
        ]);

        return $client->createAuthorizeRedirectResponse([
            'nonce' => $nonce,
            'redirect_uri' => 'http://localhost:8000/callback',
            'response_type' => 'code',
            'response_mode' => 'query',
            'scope' => 'openid',
            'state' => $state,
        ]);
    }
}
