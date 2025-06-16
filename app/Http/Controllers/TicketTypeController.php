<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'min_per_order' => 'required|integer|min:1',
            'max_per_order' => 'required|integer|min:1|gte:min_per_order',
            'sales_start_date' => 'nullable|date',
            'sales_end_date' => 'nullable|date|after_or_equal:sales_start_date',
        ]);

        $event->ticketTypes()->create($validated);

        return back()->with('success', 'Tipo de ingresso criado com sucesso!');
    }

    public function update(Request $request, Event $event, TicketType $ticketType)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:' . $ticketType->sold,
            'min_per_order' => 'required|integer|min:1',
            'max_per_order' => 'required|integer|min:1|gte:min_per_order',
            'sales_start_date' => 'nullable|date',
            'sales_end_date' => 'nullable|date|after_or_equal:sales_start_date',
            'is_active' => 'required|boolean',
        ]);

        $ticketType->update($validated);

        return back()->with('success', 'Tipo de ingresso atualizado com sucesso!');
    }

    public function destroy(Event $event, TicketType $ticketType)
    {
        $this->authorize('update', $event);

        if ($ticketType->sold > 0) {
            return back()->with('error', 'Não é possível excluir um tipo de ingresso que já teve vendas.');
        }

        $ticketType->delete();

        return back()->with('success', 'Tipo de ingresso excluído com sucesso!');
    }
}
