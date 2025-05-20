<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckCanCreateEvents;
use App\Http\Middleware\CheckIsAdmin;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Organizer\Dashboard as OrganizerDashboard;
use App\Livewire\Organizer\EventEdit;
use App\Livewire\Organizer\EventsList;
use App\Livewire\Organizer\EventForm;
use App\Livewire\Organizer\TicketForm;
use App\Livewire\Organizer\ScanTicket;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Routes para organizadores de eventos
Route::middleware(['auth', CheckCanCreateEvents::class])->prefix('organizer')->group(function () {
    Route::get('/dashboard', OrganizerDashboard::class)->name('organizer.dashboard');
    Route::get('/events', EventsList::class)->name('organizer.events');
    Route::get('/events/create', EventForm::class)->name('organizer.event.create');
    Route::get('/events/{event}/edit', EventEdit::class)->name('organizer.event.edit');
    Route::get('/events/{eventId}/scan', ScanTicket::class)->name('organizer.event.scan');
    Route::get('/events/{eventId}/tickets/create', TicketForm::class)->name('organizer.ticket.create');
    Route::get('/events/{eventId}/tickets/{ticketId}/edit', TicketForm::class)->name('organizer.ticket.edit');
});

Route::middleware(['auth', CheckIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/categories', CategoryManager::class)->name('categories');
});

require __DIR__ . '/auth.php';
