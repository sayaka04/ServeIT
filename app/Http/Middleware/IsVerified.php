<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->email_verified_at !== null) {
            return $next($request);
        }

        // Redirect to home or show a 403 error
        // return redirect()->route('home')->with('error', 'You do not have administrative privileges.');
        // OR: return abort(403, 'Unauthorized action. You do not have administrator privileges.');
        // return abort(403, 'Forbidden');
        return redirect()->route('verify-email');
    }
}
