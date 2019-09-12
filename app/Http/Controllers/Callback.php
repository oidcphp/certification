<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenIDConnect\Client;
use OpenIDConnect\Metadata\ClientRegistration;
use OpenIDConnect\Metadata\ProviderMetadata;

class Callback extends Controller
{
    public function __invoke(Request $request)
    {
        $session = $request->session();

        $client = new Client(new ProviderMetadata(
            $session->get('provider.discovery'),
            $session->get('provider.jwks')
        ), new ClientRegistration($session->get('registration')), app());

        $token = $client->handleOpenIDConnectCallback($request->query(), [
            'nonce' => $session->get('nonce'),
            'state' => $session->get('state'),
            'redirect_uri' => 'http://localhost:8080/callback',
        ]);

        dump($token->jsonSerialize());

        dump($token->idTokenClaims([], [
            'nonce' => $session->get('nonce'),
        ])->all());

        dump($client->getUserInfo($token->accessToken()));

        $session->flush();
    }
}
