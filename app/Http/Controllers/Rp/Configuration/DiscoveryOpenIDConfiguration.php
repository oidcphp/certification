<?php

namespace App\Http\Controllers\Rp\Configuration;

use Illuminate\Http\Request;
use OpenIDConnect\Issuer;
use OpenIDConnect\Metadata\ClientMetadata;

class DiscoveryOpenIDConfiguration
{
    public function __invoke(Request $request, ClientMetadata $clientMetadata)
    {
        $url = 'https://rp.certification.openid.net:8080/oidcphp-rp.configuration/rp-discovery-openid-configuration';

        $issuer = Issuer::create($url);
        $provider = $issuer->discover();

        return response()->json($provider->toArray()['discovery']);
    }
}
