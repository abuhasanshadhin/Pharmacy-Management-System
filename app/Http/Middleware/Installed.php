<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class Installed
{

    public function handle(Request $request, Closure $next): Response
    {
        $path = storage_path('installed');
        if (File::exists($path)){
            return $next($request);
        }
        return redirect('install');
    }
}
