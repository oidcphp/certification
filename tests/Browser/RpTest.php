<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * @link https://rp.certification.openid.net:8080/list?profile=C
 */
class RpTest extends DuskTestCase
{
    /**
     * Make an authentication request using the Authorization Code Flow.
     *
     * An authentication response containing an authorization code.
     *
     * @see \App\Http\Controllers\Rp\Code\ResponseTypeCode
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
     * @see \App\Http\Controllers\Rp\Code\ScopeUserinfoClaims
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
     * @see \App\Http\Controllers\Rp\Code\NonceInvalid
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-nonce-invalid.txt
     *
     * @link https://openid.net/specs/openid-connect-core-1_0.html#NonceNotes
     */
    public function testNonceInvalid(): void
    {
        $this->markTestIncomplete('oidc/core Client::handleCallback() does not verify the nonce claim yet');

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
     * @see \App\Http\Controllers\Rp\Code\TokenEndpointClientSecretBasic
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
     * @see \App\Http\Controllers\Rp\Code\IdTokenKidAbsentSingleJwks
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-kid-absent-single-jwks.txt
     * @link https://openid.net/specs/openid-connect-core-1_0.html#IDTokenValidation
     */
    public function testIdTokenKidAbsentSingleJwks(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-kid-absent-single-jwks
            $this->assertIdTokenAccepted($browser->visit('/rp/code/rp-id_token-kid-absent-single-jwks'));
        });
    }

    /**
     * Request an ID token and verify its 'iat' value.
     *
     * Identify the missing 'iat' value and reject the ID Token after doing ID Token validation.
     *
     * @see \App\Http\Controllers\Rp\Code\IdTokenIat
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-iat.txt
     */
    public function testIdTokenIat(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-iat
            $browser->visit('/rp/code/rp-id_token-iat')
                ->assertSee('Relying party info is invalid')
                ->assertSee('The following claims are mandatory: iat');
        });
    }

    /**
     * Request an ID token and compare its aud value to the Relying Party's 'client_id'.
     *
     * Identify that the 'aud' value is missing or doesn't match the 'client_id' and reject the ID Token after doing ID Token validation.
     *
     * @see \App\Http\Controllers\Rp\Code\IdTokenAud
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-aud.txt
     */
    public function testIdTokenAud(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-aud
            $browser->visit('/rp/code/rp-id_token-aud')
                ->assertSee('Relying party info is invalid')
                ->assertSee('Bad audience');
        });
    }

    /**
     * Use Code Flow and retrieve an unsigned ID Token. This test is only applicable when response_type='code'
     *
     * Accept the ID Token after doing ID Token validation.
     *
     * @see \App\Http\Controllers\Rp\Code\IdTokenSigNone
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-sig-none.txt
     */
    public function testIdTokenSigNone(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-sig-none
            $browser->visit('/rp/code/rp-id_token-sig-none');

            $this->markTestIncomplete('oidc/core does not accept unsigned ID Token (alg=none) yet');
        });
    }

    /**
     * Request an ID token and compare its 'iss' value with the Issuer Identifier of the OpenID Provider.
     *
     * The client must identify that the 'iss' value is incorrect and reject the ID Token after doing ID Token validation.
     *
     * @see \App\Http\Controllers\Rp\Code\IdTokenIssuerMismatch
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-issuer-mismatch.txt
     * @link https://openid.net/specs/openid-connect-core-1_0.html#IDTokenValidation
     */
    public function testIdTokenIssuerMismatch(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-issuer-mismatch
            $browser->visit('/rp/code/rp-id_token-issuer-mismatch')
                ->assertSee('Relying party info is invalid')
                ->assertSee('Unknown issuer');
        });
    }

    /**
     * Request an ID token without the 'sub' claim.
     *
     * The client must identify the missing 'sub' claim and must reject the ID Token.
     *
     * @see \App\Http\Controllers\Rp\Code\IdTokenSub
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-sub.txt
     * @link https://openid.net/specs/openid-connect-core-1_0.html#IDToken
     */
    public function testIdTokenSub(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-sub
            $browser->visit('/rp/code/rp-id_token-sub')
                ->assertSee('Relying party info is invalid')
                ->assertSee('The following claims are mandatory: sub');
        });
    }

    /**
     * Request an ID token without 'kid' in the JOSE header, while the Issuer publishes multiple keys.
     *
     * Identify that the 'kid' value is missing from the JOSE header and that the Issuer publishes multiple keys in its JWK Set document (referenced by 'jwks_uri'). The RP can do one of two things; reject the ID Token since it can not by using the kid determined which key to use to verify the signature. Or it can just test all possible keys and hit upon one that works, which it will in this case.
     *
     * oidc/core tries every key of the set (web-token JWSVerifier), so the ID Token is accepted.
     *
     * @see \App\Http\Controllers\Rp\Code\IdTokenKidAbsentMultipleJwks
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-kid-absent-multiple-jwks.txt
     * @link https://openid.net/specs/openid-connect-core-1_0.html#Signing
     */
    public function testIdTokenKidAbsentMultipleJwks(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-kid-absent-multiple-jwks
            $this->assertIdTokenAccepted($browser->visit('/rp/code/rp-id_token-kid-absent-multiple-jwks'));
        });
    }

    /**
     * Request an ID token signed using RS256.
     *
     * The client is expected to accept an id_token signed using RS256.
     *
     * @see \App\Http\Controllers\Rp\Code\IdTokenSigRs256
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-sig-rs256.txt
     * @link https://openid.net/specs/openid-connect-core-1_0.html#IDTokenValidation
     */
    public function testIdTokenSigRs256(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-sig-rs256
            $this->assertIdTokenAccepted($browser->visit('/rp/code/rp-id_token-sig-rs256'));
        });
    }

    /**
     * Request an ID token with an invalid RS256 signature.
     *
     * The client must identify the invalid signature and reject the ID Token after doing ID Token validation.
     *
     * The specification lets the client skip this validation when the ID Token comes from the token endpoint, but oidc/core always verifies the signature.
     *
     * @see \App\Http\Controllers\Rp\Code\IdTokenBadSigRs256
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-id_token-bad-sig-rs256.txt
     * @link https://openid.net/specs/openid-connect-core-1_0.html#IDTokenValidation
     */
    public function testIdTokenBadSigRs256(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/code/rp-id_token-bad-sig-rs256
            $browser->visit('/rp/code/rp-id_token-bad-sig-rs256')
                ->assertSee('Receive an invalid ID token')
                ->assertSee('Unable to load and verify the token');
        });
    }

    /**
     * Make a UserInfo Request and verify the 'sub' value of the UserInfo Response.
     *
     * The client is expected to verify the 'sub' value of the UserInfo Response by comparing it with the ID Token's 'sub' value. The client must identify the invalid 'sub' value and reject the UserInfo Response.
     *
     * @see \App\Http\Controllers\Rp\Code\UserinfoBadSubClaim
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.code/rp-userinfo-bad-sub-claim.txt
     * @link https://openid.net/specs/openid-connect-core-1_0.html#UserInfoResponse
     * @link https://github.com/oidcphp/core/issues/35
     */
    public function testUserinfoBadSubClaim(): void
    {
        $this->markTestIncomplete('oidc/core does not verify the sub claim of the UserInfo Response yet (oidcphp/core#35)');
    }

    /**
     * Assert that the ID Token was accepted, the callback shows its claims.
     *
     * @see \App\Http\Controllers\CallbackIdToken
     */
    private function assertIdTokenAccepted(Browser $browser): void
    {
        $browser->assertSee('iss')
            ->assertSee('sub')
            ->assertSee('aud');
    }
}
