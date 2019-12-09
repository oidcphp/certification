<?php

namespace App\Http\Controllers\Rp;

use Illuminate\Support\Facades\Session;
use OpenIDConnect\Core\Client;
use OpenIDConnect\Core\Issuer;
use OpenIDConnect\OAuth2\Metadata\ClientMetadata;

trait CertificationTrait
{
    /**
     * @param string $profile
     * @param string $testId
     * @return string
     */
    protected function createAuthorizationUrl($profile, $testId): string
    {
        $baseUrl = config('certification.base_url');
        $rpId = config('certification.rp_id');

        return "{$baseUrl}/{$rpId}.{$profile}/{$testId}";
    }

    /**
     * @param ClientMetadata $clientMetadata
     * @param string $profile
     * @param string $testId
     * @return Client
     */
    protected function createOpenIDConnectClient(ClientMetadata $clientMetadata, $profile, $testId): Client
    {
        $issuer = Issuer::create(app());
        $provider = $issuer->discover($this->createAuthorizationUrl($profile, $testId));
        $registration = $issuer->register($provider, $clientMetadata);

        $client = new Client($provider, $registration, app());

        $client->initAuthorizationParameters();

        Session::put([
            'nonce' => $client->getNonce(),
            'jwks' => $provider->jwkSet(),
            'state' => $client->getState(),
            'provider' => $provider->toArray(),
            'registration' => $registration->toArray(),
        ]);

        return $client;
    }
}
