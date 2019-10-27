<?php

namespace Tests\Browser;

use App\Http\Controllers\Rp\Code\NonceInvalid;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RpTest extends DuskTestCase
{
    /**
     * Make an authentication request using the Authorization Code Flow.
     *
     * An authentication response containing an authorization code.
     *
     * @see ResponseTypeCode
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-response_type-code.txt
     */
    public function testRpResponseTypeCode(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-response_type-code
            $browser->visit('/rp/code/rp-response_type-code')
                ->waitForText('1b2fc9341a16ae4e30082965d537ae47c21a0f27fd43eab78330ed81751ae6db', 10)
                ->assertQueryStringHas('code');
        });
    }

    /**
     * Request claims using scope values.
     *
     * A UserInfo Response containing the requested claims. If no access token is issued (when using Implicit Flow with response_type='id_token') the ID Token contains the requested claims.
     *
     * @see ScopeUserinfoClaims
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-scope-userinfo-claims.txt
     * @link https://openid.net/specs/openid-connect-core-1_0.html#ScopeClaims
     */
    public function testRpScopeUserinfoClaims(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-scope-userinfo-claims
            $browser->visit('/rp/code/rp-scope-userinfo-claims')
                ->waitForText('1b2fc9341a16ae4e30082965d537ae47c21a0f27fd43eab78330ed81751ae6db', 10)
                ->assertSee('name')
                ->assertSee('email')
                ->assertSee('address')
                ->assertSee('phone_number');
        });
    }

    /**
     * Pass a 'nonce' value in the Authentication Request. Verify the 'nonce' value returned in the ID Token.
     *
     * Identify that the 'nonce' value in the ID Token is invalid and reject the ID Token.
     *
     * @see NonceInvalid
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-nonce-invalid.txt
     *
     * @link https://openid.net/specs/openid-connect-core-1_0.html#NonceNotes
     */
    public function testRpNonceInvalid(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-nonce-invalid
            $browser->visit('/rp/code/rp-nonce-invalid')
                ->assertSee('Receive an invalid ID token');
        });
    }

    /**
     * Use the 'client_secret_basic' method to authenticate at the Authorization Server when using the token endpoint.
     *
     * A Token Response, containing an ID token.
     *
     * @see TokenEndpointClientSecretBasic
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-token_endpoint-client_secret_basic.txt
     */
    public function testRpTokenEndpointClientSecretBasic(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-token_endpoint-client_secret_basic
            $browser->visit('/rp/code/rp-token_endpoint-client_secret_basic')
                ->assertSee('id_token');
        });
    }
}
