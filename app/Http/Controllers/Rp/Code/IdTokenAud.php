<?php

namespace App\Http\Controllers\Rp\Code;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\Request;

/**
 * @see RpTest::testIdTokenAud()
 */
class IdTokenAud
{
    use CertificationTrait;

    public function __invoke(Request $request)
    {
        $url = $this->createAuthorizationUrl('code', 'rp-id_token-aud');
    }
}
