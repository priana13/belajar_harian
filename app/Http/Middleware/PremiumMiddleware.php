<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PremiumMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()?->canAccessPremium()) {
            return redirect()->route('premium.index')->with('error', 'Fitur ini tersedia untuk Member Premium dengan langganan aktif.');
        }

        return $next($request);
    }
}
