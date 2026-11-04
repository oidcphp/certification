<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MilesChou\Psr\Http\Client\HttpClientInterface;
use OpenIDConnect\Http\Request\UserInfoRequestBuilder;

/**
 * Show the token response, the claims of ID Token and the UserInfo response for debugging
 */
class Callback extends Controller
{
    use CertificationTrait;

    public function __invoke(Request $request, HttpClientInterface $httpClient): JsonResponse
    {
        $session = $request->session();
        $config = $this->restoreConfig($session);

        $token = $this->handleAuthorizationCallback($session, $request->query(), '/callback');

        $userInfo = (new UserInfoRequestBuilder($config, $httpClient))
            ->build($token->accessToken())
            ->send();

        return response()->json([
            'token_set' => $token,
            'id_token_claims' => $token->idToken() ? $token->idTokenClaims()->all() : null,
            'user_info' => json_decode((string)$userInfo->getBody(), true),
        ]);
    }
}
