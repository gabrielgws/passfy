<?php

use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;
use App\Models\Event;
use App\Models\Order;

Route::get('/', function () {
    return view('welcome');
});

// Rotas públicas (sem autenticação)
Route::get('/eventos', function () {
    return view('public.events');
})->name('public.events');

Route::get('/eventos/{event:slug}', function ($event) {
    // Garantir que $event seja um objeto Event válido
    if (is_string($event)) {
        $event = Event::where('slug', $event)->published()->firstOrFail();
    }
    return view('public.event-details', ['event' => $event]);
})->name('public.event.details');

// Rota do carrinho (pública)
Route::get('/carrinho', function () {
    return view('public.cart');
})->name('public.cart');

// Rotas de checkout (públicas)
Route::get('/checkout', function () {
    return view('public.checkout');
})->name('public.checkout');

Route::get('/pedido/{order:order_number}/sucesso', function ($order) {
    return view('public.order-success', ['order' => $order]);
})->name('public.order.success');

// Webhook do Stripe para processar eventos de pagamento
Route::post('/webhook/stripe', function () {
    // Recupera o payload do webhook
    $payload = @file_get_contents('php://input');
    $sig_header = request()->header('Stripe-Signature');
    $endpoint_secret = config('services.stripe.webhook_secret');
    
    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        
        // Processa o evento
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            
            // Recupera o pedido pelo metadata
            $orderNumber = $session->metadata->order_number;
            $order = Order::where('order_number', $orderNumber)->first();
            
            if ($order) {
                // Atualiza o status do pedido para pago
                $order->payment_status = 'paid';
                $order->payment_id = $session->payment_intent;
                $order->save();
            }
        }
        
        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
})->name('stripe.webhook');

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Rota para eventos do usuário autenticado (não admin)
    Route::get('/meus-eventos', function () {
        return view('user.events');
    })->name('user.events');

    // Rotas para gerenciamento de ingressos
    Route::get('/meus-eventos/{event}/ingressos', function ($event) {
        return view('user.tickets', ['eventId' => $event]);
    })->name('user.events.tickets');

    // Rotas de admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/categories', function () {
            return view('admin.categories');
        })->name('admin.categories');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
