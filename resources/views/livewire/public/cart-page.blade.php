<div>
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    @if(count($cartItems) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <!-- Lista de Itens -->
        <div class="p-6">
            <div class="space-y-4">
                @foreach($cartItems as $ticketId => $item)
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $item['name'] }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item['event_title'] }}</p>
                        <p class="text-lg font-bold text-green-600 dark:text-green-400">
                            R$ {{ number_format($item['price'], 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Controle de Quantidade -->
                        <div class="flex items-center space-x-2">
                            <button
                                wire:click="updateItemQuantity({{ $ticketId }}, {{ $item['quantity'] - 1 }})"
                                class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="w-12 text-center font-medium">{{ $item['quantity'] }}</span>
                            <button
                                wire:click="updateItemQuantity({{ $ticketId }}, {{ $item['quantity'] + 1 }})"
                                class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Subtotal do Item -->
                        <div class="text-right">
                            <p class="font-semibold text-gray-900 dark:text-white">
                                R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                            </p>
                        </div>

                        <!-- Botão Remover -->
                        <button
                            wire:click="removeItem({{ $ticketId }})"
                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Resumo do Pedido -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-6">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                    <span class="font-semibold">R$ {{ number_format($this->subtotal, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Taxa de Serviço (5%):</span>
                    <span class="font-semibold">R$ {{ number_format($this->maintenance_fee, 2, ',', '.') }}</span>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                    <div class="flex justify-between">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total:</span>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">
                            R$ {{ number_format($this->total, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex flex-col sm:flex-row gap-4 mt-6">
                <button
                    wire:click="continueShopping"
                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg transition-colors">
                    Continuar Comprando
                </button>
                <button
                    wire:click="proceedToCheckout"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg transition-colors">
                    Finalizar Compra
                </button>
            </div>
        </div>
    </div>
    @else
    <!-- Carrinho Vazio -->
    <div class="text-center py-12">
        <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
        </svg>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Seu carrinho está vazio</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8">Adicione alguns ingressos para começar sua compra!</p>
        <a
            href="{{ route('public.events') }}"
            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Ver Eventos
        </a>
    </div>
    @endif
</div>
