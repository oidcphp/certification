<?php

namespace App\Http\Controllers\Rp\Code;

use App\Certification\RelyingParty;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testTokenEndpointClientSecretBasic()
 */
class TokenEndpointClientSecretBasic
{
    public function __invoke(RelyingParty $rp): ResponseInterface
    {
        return $rp->redirect('code', 'rp-token_endpoint-client_secret_basic', 'callback.token-set');
    }
}
