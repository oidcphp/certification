<?php

namespace App\Http\Controllers\Rp\Configuration;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\JsonResponse;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Issuer;

/**
 * @see \Tests\Browser\ConfigurationTest::testDiscoveryOpenIDConfiguration()
 */
class DiscoveryOpenIDConfiguration
{
    use CertificationTrait;

    public function __invoke(HttpClientInterface $httpClient): JsonResponse
    {
        $url = $this->createDiscoveryUrl('configuration', 'rp-discovery-openid-configuration');

        $provider = (new Issuer($httpClient))->discover($url);

        return response()->json($provider->toArray());
    }
}
