<?php

namespace App\Certification;

use Illuminate\Http\Request;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Client;
use OpenIDConnect\Config;
use OpenIDConnect\Http\Request\UserInfoRequestBuilder;
use OpenIDConnect\Issuer;
use OpenIDConnect\Metadata\ClientMetadata;
use OpenIDConnect\Metadata\ProviderMetadata;
use OpenIDConnect\TokenSet;
use Psr\Http\Message\ResponseInterface;

/**
 * Relying Party flow against the certification RP test tool
 */
class RelyingParty
{
    private const SESSION_KEY = 'oidc';

    private ?Config $config = null;

    public function __construct(private HttpClientInterface $httpClient, private ClientMetadata $clientMetadata)
    {
    }

    /**
     * Build the OpenID Provider Configuration URL, Issuer::discover() does not append the well-known path.
     *
     * @see https://openid.net/specs/openid-connect-discovery-1_0.html#ProviderConfigurationRequest
     */
    public function discoveryUrl(string $profile, string $testId): string
    {
        $baseUrl = config('certification.base_url');
        $rpId = config('certification.rp_id');

        return "{$baseUrl}/{$rpId}.{$profile}/{$testId}/.well-known/openid-configuration";
    }

    public function discover(string $profile, string $testId): ProviderMetadata
    {
        return (new Issuer($this->httpClient))->discover($this->discoveryUrl($profile, $testId));
    }

    /**
     * Discover, register dynamically, and redirect the user agent to the authorization endpoint.
     *
     * @param array<string, mixed> $parameters
     */
    public function redirect(
        string $profile,
        string $testId,
        string $callbackRoute,
        array $parameters = []
    ): ResponseInterface {
        $provider = $this->discover($profile, $testId);
        $registration = (new Issuer($this->httpClient))->register($provider, $this->clientMetadata);

        $client = new Client(new Config($provider, $registration), $this->httpClient);
        $client->initAuthorizationParameters();

        // The nonce is kept for verifying ID Token once Client::handleCallback() supports it
        session()->put(self::SESSION_KEY, [
            'nonce' => $client->getNonce(),
            'state' => $client->getState(),
            'provider' => $provider->toArray(),
            'jwks' => $provider->jwkSet()->toArray(),
            'registration' => $registration->toArray(),
        ]);

        // Client only adds 'state' automatically, so 'nonce' must be sent explicitly
        return $client->createAuthorizeRedirectResponse(array_merge([
            'nonce' => $client->getNonce(),
            'redirect_uri' => route($callbackRoute),
            'response_mode' => 'query',
        ], $parameters));
    }

    /**
     * Exchange the authorization code with the state which was stored by redirect().
     *
     * Note: Client::handleCallback() does not verify the 'nonce' claim of ID Token yet.
     */
    public function handleCallback(Request $request): TokenSet
    {
        $stored = $request->session()->pull(self::SESSION_KEY, []);

        $this->config = new Config(
            new ProviderMetadata($stored['provider'] ?? [], $stored['jwks'] ?? []),
            new ClientMetadata($stored['registration'] ?? [])
        );

        return (new Client($this->config, $this->httpClient))->handleCallback($request->query(), [
            'state' => $stored['state'] ?? null,
            'redirect_uri' => $request->url(),
        ]);
    }

    /**
     * Request the UserInfo endpoint of the provider which was restored by handleCallback().
     *
     * @return array<string, mixed>
     */
    public function userInfo(TokenSet $token): array
    {
        $response = (new UserInfoRequestBuilder($this->config, $this->httpClient))
            ->build($token->accessToken())
            ->send();

        return json_decode((string)$response->getBody(), true);
    }
}
