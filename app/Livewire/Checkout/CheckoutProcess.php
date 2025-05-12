<?php

namespace App\Livewire\Checkout;

use Livewire\Component;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CheckoutProcess extends Component
{
    public Event $event;
    public $tickets = [];
    public $attendeeInfo = [];
    public $orderCompleted = false;
    public $order = null;

    protected $rules = [
        'tickets.*.ticket_id' => 'required|exists:tickets,id',
        'tickets.*.quantity' => 'required|integer|min:1',
        'attendeeInfo.*.name' => 'required|string|max:255',
        'attendeeInfo.*.email' => 'required|email|max:255',
    ];

    public function mount($eventId)
    {
        $this->event = Event::with('tickets')->findOrFail($eventId);

        // Inicializar os tickets disponíveis
        foreach ($this->event->tickets as $ticket) {
            if ($ticket->getTicketsRemaining() > 0) {
                $this->tickets[] = [
                    'ticket_id' => $ticket->id,
                    'quantity' => 0,
                    'ticket_info' => $ticket
                ];
            }
        }
    }

    public function updateQuantity($index, $change)
    {
        $currentQuantity = $this->tickets[$index]['quantity'];
        $newQuantity = $currentQuantity + $change;

        // Não permitir quantidade negativa
        if ($newQuantity < 0) {
            $newQuantity = 0;
        }

        // Verificar se há ingressos suficientes disponíveis
        $ticketId = $this->tickets[$index]['ticket_id'];
        $ticket = Ticket::find($ticketId);
        $remaining = $ticket->getTicketsRemaining();

        if ($newQuantity > $remaining) {
            $newQuantity = $remaining;
            $this->dispatch('error', ['message' => 'Quantidade máxima de ingressos disponíveis atingida.']);
        }

        $this->tickets[$index]['quantity'] = $newQuantity;

        // Atualizar informações de participantes
        $this->updateAttendeeInfo();
    }

    public function updateAttendeeInfo()
    {
        $newAttendeeInfo = [];
        $index = 0;

        foreach ($this->tickets as $ticketData) {
            $quantity = $ticketData['quantity'];

            for ($i = 0; $i < $quantity; $i++) {
                $newAttendeeInfo[$index] = $this->attendeeInfo[$index] ?? [
                    'ticket_id' => $ticketData['ticket_id'],
                    'name' => '',
                    'email' => '',
                ];
                $index++;
            }
        }

        $this->attendeeInfo = $newAttendeeInfo;
    }

    public function getTotalProperty()
    {
        $total = 0;

        foreach ($this->tickets as $ticketData) {
            $ticket = Ticket::find($ticketData['ticket_id']);
            $total += $ticket->price * $ticketData['quantity'];
        }

        return $total;
    }

    public function checkout()
    {
        // Verificar se há ingressos selecionados
        $totalQuantity = collect($this->tickets)->sum('quantity');
        if ($totalQuantity <= 0) {
            $this->dispatch('error', ['message' => 'Selecione pelo menos um ingresso para continuar.']);
            return;
        }

        // Atualizar e validar as informações dos participantes
        $this->updateAttendeeInfo();
        $this->validate();

        // Criar o pedido
        $order = new Order();
        $order->user_id = auth()->id();
        $order->order_number = 'ORD-' . strtoupper(Str::random(10));
        $order->total = $this->total;
        $order->status = 'completed'; // Simulando pagamento completo
        $order->save();

        // Adicionar os itens do pedido com QR codes
        foreach ($this->attendeeInfo as $attendee) {
            $ticket = Ticket::find($attendee['ticket_id']);

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->ticket_id = $attendee['ticket_id'];
            $orderItem->quantity = 1;
            $orderItem->price = $ticket->price;
            $orderItem->attendee_name = $attendee['name'];
            $orderItem->attendee_email = $attendee['email'];

            // Gerar um QR code único
            $qrString = $order->order_number . '-' . Str::random(10);
            $orderItem->qr_code = $qrString;

            $orderItem->save();
        }

        $this->orderCompleted = true;
        $this->order = $order->load('items.ticket');
    }

    public function render()
    {
        return view('livewire.checkout.checkout-process')
            ->layout('layouts.app', ['header' => 'Checkout - ' . $this->event->title]);
    }
}
