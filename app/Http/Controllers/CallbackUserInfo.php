<?php

namespace App\Http\Controllers;

use App\Certification\RelyingParty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Show the UserInfo response
 */
class CallbackUserInfo
{
    public function __invoke(Request $request, RelyingParty $rp): JsonResponse
    {
        $token = $rp->handleCallback($request);

        return response()->json($rp->userInfo($token));
    }
}
