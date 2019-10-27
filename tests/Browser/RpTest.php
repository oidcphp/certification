<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * @see https://rp.certification.openid.net:8080/list?profile=C
 */
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
    public function testResponseTypeCode(): void
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
    public function testScopeUserinfoClaims(): void
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
    public function testNonceInvalid(): void
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
    public function testTokenEndpointClientSecretBasic(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-token_endpoint-client_secret_basic
            $browser->visit('/rp/code/rp-token_endpoint-client_secret_basic')
                ->assertSee('id_token');
        });
    }

    /**
     * Request an ID token and verify its signature using a single matching key provided by the Issuer.
     *
     * Use the single matching key out of the Issuer's published set to verify the ID Tokens signature and accept the ID Token after doing ID Token validation.
     *
     * @see IdTokenKidAbsentSingleJwks
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-kid-absent-single-jwks.txt
     * @link https://openid.net/specs/openid-connect-core-1_0.html#IDTokenValidation
     */
    public function testIdTokenKidAbsentSingleJwks(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-kid-absent-single-jwks
            $browser->visit('/rp/code/rp-id_token-kid-absent-single-jwks')
                ->assertSee('iss')
                ->assertSee('sub')
                ->assertSee('aud');
        });
    }

    /**
     * Request an ID token and compare its aud value to the Relying Party's 'client_id'.
     *
     * Identify that the 'aud' value is missing or doesn't match the 'client_id' and reject the ID Token after doing ID Token validation.
     *
     * @see IdTokenIat
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-iat.txt
     */
    public function testIdTokenIat(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-iat
            $browser->visit('/rp/code/rp-id_token-iat')
                ->assertSee('Receive an invalid ID token');
        });
    }

    /**
     * Request an ID token and verify its 'iat' value.
     *
     * Identify the missing 'iat' value and reject the ID Token after doing ID Token validation.
     *
     * @see IdTokenAud
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-aud.txt
     */
    public function testIdTokenAud(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-aud
            $browser->visit('/rp/code/rp-id_token-aud')
                ->assertSee('Receive an invalid ID token');
        });
    }
}
