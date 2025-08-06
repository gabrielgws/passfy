<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketForm extends Component
{
    public $ticketId;
    public $eventId;
    public $name = '';
    public $description = '';
    public $price = '';
    public $quantity = '';
    public $min_per_order = 1;
    public $max_per_order = '';
    public $sale_start_date = '';
    public $sale_end_date = '';
    public $is_active = true;
    public $event;

    public function mount($eventId, $ticketId = null)
    {
        $this->eventId = $eventId;
        $this->ticketId = $ticketId;
        
        // Carrega o evento para verificar se é gratuito
        $this->event = \App\Models\Event::where('user_id', Auth::id())
            ->findOrFail($eventId);
            
        // Se o evento for gratuito, define o preço como 0
        if ($this->event->is_free) {
            $this->price = 0;
        }

        if ($ticketId) {
            $ticket = Ticket::whereHas('event', function ($query) {
                $query->where('user_id', Auth::id());
            })->findOrFail($ticketId);

            $this->fill($ticket->only([
                'name',
                'description',
                'price',
                'quantity',
                'min_per_order',
                'max_per_order',
                'is_active',
            ]));
            
            // Se o evento for gratuito, garante que o preço seja 0
            if ($this->event->is_free) {
                $this->price = 0;
            }

            $this->sale_start_date = $ticket->sale_start_date ? $ticket->sale_start_date->format('Y-m-d\TH:i') : '';
            $this->sale_end_date = $ticket->sale_end_date ? $ticket->sale_end_date->format('Y-m-d\TH:i') : '';
        }
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|integer|min:1|max:999999',
            'min_per_order' => 'required|integer|min:1|max:100',
            'max_per_order' => 'nullable|integer|min:1|max:100',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'is_active' => 'boolean',
        ];
        
        // Se o evento for gratuito, o preço deve ser 0
        if ($this->event->is_free) {
            $rules['price'] = 'required|numeric|in:0';
        } else {
            $rules['price'] = 'required|numeric|min:0|max:999999.99';
        }
        
        return $rules;
    }

    public function save()
    {
        $this->validate();
        
        // Garante que eventos gratuitos só tenham ingressos gratuitos
        if ($this->event->is_free && (float)$this->price > 0) {
            $this->addError('price', 'Eventos gratuitos só podem ter ingressos gratuitos (preço zero).');
            return;
        }

        $data = [
            'event_id' => $this->eventId,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->event->is_free ? 0 : $this->price, // Garante que o preço seja 0 para eventos gratuitos
            'quantity' => $this->quantity,
            'min_per_order' => $this->min_per_order,
            'max_per_order' => $this->max_per_order ?: null,
            'sale_start_date' => $this->sale_start_date ?: null,
            'sale_end_date' => $this->sale_end_date ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->ticketId) {
            $ticket = Ticket::whereHas('event', function ($query) {
                $query->where('user_id', Auth::id());
            })->findOrFail($this->ticketId);
            $ticket->update($data);
        } else {
            Ticket::create($data);
        }

        $this->dispatch('ticketSaved')->to('ticket-manager');
    }

    public function cancel()
    {
        $this->dispatch('closeTicketForm')->to('ticket-manager');
    }

    public function render()
    {
        return view('livewire.ticket-form');
    }
}
