<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Event;
use App\Models\Ticket;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $eventId;
    public $event;
    public $showCart = false;

    public function mount($eventId = null)
    {
        $this->eventId = $eventId;
        $this->loadCart();

        if ($eventId) {
            $this->event = Event::published()->findOrFail($eventId);
        }
    }

    public function loadCart()
    {
        $this->cartItems = session('cart', []);
    }

    public function addItem($ticketId, $quantity = 1)
    {
        $ticket = Ticket::with('event')->findOrFail($ticketId);

        // Verificar se o ticket está disponível
        if (!$ticket->is_on_sale) {
            $this->dispatch('showError', 'Este ingresso não está disponível para compra.');
            return;
        }

        if ($ticket->available_quantity < $quantity) {
            $this->dispatch('showError', 'Quantidade solicitada não disponível.');
            return;
        }

        // Verificar limite por pedido
        $currentQuantity = $this->getCartItemQuantity($ticketId);
        if ($ticket->max_per_order && ($currentQuantity + $quantity) > $ticket->max_per_order) {
            $this->dispatch('showError', "Limite máximo de {$ticket->max_per_order} ingressos por pedido.");
            return;
        }

        $cartItems = session('cart', []);

        if (isset($cartItems[$ticketId])) {
            $cartItems[$ticketId]['quantity'] += $quantity;
        } else {
            $cartItems[$ticketId] = [
                'id' => $ticket->id,
                'name' => $ticket->name,
                'price' => $ticket->price,
                'event_title' => $ticket->event->title,
                'event_id' => $ticket->event->id,
                'quantity' => $quantity,
            ];
        }

        session(['cart' => $cartItems]);
        $this->loadCart();
        $this->showCart = true;

        $this->dispatch('showSuccess', 'Ingresso adicionado ao carrinho!');
    }

    public function removeItem($ticketId)
    {
        $cartItems = session('cart', []);
        unset($cartItems[$ticketId]);
        session(['cart' => $cartItems]);
        $this->loadCart();

        $this->dispatch('showSuccess', 'Ingresso removido do carrinho!');
    }

    public function updateItemQuantity($ticketId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeItem($ticketId);
            return;
        }

        $ticket = Ticket::findOrFail($ticketId);

        if ($ticket->available_quantity < $quantity) {
            $this->dispatch('showError', 'Quantidade solicitada não disponível.');
            return;
        }

        if ($ticket->max_per_order && $quantity > $ticket->max_per_order) {
            $this->dispatch('showError', "Limite máximo de {$ticket->max_per_order} ingressos por pedido.");
            return;
        }

        $cartItems = session('cart', []);
        if (isset($cartItems[$ticketId])) {
            $cartItems[$ticketId]['quantity'] = $quantity;
            session(['cart' => $cartItems]);
            $this->loadCart();
        }
    }

    public function clearCart()
    {
        session()->forget('cart');
        $this->loadCart();
        $this->dispatch('showSuccess', 'Carrinho limpo!');
    }

    public function getCartItemQuantity($ticketId)
    {
        return $this->cartItems[$ticketId]['quantity'] ?? 0;
    }

    public function getSubtotalProperty()
    {
        $subtotal = 0;
        foreach ($this->cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    public function getMaintenanceFeeProperty()
    {
        if (empty($this->cartItems)) {
            return 0;
        }

        // Pegar a taxa de manutenção do primeiro evento (assumindo mesma taxa para todos)
        $firstItem = reset($this->cartItems);
        $event = Event::find($firstItem['event_id']);

        return $this->subtotal * ($event->maintenance_fee_percentage / 100);
    }

    public function getTotalProperty()
    {
        return $this->subtotal + $this->maintenance_fee;
    }

    public function getCartCountProperty()
    {
        $count = 0;
        foreach ($this->cartItems as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    public function proceedToCheckout()
    {
        if (empty($this->cartItems)) {
            $this->dispatch('showError', 'Carrinho vazio!');
            return;
        }

        return redirect()->route('public.checkout');
    }

    public function render()
    {
        return view('livewire.public.cart');
    }
}
