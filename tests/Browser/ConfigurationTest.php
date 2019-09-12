<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ConfigurationTest extends DuskTestCase
{
    /**
     * @see DiscoveryOpenIDConfiguration
     * @link https://rp.certification.openid.net:8080/log/oidcphp-rp.configuration/rp-discovery-openid-configuration.txt
     */
    public function testDiscoveryOpenIDConfiguration(): void
    {
        $this->browse(function (Browser $browser) {
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
