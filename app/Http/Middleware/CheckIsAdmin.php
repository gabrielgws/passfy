<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
