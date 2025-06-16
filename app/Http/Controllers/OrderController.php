<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function create(Event $event)
    {
        $event->load(['ticketTypes' => function ($query) {
            $query->where('is_active', true);
        }]);

        return Inertia::render('Orders/Create', [
            'event' => $event,
        ]);
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'tickets' => 'required|array',
            'tickets.*.ticket_type_id' => 'required|exists:ticket_types,id',
            'tickets.*.quantity' => 'required|integer|min:1',
            'tickets.*.attendees' => 'required|array',
            'tickets.*.attendees.*.name' => 'required|string|max:255',
            'tickets.*.attendees.*.email' => 'required|email',
            'tickets.*.attendees.*.document' => 'nullable|string',
            'buyer_info' => 'required|array',
            'buyer_info.name' => 'required|string|max:255',
            'buyer_info.email' => 'required|email',
            'buyer_info.phone' => 'required|string',
            'buyer_info.document' => 'required|string',
        ]);

        $order = DB::transaction(function () use ($validated, $event) {
            $subtotal = 0;
            $ticketItems = [];

            // Validar disponibilidade e calcular subtotal
            foreach ($validated['tickets'] as $item) {
                $ticketType = TicketType::findOrFail($item['ticket_type_id']);

                if (!$ticketType->isAvailable()) {
                    throw new \Exception("O ingresso {$ticketType->name} não está disponível.");
                }

                if ($ticketType->available < $item['quantity']) {
                    throw new \Exception("Não há ingressos suficientes disponíveis para {$ticketType->name}.");
                }

                $ticketItems[] = [
                    'ticket_type' => $ticketType,
                    'quantity' => $item['quantity'],
                    'attendees' => $item['attendees'],
                ];

                $subtotal += $ticketType->price * $item['quantity'];
            }

            // Calcular taxa de serviço
            $serviceFee = $subtotal * ($event->service_fee_percentage / 100);
            $total = $subtotal + $serviceFee;

            // Criar pedido
            $order = Order::create([
                'user_id' => auth()->id(),
                'event_id' => $event->id,
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'total' => $total,
                'buyer_info' => $validated['buyer_info'],
            ]);

            // Criar ingressos
            foreach ($ticketItems as $item) {
                $ticketType = $item['ticket_type'];

                foreach ($item['attendees'] as $attendee) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'ticket_type_id' => $ticketType->id,
                        'attendee_name' => $attendee['name'],
                        'attendee_email' => $attendee['email'],
                        'attendee_document' => $attendee['document'] ?? null,
                    ]);
                }

                // Atualizar quantidade vendida
                $ticketType->increment('sold', $item['quantity']);
            }

            return $order;
        });

        return redirect()->route('orders.payment', $order);
    }

    public function payment(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['event', 'tickets.ticketType']);

        return Inertia::render('Orders/Payment', [
            'order' => $order,
        ]);
    }

    public function processPayment(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        // Aqui você integraria com um gateway de pagamento real
        // Por exemplo: Stripe, PagSeguro, Mercado Pago, etc.

        $order->update([
            'status' => 'paid',
            'payment_method' => 'credit_card',
            'payment_id' => 'PAY-' . uniqid(),
        ]);

        // Enviar e-mail com os ingressos
        // Mail::to($order->buyer_info['email'])->send(new OrderConfirmation($order));

        return redirect()->route('orders.confirmation', $order);
    }

    public function confirmation(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['event', 'tickets.ticketType']);

        return Inertia::render('Orders/Confirmation', [
            'order' => $order,
        ]);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['event', 'tickets.ticketType']);

        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }
}
