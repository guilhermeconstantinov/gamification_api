<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AppToken
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken() == config('env.APP_TOKEN')) {
            return $next($request);
        }

        return response()->json(['message' => 'Acesso negado, por favor verifique seu token de acesso'], 401);
    }
}
