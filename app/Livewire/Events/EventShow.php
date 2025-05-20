<?php

namespace App\Livewire\Events;

use Livewire\Component;
use App\Models\Event;

class EventShow extends Component
{
    public Event $event;
    public $selectedTicket = null;
    public $quantity = 1;

    public function mount(Event $event)
    {
        $this->event = $event->load(['category', 'user', 'tickets']);
    }

    public function getSubtotalProperty()
    {
        if (!$this->selectedTicket) {
            return 0;
        }

        $ticket = $this->event->tickets->find($this->selectedTicket);
        return $ticket ? $ticket->price * $this->quantity : 0;
    }

    public function getServiceFeeProperty()
    {
        // Simulando uma taxa de serviço de 10%
        return $this->subtotal * 0.10;
    }

    public function getTotalProperty()
    {
        return $this->subtotal + $this->serviceFee;
    }

    public function purchaseTicket()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $ticket = $this->event->tickets->find($this->selectedTicket);

        if (!$ticket) {
            $this->dispatch('toast', message: 'Ingresso não encontrado.', type: 'error');
            return;
        }

        if ($ticket->quantity_available < $this->quantity) {
            $this->dispatch('toast', message: 'Quantidade de ingressos indisponível.', type: 'error');
            return;
        }

        // Aqui você implementaria a lógica real de compra
        // Por enquanto, vamos apenas simular
        $this->dispatch('toast', message: 'Compra simulada com sucesso!', type: 'success');
    }

    public function render()
    {
        return view('events.show', [
            'event' => $this->event
        ])->layout('layouts.guest');
    }
}
