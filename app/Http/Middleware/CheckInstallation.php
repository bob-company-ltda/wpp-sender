<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Exclude the /install* path from this check
        if ($request->is('install*')) {
            return $next($request);
        }

        $installedFilePath = public_path('uploads/installed');
       
        if (!File::exists($installedFilePath)) {
            // If the file doesn't exist, return a message
            return response('Please install the script first', 403);
        }

        return $next($request);
    }
}
