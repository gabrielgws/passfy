{{-- resources/views/livewire/event/ticket-types-manager.blade.php --}}
<div>
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <flux:heading size="lg">Ingressos do Evento</flux:heading>
            <flux:subheading>Gerencie os tipos de ingressos disponíveis</flux:subheading>
        </div>

        <flux:button variant="primary" wire:click="openAddTicketModal">
            <flux:icon.plus class="size-5" />
            Adicionar Ingresso
        </flux:button>
    </div>

    {{-- Lista de Ingressos --}}
    <div class="space-y-4">
        @forelse($ticketTypes as $ticket)
        <flux:card>
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <flux:heading size="sm">{{ $ticket->name }}</flux:heading>

                        @if(!$ticket->is_visible)
                        <flux:badge variant="ghost" size="sm">Oculto</flux:badge>
                        @endif

                        @if($ticket->quantity_remaining <= 0)
                            <flux:badge variant="danger" size="sm">Esgotado</flux:badge>
                            @elseif($ticket->quantity_remaining <= 10)
                                <flux:badge variant="warning" size="sm">
                                Últimas {{ $ticket->quantity_remaining }} unidades
                                </flux:badge>
                                @endif
                    </div>

                    @if($ticket->description)
                    <p class="text-sm text-gray-600 mt-1">{{ $ticket->description }}</p>
                    @endif

                    <div class="flex items-center gap-6 mt-3 text-sm">
                        <div>
                            <span class="text-gray-500">Preço:</span>
                            <span class="font-semibold">{{ $ticket->formatted_price }}</span>
                        </div>

                        <div>
                            <span class="text-gray-500">Vendidos:</span>
                            <span class="font-semibold">
                                {{ $ticket->quantity_sold }}/{{ $ticket->quantity_available }}
                            </span>
                        </div>

                        @if($ticket->sales_start_at || $ticket->sales_end_at)
                        <div>
                            <span class="text-gray-500">Período de vendas:</span>
                            <span class="font-semibold">
                                {{ $ticket->sales_start_at?->format('d/m/Y H:i') ?? 'Início' }}
                                até
                                {{ $ticket->sales_end_at?->format('d/m/Y H:i') ?? 'Fim' }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <flux:button
                        variant="ghost"
                        size="sm"
                        wire:click="toggleVisibility({{ $ticket->id }})">
                        @if($ticket->is_visible)
                        <flux:icon.eye class="size-4" />
                        @else
                        <flux:icon.eye-off class="size-4" />
                        @endif
                    </flux:button>

                    <flux:button
                        variant="ghost"
                        size="sm"
                        wire:click="editTicket({{ $ticket->id }})">
                        <flux:icon.pencil class="size-4" />
                    </flux:button>

                    <flux:button
                        variant="ghost"
                        size="sm"
                        wire:click="deleteTicket({{ $ticket->id }})"
                        wire:confirm="Tem certeza que deseja excluir este ingresso?">
                        <flux:icon.trash class="size-4" />
                    </flux:button>
                </div>
            </div>
        </flux:card>
        @empty
        <flux:card>
            <div class="text-center py-8">
                <flux:icon.ticket class="size-12 mx-auto text-gray-400 mb-3" />
                <flux:heading size="sm" class="text-gray-600">
                    Nenhum ingresso cadastrado
                </flux:heading>
                <flux:subheading>
                    Adicione tipos de ingressos para começar a vender
                </flux:subheading>
            </div>
        </flux:card>
        @endforelse
    </div>

    {{-- Modal de Adicionar/Editar Ingresso --}}
    <flux:modal wire:model="showAddTicketModal" class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ $editingTicket ? 'Editar Ingresso' : 'Novo Ingresso' }}
            </flux:heading>
            <flux:subheading>
                Configure as informações do tipo de ingresso
            </flux:subheading>
        </div>

        <flux:separator />

        <div class="space-y-4">
            {{-- Nome do Ingresso --}}
            <flux:input
                label="Nome do Ingresso"
                wire:model="ticketForm.name"
                placeholder="Ex: Inteira, Meia-Entrada, VIP"
                error="{{ $errors->first('ticketForm.name') }}" />

            {{-- Descrição --}}
            <flux:textarea
                label="Descrição (opcional)"
                wire:model="ticketForm.description"
                placeholder="Informações adicionais sobre este tipo de ingresso"
                rows="3" />

            {{-- Preço e Quantidade --}}
            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    label="Preço (R$)"
                    type="number"
                    step="0.01"
                    wire:model="ticketForm.price"
                    error="{{ $errors->first('ticketForm.price') }}" />

                <flux:input
                    label="Quantidade Disponível"
                    type="number"
                    wire:model="ticketForm.quantity_available"
                    error="{{ $errors->first('ticketForm.quantity_available') }}" />
            </div>

            {{-- Limites de Compra --}}
            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    label="Mínimo por Compra"
                    type="number"
                    wire:model="ticketForm.min_purchase"
                    error="{{ $errors->first('ticketForm.min_purchase') }}" />

                <flux:input
                    label="Máximo por Compra"
                    type="number"
                    wire:model="ticketForm.max_purchase"
                    error="{{ $errors->first('ticketForm.max_purchase') }}" />
            </div>

            {{-- Período de Vendas --}}
            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    label="Início das Vendas (opcional)"
                    type="datetime-local"
                    wire:model="ticketForm.sales_start_at" />

                <flux:input
                    label="Fim das Vendas (opcional)"
                    type="datetime-local"
                    wire:model="ticketForm.sales_end_at"
                    error="{{ $errors->first('ticketForm.sales_end_at') }}" />
            </div>

            {{-- Visibilidade --}}
            <flux:checkbox
                label="Ingresso visível para compra"
                wire:model="ticketForm.is_visible" />
        </div>

        <flux:separator />

        <div class="flex justify-end gap-3">
            <flux:button variant="ghost" wire:click="closeModal">
                Cancelar
            </flux:button>

            <flux:button variant="primary" wire:click="saveTicket">
                {{ $editingTicket ? 'Salvar Alterações' : 'Criar Ingresso' }}
            </flux:button>
        </div>
    </flux:modal>
</div>
