<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCanCreateEvents
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->can_create_events) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para criar eventos.');
        }

        return $next($request);
    }
}
