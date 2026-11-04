<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testIdTokenIat()
 */
class IdTokenIat
{
    use CertificationTrait;

    public function __invoke(): ResponseInterface
    {
        return $this->redirectToAuthorization('code', 'rp-id_token-iat', '/callback/id-token');
    }
}
