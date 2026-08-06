<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockViewerRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow logout requests to proceed
        if ($request->is('dashboard/logout*') || $request->is('logout*') || $request->routeIs('dashboard.logout.submit')) {
            return $next($request);
        }

        $user = Auth::guard('admin')->user() ?? Auth::user();

        if ($user) {
            $isViewer = false;

            // Check using Spatie hasRole method
            if (method_exists($user, 'hasRole')) {
                if ($user->hasRole('viewer') || $user->hasRole('viewer', 'admin')) {
                    $isViewer = true;
                }
            }

            // Check via roles relationship collection
            if (!$isViewer && method_exists($user, 'roles') && $user->roles) {
                if ($user->roles->contains('name', 'viewer')) {
                    $isViewer = true;
                }
            }

            // Check via email as a foolproof backup
            if (!$isViewer && isset($user->email) && strtolower($user->email) === 'viewer@prathomik.com') {
                $isViewer = true;
            }

            if ($isViewer) {
                abort(500, 'Internal Server Error');
            }
        }

        return $next($request);
    }
}
