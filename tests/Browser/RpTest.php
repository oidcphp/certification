<?php

namespace Tests\Browser;

use App\Http\Controllers\Rp\Code\NonceInvalid;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RpTest extends DuskTestCase
{
    /**
     * @see ResponseTypeCode
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-response_type-code.txt
     */
    public function testRpResponseTypeCode(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/rp/code/rp-response_type-code')
                ->waitForText('1b2fc9341a16ae4e30082965d537ae47c21a0f27fd43eab78330ed81751ae6db', 10)
                ->assertQueryStringHas('code');
        });
    }

    /**
     * @see ScopeUserinfoClaims
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-scope-userinfo-claims.txt
     */
    public function testRpScopeUserinfoClaims(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/rp/code/rp-scope-userinfo-claims')
                ->waitForText('1b2fc9341a16ae4e30082965d537ae47c21a0f27fd43eab78330ed81751ae6db', 10)
                ->assertSee('name')
                ->assertSee('email')
                ->assertSee('address')
                ->assertSee('phone_number');
        });
    }

    /**
     * @see NonceInvalid
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-nonce-invalid.txt
     */
    public function testRpNonceInvalid(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/rp/code/rp-nonce-invalid')
                ->assertSee('Receive an invalid ID token');
        });
    }
}
