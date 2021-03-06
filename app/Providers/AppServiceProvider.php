<?php

namespace App\Providers;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;
use OpenIDConnect\Core\Token\TokenFactory;
use OpenIDConnect\OAuth2\Metadata\ClientMetadata;
use OpenIDConnect\OAuth2\Token\TokenFactoryInterface;
use OpenIDConnect\Support\Http\GuzzlePsr18Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Zend\Diactoros\RequestFactory;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\StreamFactory;
use Zend\Diactoros\UriFactory;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(StreamFactoryInterface::class, StreamFactory::class);
        $this->app->singleton(ResponseFactoryInterface::class, ResponseFactory::class);
        $this->app->singleton(RequestFactoryInterface::class, RequestFactory::class);
        $this->app->singleton(UriFactoryInterface::class, UriFactory::class);
        $this->app->singleton(ClientInterface::class, function () {
            return new GuzzlePsr18Client(new HttpClient());
        });

        $this->app->singleton(ClientMetadata::class, function () {
            return new ClientMetadata([
                'contacts' => [
                    'jangconan@gmail.com',
                ],
                'redirect_uris' => [
                    'http://localhost:8000/callback',
                    'http://localhost:8000/callback/id-token',
                    'http://localhost:8000/callback/token-set',
                    'http://localhost:8000/callback/user-info',
                ],
            ]);
        });

        $this->app->singleton(TokenFactoryInterface::class, TokenFactory::class);
    }

    public function boot()
    {
        //
    }
}
