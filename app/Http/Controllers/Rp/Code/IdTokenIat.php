<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\Request;
use OpenIDConnect\OAuth2\Metadata\ClientMetadata;

/**
 * @see RpTest::testIdTokenIat()
 */
class IdTokenIat
{
    use CertificationTrait;

    public function __invoke(Request $request, ClientMetadata $clientMetadata)
    {
        $client = $this->createOpenIDConnectClient($clientMetadata, 'code', 'rp-id_token-iat');

        return $client->createAuthorizeRedirectResponse([
            'redirect_uri' => 'http://localhost:8000/callback/id-token',
            'response_mode' => 'query',
            'response_type' => 'code',
        ]);
    }
}
