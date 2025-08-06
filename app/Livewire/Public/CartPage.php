<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CartPage extends Component
{
    public $cartItems = [];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = session('cart', []);
    }

    public function removeItem($ticketId)
    {
        $cartItems = session('cart', []);
        unset($cartItems[$ticketId]);
        session(['cart' => $cartItems]);
        $this->loadCart();

        session()->flash('success', 'Ingresso removido do carrinho!');
    }

    public function updateItemQuantity($ticketId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeItem($ticketId);
            return;
        }

        $ticket = Ticket::findOrFail($ticketId);

        if ($ticket->available_quantity < $quantity) {
            session()->flash('error', 'Quantidade solicitada não disponível.');
            return;
        }

        if ($ticket->max_per_order && $quantity > $ticket->max_per_order) {
            session()->flash('error', "Limite máximo de {$ticket->max_per_order} ingressos por pedido.");
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
        session()->flash('success', 'Carrinho limpo!');
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

        // Taxa de manutenção fixa de 5% (pode ser configurável)
        return $this->subtotal * 0.05;
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
            session()->flash('error', 'Carrinho vazio!');
            return;
        }
        
        // Verificar se o usuário está logado
        if (!auth()->check()) {
            session()->flash('error', 'Você precisa estar logado para finalizar a compra.');
            return redirect()->route('login');
        }

        return redirect()->route('public.checkout');
    }

    public function continueShopping()
    {
        return redirect()->route('public.events');
    }

    public function render()
    {
        return view('livewire.public.cart-page');
    }
}
