<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\Request;
use OpenIDConnect\Metadata\ClientMetadata;

/**
 * @see RpTest::testScopeUserinfoClaims()
 */
class ScopeUserinfoClaims
{
    use CertificationTrait;

    public function __invoke(Request $request, ClientMetadata $clientMetadata)
    {
        $client = $this->createOpenIDConnectClient($clientMetadata, 'code', 'rp-scope-userinfo-claims');

        return $client->createAuthorizeRedirectResponse([
            'redirect_uri' => 'http://localhost:8000/callback/user-info',
            'response_mode' => 'query',
            'response_type' => 'code',
            'scope' => 'openid profile email address phone',
        ]);
    }
}
