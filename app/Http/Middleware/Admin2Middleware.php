<?php

namespace App\Http\Middleware;

use App\Admin\Resources;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Admin2Middleware
{
    public function handle(Request $request, Closure $next)
    {
        Inertia::setRootView('admin2');
        Inertia::share('navigation', fn () => Resources::navigation());

        return $next($request);
    }
}
