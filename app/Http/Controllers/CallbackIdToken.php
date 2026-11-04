<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Show the claims of the verified ID Token
 */
class CallbackIdToken extends Controller
{
    use CertificationTrait;

    public function __invoke(Request $request): JsonResponse
    {
        $token = $this->handleAuthorizationCallback($request->session(), $request->query(), '/callback/id-token');

        return response()->json($token->idTokenClaims()->all());
    }
}
