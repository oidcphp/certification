<?php

namespace App\Http\Controllers\Rp;

use Illuminate\Contracts\Session\Session;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Client;
use OpenIDConnect\Config;
use OpenIDConnect\Issuer;
use OpenIDConnect\Metadata\ClientMetadata;
use OpenIDConnect\Metadata\ProviderMetadata;
use OpenIDConnect\TokenSet;
use Psr\Http\Message\ResponseInterface;

trait CertificationTrait
{
    /**
     * Build the issuer URL of the given test on the certification RP test tool.
     */
    protected function createIssuerUrl(string $profile, string $testId): string
    {
        $baseUrl = config('certification.base_url');
        $rpId = config('certification.rp_id');

        return "{$baseUrl}/{$rpId}.{$profile}/{$testId}";
    }

    /**
     * Build the OpenID Provider Configuration URL, Issuer::discover() does not append the well-known path.
     *
     * @see https://openid.net/specs/openid-connect-discovery-1_0.html#ProviderConfigurationRequest
     */
    protected function createDiscoveryUrl(string $profile, string $testId): string
    {
        return rtrim($this->createIssuerUrl($profile, $testId), '/') . '/.well-known/openid-configuration';
    }

    /**
     * Discover the provider, register the client dynamically, and keep the state in the session.
     */
    protected function createOpenIDConnectClient(string $profile, string $testId): Client
    {
        $httpClient = app(HttpClientInterface::class);

        $issuer = new Issuer($httpClient);
        $provider = $issuer->discover($this->createDiscoveryUrl($profile, $testId));
        $registration = $issuer->register($provider, app(ClientMetadata::class));

        $client = new Client(new Config($provider, $registration), $httpClient);

        $client->initAuthorizationParameters();

        session()->put([
            'nonce' => $client->getNonce(),
            'state' => $client->getState(),
            'provider' => $provider->toArray(),
            'jwks' => $provider->jwkSet()->toArray(),
            'registration' => $registration->toArray(),
        ]);

        return $client;
    }

    /**
     * Discover, register, and redirect the user agent to the authorization endpoint.
     *
     * @param array<string, mixed> $parameters
     */
    protected function redirectToAuthorization(
        string $profile,
        string $testId,
        string $callbackPath,
        array $parameters = []
    ): ResponseInterface {
        $client = $this->createOpenIDConnectClient($profile, $testId);

        // Client only adds 'state' automatically, so 'nonce' must be sent explicitly
        return $client->createAuthorizeRedirectResponse(array_merge([
            'nonce' => $client->getNonce(),
            'redirect_uri' => url($callbackPath),
            'response_mode' => 'query',
            'response_type' => 'code',
        ], $parameters));
    }

    /**
     * Exchange the authorization code with the state which was stored by createOpenIDConnectClient().
     *
     * Note: Client::handleCallback() does not verify the 'nonce' claim of ID Token yet.
     *
     * @param array<string, mixed> $query
     */
    protected function handleAuthorizationCallback(Session $session, array $query, string $callbackPath): TokenSet
    {
        $client = new Client($this->restoreConfig($session), app(HttpClientInterface::class));

        try {
            return $client->handleCallback($query, [
                'state' => $session->get('state'),
                'redirect_uri' => url($callbackPath),
            ]);
        } finally {
            $session->flush();
        }
    }

    /**
     * Restore the config which was stored by createOpenIDConnectClient().
     */
    protected function restoreConfig(Session $session): Config
    {
        return new Config(
            new ProviderMetadata($session->get('provider', []), $session->get('jwks', [])),
            new ClientMetadata($session->get('registration', []))
        );
    }
}
