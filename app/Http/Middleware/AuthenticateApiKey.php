<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Support\ProblemResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->headers->get('Authorization', '');

        if (! str_starts_with($header, 'Bearer ')) {
            return ProblemResponse::unauthorized('Missing bearer token.');
        }

        $token = substr($header, strlen('Bearer '));

        $key = ApiKey::findByPlaintext($token);

        if ($key === null) {
            return ProblemResponse::unauthorized('Invalid or revoked API key.');
        }

        $key->touchLastUsed();

        $request->attributes->set('api_key', $key);
        $request->attributes->set('workspace', $key->workspace);

        return $next($request);
    }
}
