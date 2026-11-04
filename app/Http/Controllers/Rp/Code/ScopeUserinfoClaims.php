<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * @see \Tests\Browser\RpTest::testScopeUserinfoClaims()
 */
class ScopeUserinfoClaims
{
    use CertificationTrait;

    public function __invoke(): ResponseInterface
    {
        return $this->redirectToAuthorization('code', 'rp-scope-userinfo-claims', '/callback/user-info', [
            'scope' => 'openid profile email address phone',
        ]);
    }
}
