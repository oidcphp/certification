<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MilesChou\Psr\Http\Client\HttpClientManager;
use OpenIDConnect\Client;
use OpenIDConnect\Config;
use OpenIDConnect\Metadata\ClientMetadata;
use OpenIDConnect\Metadata\ProviderMetadata;

class CallbackIdToken extends Controller
{
    public function __invoke(Request $request)
    {
        $session = $request->session();

        $config = new Config(
            new ProviderMetadata(
                $session->get('provider'),
                $session->get('jwks')
            ),
            new ClientMetadata($session->get('registration'))
        );

        $client = new Client($config, new HttpClientManager(new \GuzzleHttp\Client()));

        $token = $client->handleCallback($request->query(), [
            'nonce' => $session->get('nonce'),
            'state' => $session->get('state'),
            'redirect_uri' => 'http://localhost:8000/callback/id-token',
        ]);

        $claims = $token->idTokenClaims()->all();

        $session->flush();

        return response()->json($claims);
    }
}
