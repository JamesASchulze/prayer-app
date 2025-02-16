<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOrganizationAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->organization_id) {
            abort(403, 'You must belong to an organization to access this resource.');
        }

        return $next($request);
    }
} 