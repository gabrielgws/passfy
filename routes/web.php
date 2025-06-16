<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ServiceFeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\TicketValidationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Eventos públicos
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

// Rotas autenticadas
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/events', [DashboardController::class, 'events'])->name('dashboard.events');
    Route::get('/dashboard/events/{event}', [DashboardController::class, 'eventDetails'])->name('dashboard.events.show');
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');

    // Rotas temporárias (implementar depois)
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::get('/tickets/scanner', fn() => Inertia::render('Welcome'))->name('tickets.scanner');
    Route::get('/dashboard/analytics', fn() => Inertia::render('Welcome'))->name('dashboard.analytics');
    Route::get('/orders/{order}', fn() => Inertia::render('Welcome'))->name('orders.show');

    // Tipos de ingresso
    Route::post('/events/{event}/tickets', [TicketTypeController::class, 'store'])->name('tickets.store');
    Route::put('/events/{event}/tickets/{ticketType}', [TicketTypeController::class, 'update'])->name('tickets.update');
    Route::delete('/events/{event}/tickets/{ticketType}', [TicketTypeController::class, 'destroy'])->name('tickets.destroy');

    // Compra de ingressos
    Route::get('/events/{event}/buy', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/events/{event}/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
    Route::post('/orders/{order}/process-payment', [OrderController::class, 'processPayment'])->name('orders.process-payment');
    Route::get('/orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Validação de ingressos
    Route::get('/tickets/scanner', [TicketValidationController::class, 'scanner'])->name('tickets.scanner');
    Route::post('/tickets/validate', [TicketValidationController::class, 'validate'])->name('tickets.validate');

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        // Categorias
        Route::resource('categories', CategoryController::class);

        // Taxa de serviço
        Route::put('/events/{event}/service-fee', [ServiceFeeController::class, 'update'])->name('events.service-fee');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
