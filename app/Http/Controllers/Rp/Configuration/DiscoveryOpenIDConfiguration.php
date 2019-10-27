<?php

namespace App\Http\Controllers\Rp\Configuration;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\Request;
use OpenIDConnect\Issuer;
use OpenIDConnect\Metadata\ClientMetadata;
use Tests\Browser\ConfigurationTest;

/**
 * @see ConfigurationTest::testDiscoveryOpenIDConfiguration()
 */
class DiscoveryOpenIDConfiguration
{
    use CertificationTrait;

    public function __invoke(Request $request, ClientMetadata $clientMetadata)
    {
        $url = $this->createAuthorizationUrl('configuration', 'rp-discovery-openid-configuration');

        $issuer = Issuer::create($url);
        $provider = $issuer->discover();

        return response()->json($provider->toArray()['discovery']);
    }
}
