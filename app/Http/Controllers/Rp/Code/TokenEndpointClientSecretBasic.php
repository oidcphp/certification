<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\Request;
use OpenIDConnect\Metadata\ClientMetadata;
use Tests\Browser\RpTest;

/**
 * @see RpTest::testTokenEndpointClientSecretBasic()
 */
class TokenEndpointClientSecretBasic
{
    use CertificationTrait;

    public function __invoke(Request $request, ClientMetadata $clientMetadata)
    {
        $client = $this->createOpenIDConnectClient($clientMetadata, 'code', 'rp-token_endpoint-client_secret_basic');

        return $client->createAuthorizeRedirectResponse([
            'redirect_uri' => 'http://localhost:8000/callback/token-set',
            'response_mode' => 'query',
            'response_type' => 'code',
        ]);
    }
}
