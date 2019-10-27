<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use OpenIDConnect\Client;
use OpenIDConnect\Issuer;
use OpenIDConnect\Metadata\ClientMetadata;
use Tests\Browser\RpTest;

/**
 * @see RpTest::testTokenEndpointClientSecretBasic()
 */
class TokenEndpointClientSecretBasic
{
    use CertificationTrait;

    public function __invoke(Request $request, ClientMetadata $clientMetadata)
    {
        $url = $this->createAuthorizationUrl('code', 'rp-token_endpoint-client_secret_basic');

        $issuer = Issuer::create($url);
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
            'redirect_uri' => 'http://localhost:8000/callback/token-set',
            'response_type' => 'code',
            'response_mode' => 'query',
            'scope' => 'openid',
            'state' => $state,
        ]);
    }
}
