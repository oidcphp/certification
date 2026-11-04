<?php

namespace App\Http\Controllers;

use App\Certification\RelyingParty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Show the token response
 */
class CallbackTokenSet
{
    public function __invoke(Request $request, RelyingParty $rp): JsonResponse
    {
        $token = $rp->handleCallback($request);

        return response()->json($token);
    }
}
