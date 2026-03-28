<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Not logged in at all
        if (!auth()->check()) {
            return redirect()->route('login.customer');
        }

        // Logged in but wrong role
        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized. You do not have access to this area.');
        }

        // Blocked by admin
        if (auth()->user()->status === 'blocked') {
            auth()->logout();
            return redirect()->route('login.customer')
                ->withErrors(['email' => 'Your account has been blocked. Contact support.']);
        }

        return $next($request);
    }
}