<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenIDConnect\Metadata\ClientMetadata;
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
        $this->app->singleton(\GuzzleHttp\ClientInterface::class, \GuzzleHttp\Client::class);

        $this->app->singleton(ClientMetadata::class, function () {
            return new ClientMetadata([
                'contacts' => [
                    'jangconan@gmail.com',
                ],
                'redirect_uris' => [
                    'http://localhost:8080/callback',
                ],
            ]);
        });
    }

    public function boot()
    {
        //
    }
}
