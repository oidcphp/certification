<?php

namespace App\Http\Controllers\Rp\Code;

use App\Certification\RelyingParty;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testUserinfoBadSubClaim()
 */
class UserinfoBadSubClaim
{
    public function __invoke(RelyingParty $rp): ResponseInterface
    {
        return $rp->redirect('code', 'rp-userinfo-bad-sub-claim', 'callback.user-info');
    }
}
