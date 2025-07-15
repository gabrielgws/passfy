<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ingressos - {{ $event->title }}</h1>
            <p class="text-gray-600 dark:text-gray-400">Gerencie os ingressos disponíveis para este evento</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('user.events') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                Voltar aos Eventos
            </a>
            <button wire:click="showCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Novo Ingresso
            </button>
        </div>
    </div>

    @if (session()->has('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <!-- Informações do Evento -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
        <div class="flex items-center space-x-4">
            @if($event->hasImage())
            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-16 h-16 object-cover rounded-lg">
            @else
            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            @endif
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $event->title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $event->start_date ? $event->start_date->format('d/m/Y H:i') : 'Data não definida' }} •
                    {{ $event->location }}, {{ $event->city }}/{{ $event->state }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-500">
                    Status:
                    @if($event->status === 'published')
                    <span class="text-green-600 dark:text-green-400">Publicado</span>
                    @elseif($event->status === 'draft')
                    <span class="text-yellow-600 dark:text-yellow-400">Rascunho</span>
                    @else
                    <span class="text-gray-600 dark:text-gray-400">{{ ucfirst($event->status) }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Nome</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Preço</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Quantidade</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Vendidos</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Disponíveis</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $ticket->name }}</div>
                                @if($ticket->description)
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($ticket->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-medium text-gray-900 dark:text-white">
                                    R$ {{ number_format($ticket->price, 2, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->quantity }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->sold_quantity }}</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($ticket->is_sold_out)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Esgotado
                                </span>
                                @else
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->available_quantity }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($ticket->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Ativo
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    Inativo
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="showEditModal({{ $ticket->id }})" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                        Editar
                                    </button>
                                    <button wire:click="toggleActive({{ $ticket->id }})" class="text-blue-600 hover:text-blue-900">
                                        {{ $ticket->is_active ? 'Desativar' : 'Ativar' }}
                                    </button>
                                    <button wire:click="confirmDelete({{ $ticket->id }})" class="text-red-600 hover:text-red-900">
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum ingresso encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Comece criando o primeiro ingresso para este evento.</p>
                <button wire:click="showCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Criar Ingresso
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal de Formulário de Ingresso -->
    @if($showFormModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            @livewire('ticket-form', ['eventId' => $eventId, 'ticketId' => $editingId], key($editingId ?: 'new'))
        </div>
    </div>
    @endif

    <!-- Modal de Confirmação de Exclusão -->
    @if($confirmingDelete)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Confirmar Exclusão</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Tem certeza que deseja excluir este ingresso? Esta ação não pode ser desfeita.
            </p>
            <div class="flex items-center justify-end space-x-3">
                <button wire:click="$set('confirmingDelete', false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Cancelar
                </button>
                <button wire:click="deleteTicket" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Excluir Ingresso
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
