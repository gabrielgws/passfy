<x-layouts.public title="Pedido Confirmado - Passfy">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Card de Sucesso -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 text-center">
                <!-- Ícone de Sucesso -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 mb-6">
                    <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <!-- Título -->
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Pedido Confirmado!
                </h1>

                <!-- Número do Pedido -->
                <div class="mb-6">
                    <p class="text-gray-600 dark:text-gray-400 mb-2">Número do Pedido:</p>
                    <p class="text-2xl font-mono font-bold text-blue-600 dark:text-blue-400">{{ $order->order_number }}</p>
                </div>

                <!-- Informações do Pedido -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8 text-left">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detalhes do Pedido</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Cliente:</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $order->customer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">E-mail:</span>
                            <span class="text-gray-900 dark:text-white">{{ $order->customer_email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Total:</span>
                            <span class="text-green-600 dark:text-green-400 font-bold">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status:</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($order->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                @if($order->status === 'paid') Pago
                                @elseif($order->status === 'pending') Pendente
                                @else {{ ucfirst($order->status) }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Ingressos -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8 text-left">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ingressos</h3>

                    <div class="space-y-3">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-600 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->ticket_name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Qtd: {{ $item->quantity }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">Código: {{ $item->ticket_code }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900 dark:text-white">
                                    R$ {{ number_format($item->total_price, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Próximos Passos -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-200 mb-4">Próximos Passos</h3>

                    <div class="space-y-3 text-left">
                        @if($order->status === 'pending')
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                1
                            </div>
                            <div>
                                <p class="text-blue-900 dark:text-blue-200 font-medium">Aguardando Pagamento</p>
                                <p class="text-blue-800 dark:text-blue-300 text-sm">Complete o pagamento para receber seus ingressos</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $order->status === 'pending' ? '2' : '1' }}
                            </div>
                            <div>
                                <p class="text-blue-900 dark:text-blue-200 font-medium">Receba seus Ingressos</p>
                                <p class="text-blue-800 dark:text-blue-300 text-sm">Os ingressos serão enviados por e-mail após a confirmação</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $order->status === 'pending' ? '3' : '2' }}
                            </div>
                            <div>
                                <p class="text-blue-900 dark:text-blue-200 font-medium">Apresente no Evento</p>
                                <p class="text-blue-800 dark:text-blue-300 text-sm">Mostre o QR Code no dia do evento</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('public.events') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-medium transition-colors">
                        Ver Mais Eventos
                    </a>

                    @if(Auth::check())
                    <a href="{{ route('user.orders') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg font-medium transition-colors">
                        Meus Pedidos
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
