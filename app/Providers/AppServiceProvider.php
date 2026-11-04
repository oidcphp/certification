<?php

namespace App\Providers;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use MilesChou\Psr\Http\Client\HttpClientManager;
use OpenIDConnect\Metadata\ClientMetadata;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HttpClientInterface::class, function () {
            return new HttpClientManager(new GuzzleClient());
        });

        $this->app->bind(ClientMetadata::class, function () {
            return new ClientMetadata([
                'contacts' => config('certification.contacts'),
                'redirect_uris' => array_map('route', [
                    'callback',
                    'callback.id-token',
                    'callback.token-set',
                    'callback.user-info',
                ]),
            ]);
        });
    }
}
