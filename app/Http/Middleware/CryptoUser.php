<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CryptoUser
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->is_banned) {
            abort(403, 'Your trading account is suspended');
        }

        return $next($request);
    }
}
