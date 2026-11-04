<?php

namespace App\Http\Controllers\Rp\Code;

use App\Certification\RelyingParty;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testScopeUserinfoClaims()
 */
class ScopeUserinfoClaims
{
    public function __invoke(RelyingParty $rp): ResponseInterface
    {
        return $rp->redirect('code', 'rp-scope-userinfo-claims', 'callback.user-info', [
            'scope' => 'openid profile email address phone',
        ]);
    }
}
