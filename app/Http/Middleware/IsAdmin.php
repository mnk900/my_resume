<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin() || auth()->user()->isSuspended()) {
            abort(403, 'Unauthorized administrative access.');
        }

        \App\Services\SeoService::set([
            'title' => 'Admin Control Center | MyResume.cloud',
            'robots' => 'noindex, nofollow'
        ]);

        return $next($request);
    }
}
