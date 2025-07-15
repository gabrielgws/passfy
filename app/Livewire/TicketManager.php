<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketManager extends Component
{
    public $eventId;
    public $event;
    public $tickets;
    public $showFormModal = false;
    public $editingId = null;
    public $confirmingDelete = false;
    public $deleteId = null;

    protected $listeners = ['closeTicketForm' => 'closeModal', 'ticketSaved' => 'ticketSaved'];

    public function mount($eventId)
    {
        $this->eventId = $eventId;
        $this->loadEvent();
        $this->loadTickets();
    }

    public function loadEvent()
    {
        $this->event = Event::where('user_id', Auth::id())->findOrFail($this->eventId);
    }

    public function loadTickets()
    {
        $this->tickets = Ticket::where('event_id', $this->eventId)
            ->orderBy('price')
            ->get();
    }

    public function showCreateModal()
    {
        $this->editingId = null;
        $this->showFormModal = true;
    }

    public function showEditModal($id)
    {
        $this->editingId = $id;
        $this->showFormModal = true;
    }

    public function closeModal()
    {
        $this->showFormModal = false;
        $this->editingId = null;
    }

    public function ticketSaved()
    {
        $this->showFormModal = false;
        $this->editingId = null;
        $this->loadTickets();
        session()->flash('success', 'Ingresso salvo com sucesso!');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteTicket()
    {
        $ticket = Ticket::whereHas('event', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($this->deleteId);

        $ticket->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->loadTickets();
        session()->flash('success', 'Ingresso removido com sucesso!');
    }

    public function toggleActive($id)
    {
        $ticket = Ticket::whereHas('event', function ($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($id);

        $ticket->update(['is_active' => !$ticket->is_active]);
        $this->loadTickets();
        session()->flash('success', 'Status do ingresso atualizado!');
    }

    public function render()
    {
        return view('livewire.ticket-manager');
    }
}
