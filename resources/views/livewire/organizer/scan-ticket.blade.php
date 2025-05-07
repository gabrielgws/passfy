<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Resumo do Evento -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">{{ $event->title }}</h2>
                    <div class="flex flex-col md:flex-row md:justify-between">
                        <div class="mb-4 md:mb-0">
                            <p class="text-gray-600"><strong>Data:</strong>
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</p>
                            <p class="text-gray-600"><strong>Local:</strong> {{ $event->location }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-600"><strong>Check-ins:</strong> {{ $checkedInCount }} /
                                {{ $totalTickets }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulário de Validação -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Validar Ingresso</h3>

                    <div class="mb-6">
                        <label for="qrCode" class="block text-sm font-medium text-gray-700 mb-1">Código QR</label>
                        <div class="flex">
                            <input type="text" id="qrCode" wire:model="qrCode"
                                class="w-full px-4 py-2 border rounded-l-lg @error('qrCode') border-red-500 @enderror"
                                placeholder="Digite ou escaneie o código QR">
                            <button wire:click="verifyTicket"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-lg">
                                Validar
                            </button>
                        </div>
                        @error('qrCode')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($message)
                        <div
                            class="mb-6 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-800' : ($messageType === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    @if ($attendee)
                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium text-lg mb-2">Detalhes do Ingresso</h4>
                            <p><strong>Nome:</strong> {{ $attendee->attendee_name ?: 'Não informado' }}</p>
                            <p><strong>Email:</strong> {{ $attendee->attendee_email ?: 'Não informado' }}</p>
                            <p><strong>Tipo de Ingresso:</strong> {{ $attendee->ticket->name }}</p>
                            <p><strong>Pedido:</strong> #{{ $attendee->order->order_number }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
