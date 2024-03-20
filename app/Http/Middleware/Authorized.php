<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class Authorized
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestRoute = Route::currentRouteName();
        if (Auth::user()->can($requestRoute)){
            return $next($request);
        }else{
            abort(401);
        }
    }
}
