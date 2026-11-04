<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testIdTokenSigNone()
 */
class IdTokenSigNone
{
    use CertificationTrait;

    public function __invoke(): ResponseInterface
    {
        return $this->redirectToAuthorization('code', 'rp-id_token-sig-none', '/callback/id-token');
    }
}
