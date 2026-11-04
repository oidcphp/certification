<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testNonceInvalid()
 */
class NonceInvalid
{
    use CertificationTrait;

    public function __invoke(): ResponseInterface
    {
        return $this->redirectToAuthorization('code', 'rp-nonce-invalid', '/callback/id-token');
    }
}
