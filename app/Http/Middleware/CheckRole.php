<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check specific role
        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($role === 'operator' && !$user->isOperator()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($role === 'user' && !$user->isUser()) {
            abort(403, 'Unauthorized action.');
        }

        // Check staff role (admin or operator)
        if ($role === 'staff' && !$user->isStaff()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
} 