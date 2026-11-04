<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Rp\CertificationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Show the token response
 */
class CallbackTokenSet extends Controller
{
    use CertificationTrait;

    public function __invoke(Request $request): JsonResponse
    {
        $token = $this->handleAuthorizationCallback($request->session(), $request->query(), '/callback/token-set');

        return response()->json($token);
    }
}
