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
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

class Checkout extends Component
{
    public $cartItems = [];
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $customerDocument = '';
    public $paymentMethod = 'pix';
    public $isProcessing = false;
    
    // Campos para cartão de crédito
    public $cardNumber = '';
    public $cardExpiry = '';
    public $cardCvc = '';
    public $cardName = '';
    
    // Para armazenar erros personalizados
    public $errorBag;

    protected $rules = [
        'customerName' => 'required|string|max:120',
        'customerEmail' => 'required|email|max:120',
        'customerPhone' => 'required|string|max:20',
        'customerDocument' => 'required|string|max:20',
        'paymentMethod' => 'required|in:pix,credit_card,debit_card',
        'cardNumber' => 'required_if:paymentMethod,credit_card,debit_card|string',
        'cardExpiry' => 'required_if:paymentMethod,credit_card,debit_card|string',
        'cardCvc' => 'required_if:paymentMethod,credit_card,debit_card|string',
        'cardName' => 'required_if:paymentMethod,credit_card,debit_card|string',
    ];

    // Método mount removido para evitar duplicação

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
        
        // Inicializar errorBag como uma Collection
        $this->errorBag = collect([]);
    }
    
    public function processOrder()
    {
        $this->errorBag = collect([]);
        $this->validate();

        // Verificar se ainda há ingressos disponíveis
        foreach ($this->cartItems as $ticketId => $item) {
            $ticket = Ticket::find($ticketId);
            if (!$ticket || $ticket->available_quantity < $item['quantity']) {
                $this->errorBag['general'] = "O ingresso '{$item['name']}' não está mais disponível na quantidade solicitada.";
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
                'payment_data' => null,
            ]);

            // Se o método de pagamento for cartão, usar o Stripe Checkout
            if (in_array($this->paymentMethod, ['credit_card', 'debit_card'])) {
                try {
                    $stripe = new StripeClient(config('services.stripe.secret'));
                    
                    // Criar uma sessão de checkout do Stripe
                    $lineItems = [];
                    
                    // Adicionar itens do carrinho à sessão de checkout
                    foreach ($this->cartItems as $ticketId => $item) {
                        $lineItems[] = [
                            'price_data' => [
                                'currency' => 'brl',
                                'product_data' => [
                                    'name' => $item['name'],
                                    'description' => 'Evento: ' . $item['event_name'],
                                ],
                                'unit_amount' => (int)($item['price'] * 100), // Stripe trabalha com centavos
                            ],
                            'quantity' => $item['quantity'],
                        ];
                    }
                    
                    // Adicionar taxa de manutenção se houver
                    if ($this->maintenance_fee > 0) {
                        $lineItems[] = [
                            'price_data' => [
                                'currency' => 'brl',
                                'product_data' => [
                                    'name' => 'Taxa de Serviço',
                                    'description' => 'Taxa de serviço da plataforma',
                                ],
                                'unit_amount' => (int)($this->maintenance_fee * 100),
                            ],
                            'quantity' => 1,
                        ];
                    }
                    
                    // Criar a sessão de checkout
                    $session = $stripe->checkout->sessions->create([
                        'payment_method_types' => ['card'],
                        'line_items' => $lineItems,
                        'mode' => 'payment',
                        'success_url' => route('public.order.success', ['order' => $order->order_number]),
                        'cancel_url' => route('public.checkout'),
                        'customer_email' => $this->customerEmail,
                        'client_reference_id' => $order->order_number,
                        'metadata' => [
                            'order_id' => $order->id,
                        ],
                    ]);
                    
                    // Atualizar pedido com ID da sessão
                    $order->update([
                        'payment_id' => $session->id,
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
                    
                    // Emitir evento para redirecionar para o Stripe Checkout
                    $this->dispatch('redirectToStripeCheckout', $session->url);
                    return;
                    
                } catch (ApiErrorException $e) {
                    DB::rollBack();
                    $this->errorBag['general'] = 'Erro no processamento do pagamento: ' . $e->getMessage();
                    $this->isProcessing = false;
                    return;
                }
            } else {
                // Para outros métodos de pagamento (como PIX), continuar com o fluxo atual
                
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
            return redirect()->route('public.order.success', ['order' => $order->order_number]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorBag['general'] = 'Erro ao processar o pedido: ' . $e->getMessage();
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.public.checkout');
    }
    
    // Método para verificar se o pagamento é com cartão
    public function isCardPayment()
    {
        return in_array($this->paymentMethod, ['credit_card', 'debit_card']);
    }
}
