<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Checkout extends Component
{
    public $cartItems = [];
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $customerDocument = '';
    public $paymentMethod = 'pix';
    public $isProcessing = false;

    protected $rules = [
        'customerName' => 'required|string|max:120',
        'customerEmail' => 'required|email|max:120',
        'customerPhone' => 'required|string|max:20',
        'customerDocument' => 'required|string|max:20',
        'paymentMethod' => 'required|in:pix,credit_card,debit_card',
    ];

    public function mount()
    {
        $this->cartItems = session('cart', []);

        if (empty($this->cartItems)) {
            return redirect()->route('public.events');
        }

        // Preencher dados do usuário se estiver logado
        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
        }
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

        // Pegar a taxa de manutenção do primeiro evento
        $firstItem = reset($this->cartItems);
        $event = Event::find($firstItem['event_id']);

        return $this->subtotal * ($event->maintenance_fee_percentage / 100);
    }

    public function getTotalProperty()
    {
        return $this->subtotal + $this->maintenance_fee;
    }

    public function getEventsProperty()
    {
        $eventIds = collect($this->cartItems)->pluck('event_id')->unique();
        return Event::whereIn('id', $eventIds)->get();
    }

    public function processOrder()
    {
        $this->validate();

        // Verificar se ainda há ingressos disponíveis
        foreach ($this->cartItems as $ticketId => $item) {
            $ticket = Ticket::find($ticketId);
            if (!$ticket || $ticket->available_quantity < $item['quantity']) {
                $this->addError('general', "O ingresso '{$item['name']}' não está mais disponível na quantidade solicitada.");
                return;
            }
        }

        $this->isProcessing = true;

        try {
            DB::beginTransaction();

            // Criar o pedido
            $order = Order::create([
                'user_id' => Auth::id(),
                'event_id' => $this->events->first()->id, // Assumindo um evento por pedido por simplicidade
                'customer_name' => $this->customerName,
                'customer_email' => $this->customerEmail,
                'customer_phone' => $this->customerPhone,
                'customer_document' => $this->customerDocument,
                'subtotal' => $this->subtotal,
                'maintenance_fee' => $this->maintenance_fee,
                'total' => $this->total,
                'status' => 'pending',
                'payment_method' => $this->paymentMethod,
            ]);

            // Criar os itens do pedido
            foreach ($this->cartItems as $ticketId => $item) {
                $ticket = Ticket::find($ticketId);

                OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticket->id,
                    'ticket_name' => $ticket->name,
                    'unit_price' => $ticket->price,
                    'quantity' => $item['quantity'],
                    'total_price' => $ticket->price * $item['quantity'],
                    'status' => 'active',
                ]);

                // Atualizar quantidade vendida
                $ticket->increment('sold_quantity', $item['quantity']);
            }

            // Atualizar quantidade vendida do evento
            $event = Event::find($this->events->first()->id);
            $event->increment('sold_tickets', collect($this->cartItems)->sum('quantity'));

            DB::commit();

            // Limpar carrinho
            session()->forget('cart');

            // Redirecionar para página de sucesso
            return redirect()->route('public.order.success', $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', 'Erro ao processar o pedido. Tente novamente.');
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.public.checkout');
    }
}
