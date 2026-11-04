<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testIdTokenKidAbsentSingleJwks()
 */
class IdTokenKidAbsentSingleJwks
{
    use CertificationTrait;

    public function __invoke(): ResponseInterface
    {
        return $this->redirectToAuthorization('code', 'rp-id_token-kid-absent-single-jwks', '/callback/id-token');
    }
}
