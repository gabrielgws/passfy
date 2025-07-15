<div>
    <!-- Botão do Carrinho (Floating) -->
    <div class="fixed bottom-6 right-6 z-50">
        <button
            wire:click="$set('showCart', true)"
            class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg transition-colors relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
            </svg>

            @if($this->cartCount > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center">
                {{ $this->cartCount }}
            </span>
            @endif
        </button>
    </div>

    <!-- Modal do Carrinho -->
    @if($showCart)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end sm:items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Carrinho ({{ $this->cartCount }})
                </h3>
                <button
                    wire:click="$set('showCart', false)"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Conteúdo -->
            <div class="flex-1 overflow-y-auto p-4">
                @if(count($cartItems) > 0)
                <div class="space-y-4">
                    @foreach($cartItems as $ticketId => $item)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item['event_title'] }}</p>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">
                                R$ {{ number_format($item['price'], 2, ',', '.') }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <button
                                wire:click="updateItemQuantity({{ $ticketId }}, {{ $item['quantity'] - 1 }})"
                                class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>

                            <span class="w-8 text-center font-medium text-gray-900 dark:text-white">
                                {{ $item['quantity'] }}
                            </span>

                            <button
                                wire:click="updateItemQuantity({{ $ticketId }}, {{ $item['quantity'] + 1 }})"
                                class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>

                        <button
                            wire:click="removeItem({{ $ticketId }})"
                            class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>

                <!-- Resumo -->
                <div class="mt-6 space-y-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                        <span class="text-gray-900 dark:text-white">R$ {{ number_format($this->subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Taxa de serviço:</span>
                        <span class="text-gray-900 dark:text-white">R$ {{ number_format($this->maintenance_fee, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold border-t border-gray-200 dark:border-gray-700 pt-2">
                        <span class="text-gray-900 dark:text-white">Total:</span>
                        <span class="text-green-600 dark:text-green-400">R$ {{ number_format($this->total, 2, ',', '.') }}</span>
                    </div>
                </div>

                @else
                <!-- Carrinho Vazio -->
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Carrinho vazio</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Adicione ingressos ao seu carrinho para continuar.</p>
                    <button
                        wire:click="$set('showCart', false)"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Continuar Comprando
                    </button>
                </div>
                @endif
            </div>

            <!-- Footer -->
            @if(count($cartItems) > 0)
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
                <button
                    wire:click="clearCart"
                    class="w-full text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                    Limpar Carrinho
                </button>

                <button
                    wire:click="proceedToCheckout"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                    Finalizar Compra
                </button>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
