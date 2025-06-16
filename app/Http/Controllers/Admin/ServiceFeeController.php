<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class ServiceFeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->is_admin) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'service_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $event->update($validated);

        return back()->with('success', 'Taxa de serviço atualizada com sucesso!');
    }
}
