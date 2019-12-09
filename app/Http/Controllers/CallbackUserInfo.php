<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenIDConnect\Core\Builder\UserInfoRequestBuilder;
use OpenIDConnect\Core\Client;
use OpenIDConnect\OAuth2\Metadata\ClientInformation;
use OpenIDConnect\OAuth2\Metadata\ProviderMetadata;
use Psr\Http\Client\ClientInterface;

class CallbackUserInfo extends Controller
{
    public function __invoke(Request $request, ClientInterface $httpClient)
    {
        $session = $request->session();

        $providerMetadata = new ProviderMetadata(
            $session->get('provider'),
            $session->get('jwks')
        );

        $clientInformation = new ClientInformation($session->get('registration'));

        $client = new Client($providerMetadata, $clientInformation, app());

        $token = $client->handleOpenIDConnectCallback($request->query(), [
            'state' => $session->get('state'),
            'redirect_uri' => 'http://localhost:8000/callback/user-info',
        ]);

        $session->flush();

        $builder = new UserInfoRequestBuilder(app());
        $builder->setProviderMetadata($providerMetadata);
        $builder->setClientInformation($clientInformation);
        $request = $builder->build($token->accessToken());

        return response()->json(json_decode((string)$httpClient->sendRequest($request)->getBody(), true));
    }
}
