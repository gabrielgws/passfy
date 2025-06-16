<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketValidationController extends Controller
{
    public function scanner()
    {
        return Inertia::render('Tickets/Scanner');
    }

    public function validate(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string',
        ]);

        $ticket = Ticket::where('qr_code', $validated['qr_code'])
            ->with(['order.event', 'ticketType', 'order.user'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ingresso não encontrado.',
            ], 404);
        }

        // Verificar se o usuário pode validar este ingresso
        if ($ticket->order->event->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para validar este ingresso.',
            ], 403);
        }

        if ($ticket->status === 'used') {
            return response()->json([
                'success' => false,
                'message' => 'Este ingresso já foi utilizado.',
                'ticket' => $ticket,
            ], 400);
        }

        if ($ticket->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Este ingresso foi cancelado.',
            ], 400);
        }

        $ticket->validate(auth()->user());

        return response()->json([
            'success' => true,
            'message' => 'Ingresso validado com sucesso!',
            'ticket' => $ticket->fresh(['order.event', 'ticketType']),
        ]);
    }
}
