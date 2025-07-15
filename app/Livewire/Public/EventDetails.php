<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Event;
use Livewire\Component;

class EventDetails extends Component
{
    public $eventId;
    public $event;
    public $tickets;
    public $quantities = [];

    public function mount($eventId)
    {
        $this->eventId = $eventId;
        $this->loadEvent();
        // Inicializa as quantidades para cada ingresso
        foreach ($this->tickets ?? [] as $ticket) {
            $this->quantities[$ticket->id] = 1;
        }
    }

    public function loadEvent()
    {
        $this->event = Event::published()
            ->with(['category', 'tickets' => function ($query) {
                $query->where('is_active', true);
            }])
            ->findOrFail($this->eventId);

        $this->tickets = $this->event->tickets->where('is_active', true);
        // Atualiza as quantidades caso o evento seja recarregado
        foreach ($this->tickets as $ticket) {
            if (!isset($this->quantities[$ticket->id])) {
                $this->quantities[$ticket->id] = 1;
            }
        }
    }

    public function incrementQuantity($ticketId)
    {
        $ticket = $this->tickets->where('id', $ticketId)->first();
        if (!$ticket) return;
        $max = min($ticket->available_quantity, $ticket->max_per_order ?? 999);
        if ($this->quantities[$ticketId] < $max) {
            $this->quantities[$ticketId]++;
        }
    }

    public function decrementQuantity($ticketId)
    {
        if (isset($this->quantities[$ticketId]) && $this->quantities[$ticketId] > 1) {
            $this->quantities[$ticketId]--;
        }
    }

    public function addToCart($ticketId)
    {
        $ticket = $this->tickets->where('id', $ticketId)->first();
        if (!$ticket) return;

        $quantity = $this->quantities[$ticketId] ?? 1;
        $max = min($ticket->available_quantity, $ticket->max_per_order ?? 999);

        if ($quantity < 1 || $quantity > $max) {
            $quantity = 1;
        }

        // Adicionar ao carrinho via sessão
        $cartItems = session('cart', []);

        if (isset($cartItems[$ticketId])) {
            $cartItems[$ticketId]['quantity'] += $quantity;
        } else {
            $cartItems[$ticketId] = [
                'ticket_id' => $ticketId,
                'event_id' => $ticket->event_id,
                'name' => $ticket->name,
                'price' => $ticket->price,
                'quantity' => $quantity,
                'event_title' => $ticket->event->title,
            ];
        }

        session(['cart' => $cartItems]);

        // Redirecionar para a página do carrinho
        return redirect()->route('public.cart');
    }

    public function render()
    {
        return view('livewire.public.event-details');
    }
}
