<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS behind Render's reverse proxy
        $request->setTrustedProxies([$request->getClientIp()], Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);
        
        if (!$request->isSecure() && app()->environment('production')) {
            $request->setScheme('https');
        }

        return $next($request);
    }
}
