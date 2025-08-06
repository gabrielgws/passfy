<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('public.events') }}" class="text-blue-600 hover:text-blue-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Finalizar Compra</h1>
            </div>
        </div>

        @if($errorBag->has('general'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="ml-3">
                    <p class="text-sm text-red-800 dark:text-red-200">{{ $errorBag->first('general') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Formulário de Dados -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Dados do Comprador</h2>

                <form wire:submit.prevent="processOrder" class="space-y-6">
                    <!-- Nome -->
                    <div>
                        <label for="customerName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nome Completo *
                        </label>
                        <input
                            type="text"
                            id="customerName"
                            wire:model="customerName"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Digite seu nome completo">
                        @error('customerName')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="customerEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            E-mail *
                        </label>
                        <input
                            type="email"
                            id="customerEmail"
                            wire:model="customerEmail"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="seu@email.com">
                        @error('customerEmail')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telefone -->
                    <div>
                        <label for="customerPhone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Telefone *
                        </label>
                        <input
                            type="tel"
                            id="customerPhone"
                            wire:model="customerPhone"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="(11) 99999-9999">
                        @error('customerPhone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CPF/CNPJ -->
                    <div>
                        <label for="customerDocument" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            CPF/CNPJ *
                        </label>
                        <input
                            type="text"
                            id="customerDocument"
                            wire:model="customerDocument"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            placeholder="000.000.000-00">
                        @error('customerDocument')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Método de Pagamento -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Forma de Pagamento *
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    wire:model.live="paymentMethod"
                                    value="pix"
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">PIX</span>
                            </label>
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    wire:model.live="paymentMethod"
                                    value="credit_card"
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Cartão de Crédito</span>
                            </label>
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    wire:model.live="paymentMethod"
                                    value="debit_card"
                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 text-gray-700 dark:text-gray-300">Cartão de Débito</span>
                            </label>
                        </div>
                        @error('paymentMethod')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        
                        <!-- Informação sobre Cartão de Crédito/Débito -->
                        @if(in_array($paymentMethod, ['credit_card', 'debit_card']))
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg space-y-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Você será redirecionado para o checkout seguro do Stripe para finalizar seu pagamento.</span>
                            </div>
                            
                            <div class="flex items-center mt-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">Seus dados estão seguros e criptografados</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Botão Finalizar -->
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white py-3 px-4 rounded-lg font-medium transition-colors flex items-center justify-center space-x-2">
                        <svg wire:loading class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove>Finalizar Compra</span>
                        <span wire:loading>Processando...</span>
                    </button>
                </form>
            </div>

            <!-- Resumo do Pedido -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 h-fit">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Resumo do Pedido</h2>

                <!-- Itens do Carrinho -->
                <div class="space-y-4 mb-6">
                    @foreach($cartItems as $ticketId => $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item['event_title'] }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500">Qtd: {{ $item['quantity'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900 dark:text-white">
                                R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Valores -->
                <div class="space-y-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                        <span class="text-gray-900 dark:text-white">R$ {{ number_format($this->subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Taxa de serviço:</span>
                        <span class="text-gray-900 dark:text-white">R$ {{ number_format($this->maintenance_fee, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold border-t border-gray-200 dark:border-gray-700 pt-3">
                        <span class="text-gray-900 dark:text-white">Total:</span>
                        <span class="text-green-600 dark:text-green-400">R$ {{ number_format($this->total, 2, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Informações Adicionais -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-2">Informações Importantes</h3>
                    <ul class="text-xs text-blue-800 dark:text-blue-300 space-y-1">
                        <li>• Os ingressos serão enviados por e-mail após a confirmação do pagamento</li>
                        <li>• Apresente o QR Code no dia do evento</li>
                        <li>• Não é possível cancelar ou reembolsar ingressos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('livewire:initialized', function() {
        // Escuta o evento do Livewire quando o pedido for processado e o checkout do Stripe for criado
        Livewire.on('redirectToStripeCheckout', function(checkoutUrl) {
            console.log('Evento redirectToStripeCheckout recebido com URL:', checkoutUrl);
            // Redireciona para o checkout do Stripe
            window.location.href = checkoutUrl;
        });
    });
</script>
@endpush
