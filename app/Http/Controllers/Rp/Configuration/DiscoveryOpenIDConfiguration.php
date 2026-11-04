<?php

namespace App\Http\Controllers\Rp\Configuration;

use App\Certification\RelyingParty;
use Illuminate\Http\JsonResponse;

/**
 * @see \Tests\Browser\ConfigurationTest::testDiscoveryOpenIDConfiguration()
 */
class DiscoveryOpenIDConfiguration
{
    public function __invoke(RelyingParty $rp): JsonResponse
    {
        return response()->json($rp->discover('configuration', 'rp-discovery-openid-configuration')->toArray());
    }
}
