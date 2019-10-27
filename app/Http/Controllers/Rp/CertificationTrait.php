<?php

namespace App\Http\Controllers\Rp;

trait CertificationTrait
{
    /**
     * @param string $profile
     * @param string $testId
     * @return string
     */
    protected function createAuthorizationUrl(string $profile, string $testId): string
    {
        $baseUrl = config('certification.base_url');
        $rpId = config('certification.rp_id');

        return "{$baseUrl}/{$rpId}.{$profile}/{$testId}";
    }
}
