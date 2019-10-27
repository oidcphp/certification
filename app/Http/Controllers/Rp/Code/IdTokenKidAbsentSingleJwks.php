<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\Request;
use OpenIDConnect\Metadata\ClientMetadata;

/**
 * @see RpTest::testIdTokenKidAbsentSingleJwks()
 */
class IdTokenKidAbsentSingleJwks
{
    use CertificationTrait;

    public function __invoke(Request $request, ClientMetadata $clientMetadata)
    {
        $client = $this->createOpenIDConnectClient($clientMetadata, 'code', 'rp-id_token-kid-absent-single-jwks');

        return $client->createAuthorizeRedirectResponse([
            'redirect_uri' => 'http://localhost:8000/callback/id-token',
            'response_mode' => 'query',
            'response_type' => 'code',
        ]);
    }
}
