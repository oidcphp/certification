<?php

namespace App\Http\Controllers\Rp\Code;

use App\Certification\RelyingParty;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testIdTokenIat()
 */
class IdTokenIat
{
    public function __invoke(RelyingParty $rp): ResponseInterface
    {
        return $rp->redirect('code', 'rp-id_token-iat', 'callback.id-token');
    }
}
