<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * @see https://rp.certification.openid.net:8080/list?profile=DYN
 */
class ConfigurationTest extends DuskTestCase
{
    /**
     * Retrieve and use the OpenID Provider Configuration Information.
     *
     * Read and use the JSON object returned from the OpenID Connect Provider.
     *
     * @see DiscoveryOpenIDConfiguration
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.configuration/rp-discovery-openid-configuration.txt
     * @link https://openid.net/specs/openid-connect-discovery-1_0.html#ProviderConfig
     */
    public function testDiscoveryOpenIDConfiguration(): void
    {
        $this->browse(function (Browser $browser) {
            // http://localhost:8000/rp/configuration/rp-discovery-openid-configuration
            $browser->visit('/rp/configuration/rp-discovery-openid-configuration')
                ->assertSee('authorization_endpoint')
                ->assertSee('id_token_signing_alg_values_supported')
                ->assertSee('issuer')
                ->assertSee('jwks_uri')
                ->assertSee('response_types_supported')
                ->assertSee('subject_types_supported')
                ->assertSee('token_endpoint');
        });
    }
}
