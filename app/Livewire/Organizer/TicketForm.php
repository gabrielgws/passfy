<?php

namespace App\Livewire\Organizer;

use Livewire\Component;
use App\Models\Event;
use App\Models\Ticket;

class TicketForm extends Component
{
    public Event $event;
    public Ticket $ticket;
    public $isEditing = false;

    protected $rules = [
        'ticket.name' => 'required|string|max:255',
        'ticket.description' => 'nullable|string',
        'ticket.price' => 'required|numeric|min:0',
        'ticket.quantity_available' => 'required|integer|min:1',
        'ticket.sale_starts_at' => 'nullable|date',
        'ticket.sale_ends_at' => 'nullable|date|after:ticket.sale_starts_at',
    ];

    public function mount($eventId, $ticketId = null)
    {
        $this->event = Event::where('user_id', auth()->id())->findOrFail($eventId);

        if ($ticketId) {
            $this->ticket = Ticket::where('event_id', $this->event->id)->findOrFail($ticketId);
            $this->isEditing = true;
        } else {
            $this->ticket = new Ticket();
            $this->ticket->event_id = $this->event->id;
        }
    }

    public function save()
    {
        $this->validate();

        $this->ticket->save();

        return redirect()->route('organizer.event.edit', $this->event->id)
            ->with('success', $this->isEditing ? 'Ingresso atualizado com sucesso!' : 'Ingresso criado com sucesso!');
    }

    public function render()
    {
        return view('livewire.organizer.ticket-form')
            ->layout('layouts.app', ['header' => $this->isEditing ? 'Editar Ingresso' : 'Criar Ingresso']);
    }
}
