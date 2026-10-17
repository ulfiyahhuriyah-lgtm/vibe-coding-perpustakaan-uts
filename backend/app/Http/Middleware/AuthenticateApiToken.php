<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Autentikasi sederhana: header "Authorization: Bearer <token>". Token disimpan sebagai hash SHA-256. */
class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $user = $token ? User::where('api_token', hash('sha256', $token))->first() : null;

        if (! $user) {
            return response()->json(['message' => 'Tidak terautentikasi. Silakan login terlebih dahulu.'], 401);
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
