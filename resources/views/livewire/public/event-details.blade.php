<div>
    @if($event)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conteúdo Principal -->
        <div class="lg:col-span-2">
            <!-- Imagem do Evento -->
            <div class="mb-6">
                @if($event->hasImage())
                <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-64 lg:h-96 object-cover rounded-lg">
                @else
                <div class="w-full h-64 lg:h-96 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                @endif
            </div>

            <!-- Informações do Evento -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <!-- Categoria -->
                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $event->category->name }}
                    </span>
                </div>

                <!-- Título -->
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $event->title }}
                </h1>

                <!-- Descrição -->
                @if($event->description)
                <div class="prose dark:prose-invert max-w-none mb-6">
                    {!! nl2br(e($event->description)) !!}
                </div>
                @endif

                <!-- Informações de Data e Local -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Data e Hora</h3>
                        <div class="space-y-2">
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">Início:</span>
                                <span class="ml-2">{{ $event->start_date->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">Fim:</span>
                                <span class="ml-2">{{ $event->end_date->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Local</h3>
                        <div class="space-y-2">
                            <div class="flex items-start text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium">{{ $event->location }}</div>
                                    @if($event->address)
                                    <div class="text-sm">{{ $event->address }}</div>
                                    @endif
                                    <div class="text-sm">{{ $event->city }}, {{ $event->state }}</div>
                                    @if($event->zip_code)
                                    <div class="text-sm">CEP: {{ $event->zip_code }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar com Ingressos -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 sticky top-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Ingressos</h2>

                @if($tickets->count() > 0)
                <div class="space-y-4">
                    @foreach($tickets as $ticket)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $ticket->name }}</h3>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                R$ {{ number_format($ticket->price, 2, ',', '.') }}
                            </span>
                        </div>

                        @if($ticket->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            {{ $ticket->description }}
                        </p>
                        @endif

                        <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-3">
                            <span>Disponível: {{ $ticket->available_quantity }}</span>
                            @if($ticket->is_sold_out)
                            <span class="text-red-600 dark:text-red-400 font-medium">Esgotado</span>
                            @endif
                        </div>

                        @if($ticket->sale_start_date || $ticket->sale_end_date)
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            @if($ticket->sale_start_date)
                            <div>Venda inicia: {{ $ticket->sale_start_date->format('d/m/Y H:i') }}</div>
                            @endif
                            @if($ticket->sale_end_date)
                            <div>Venda termina: {{ $ticket->sale_end_date->format('d/m/Y H:i') }}</div>
                            @endif
                        </div>
                        @endif

                        @if($ticket->is_on_sale && !$ticket->is_sold_out)
                        <div class="space-y-2">
                            <!-- Seletor de Quantidade -->
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Quantidade:</label>
                                <div class="flex items-center space-x-2">
                                    <button
                                        type="button"
                                        wire:click="decrementQuantity({{ $ticket->id }})"
                                        class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <input
                                        type="number"
                                        id="qty-{{ $ticket->id }}"
                                        min="1"
                                        max="{{ min($ticket->available_quantity, $ticket->max_per_order ?? 999) }}"
                                        wire:model="quantities.{{ $ticket->id }}"
                                        class="w-12 text-center border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-sm">
                                    <button
                                        type="button"
                                        wire:click="incrementQuantity({{ $ticket->id }})"
                                        class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <button
                                type="button"
                                wire:click="addToCart({{ $ticket->id }})"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                </svg>
                                <span>Adicionar ao Carrinho</span>
                            </button>
                        </div>
                        @elseif($ticket->is_sold_out)
                        <button disabled class="w-full bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed">
                            Esgotado
                        </button>
                        @else
                        <button disabled class="w-full bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed">
                            Venda Indisponível
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>

                @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Nenhum ingresso disponível no momento.</p>
                </div>
                @endif

                <!-- Informações do Organizador -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Organizado por</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $event->user->name }}</p>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="text-center py-12">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Evento não encontrado</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">O evento que você está procurando não existe ou não está disponível.</p>
        <a href="{{ route('public.events') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
            Voltar aos Eventos
        </a>
    </div>
    @endif
</div>
