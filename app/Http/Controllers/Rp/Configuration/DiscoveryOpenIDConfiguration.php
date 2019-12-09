<?php

namespace App\Http\Controllers\Rp\Configuration;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\Request;
use OpenIDConnect\Core\Issuer;
use OpenIDConnect\OAuth2\Metadata\ClientMetadata;
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

        $issuer = Issuer::create(app());
        $provider = $issuer->discover($url);

        return response()->json($provider->toArray());
    }
}
