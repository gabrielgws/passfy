<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <div class="min-h-screen">
        <!-- Event Header -->
        <div class="relative h-96">
            <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                <div class="max-w-7xl mx-auto">
                    <span class="inline-block px-3 py-1 bg-indigo-600 rounded-full text-sm mb-4">{{ $event->category->name }}</span>
                    <h1 class="text-4xl font-bold mb-4">{{ $event->title }}</h1>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $event->start_date->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Event Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-2xl font-semibold mb-4">Sobre o evento</h2>
                        <div class="prose dark:prose-invert max-w-none">
                            {{ $event->description }}
                        </div>
                    </div>

                    <!-- Organizer Info -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-semibold mb-4">Organizador</h2>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-semibold">
                                {{ substr($event->user->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold">{{ $event->user->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400">Organizador</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Purchase -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-2xl font-semibold mb-4">Ingressos</h2>

                        @if($event->tickets->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Não há ingressos disponíveis para este evento.</p>
                        @else
                        <form wire:submit="purchaseTicket" class="space-y-4">
                            @foreach($event->tickets as $ticket)
                            <div class="border rounded-lg p-4 {{ $selectedTicket == $ticket->id ? 'border-indigo-600' : 'border-gray-200' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            wire:model="selectedTicket"
                                            value="{{ $ticket->id }}"
                                            class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 font-semibold">{{ $ticket->name }}</span>
                                    </label>
                                    <span class="text-lg font-bold text-indigo-600">
                                        R$ {{ number_format($ticket->price, 2, ',', '.') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->description }}</p>
                                @if($ticket->quantity_available)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    {{ $ticket->quantity_available }} ingressos disponíveis
                                </p>
                                @endif
                            </div>
                            @endforeach

                            @if($selectedTicket)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Quantidade
                                </label>
                                <input
                                    type="number"
                                    wire:model="quantity"
                                    min="1"
                                    max="{{ $selectedTicket ? $event->tickets->find($selectedTicket)->quantity_available : 1 }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div class="mt-6">
                                <div class="flex justify-between mb-2">
                                    <span>Subtotal</span>
                                    <span class="font-semibold">
                                        R$ {{ number_format($subtotal, 2, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span>Taxa de serviço</span>
                                    <span class="font-semibold">
                                        R$ {{ number_format($serviceFee, 2, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-lg font-bold pt-2 border-t">
                                    <span>Total</span>
                                    <span class="text-indigo-600">
                                        R$ {{ number_format($total, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="w-full mt-6 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                Comprar ingressos
                            </button>
                            @endif
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @fluxScripts
</body>

</html>
