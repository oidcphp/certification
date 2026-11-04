<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testTokenEndpointClientSecretBasic()
 */
class TokenEndpointClientSecretBasic
{
    use CertificationTrait;

    public function __invoke(): ResponseInterface
    {
        return $this->redirectToAuthorization('code', 'rp-token_endpoint-client_secret_basic', '/callback/token-set');
    }
}
