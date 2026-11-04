<?php

namespace App\Http\Controllers;

use App\Certification\RelyingParty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Show the token response, the claims of ID Token and the UserInfo response for debugging
 */
class Callback
{
    public function __invoke(Request $request, RelyingParty $rp): JsonResponse
    {
        $token = $rp->handleCallback($request);

        return response()->json([
            'token_set' => $token,
            'id_token_claims' => $token->idToken() ? $token->idTokenClaims()->all() : null,
            'user_info' => $rp->userInfo($token),
        ]);
    }
}
