<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Http\Request\UserInfoRequestBuilder;

/**
 * Show the UserInfo response
 */
class CallbackUserInfo extends Controller
{
    use CertificationTrait;

    public function __invoke(Request $request, HttpClientInterface $httpClient): JsonResponse
    {
        $session = $request->session();
        $config = $this->restoreConfig($session);

        $token = $this->handleAuthorizationCallback($session, $request->query(), '/callback/user-info');

        $response = (new UserInfoRequestBuilder($config, $httpClient))
            ->build($token->accessToken())
            ->send();

        return response()->json(json_decode((string)$response->getBody(), true));
    }
}
