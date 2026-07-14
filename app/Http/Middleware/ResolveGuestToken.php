<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResolveGuestToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth('api')->check()) {

            $token = $request->header('X-Guest-Token');

            if (!$token) {
                $token = (string) Str::uuid();
            }

            $request->attributes->set('guest_token', $token);

            $response = $next($request);

            $response->headers->set('X-Guest-Token', $token);

            return $response;
        }

        return $next($request);
    }
}
