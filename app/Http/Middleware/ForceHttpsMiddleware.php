<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForceHttpsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $currentUrl = url()->current();
        $domain = parse_url($currentUrl, PHP_URL_HOST);

        if ($domain !== 'localhost') {
            URL::forceScheme('https');
        }

        return $next($request);
    }
}
