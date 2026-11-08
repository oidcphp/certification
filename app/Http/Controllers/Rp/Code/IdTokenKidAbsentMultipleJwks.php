<?php

namespace App\Http\Controllers\Rp\Code;

use App\Certification\RelyingParty;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testIdTokenKidAbsentMultipleJwks()
 */
class IdTokenKidAbsentMultipleJwks
{
    public function __invoke(RelyingParty $rp): ResponseInterface
    {
        return $rp->redirect('code', 'rp-id_token-kid-absent-multiple-jwks', 'callback.id-token');
    }
}
