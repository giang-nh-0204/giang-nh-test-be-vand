<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class CheckAuthHeader
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            return response()->json(['message' => 'Authorization header not found'], 401);
        }

        try {
            return $next($request);
        }
        catch (AuthenticationException $e) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    }
}
