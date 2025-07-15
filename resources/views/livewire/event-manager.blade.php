<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Meus Eventos</h1>
            <p class="text-gray-600 dark:text-gray-400">Gerencie seus eventos publicados e rascunhos</p>
        </div>
        <button wire:click="showCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Novo Evento
        </button>
    </div>

    @if (session()->has('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            @if($events->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Imagem</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Título</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Categoria</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Data</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-4 px-4">
                                @if($event->hasImage())
                                <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $event->title }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($event->short_description, 50) }}</div>
                            </td>
                            <td class="py-4 px-4">
                                {{ $event->category->name ?? '-' }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $event->start_date ? $event->start_date->format('d/m/Y H:i') : '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                @if($event->status === 'published')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Publicado
                                </span>
                                @elseif($event->status === 'draft')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Rascunho
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    {{ ucfirst($event->status) }}
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('user.events.tickets', $event->id) }}" class="text-green-600 hover:text-green-900" title="Gerenciar Ingressos">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                    </a>
                                    <button wire:click="showEditModal({{ $event->id }})" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $event->id }})" class="text-red-600 hover:text-red-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
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
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 4h6"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum evento encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Comece criando seu primeiro evento.</p>
                <button wire:click="showCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center mx-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Criar Evento
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal de Formulário de Evento -->
    @if($showFormModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            @livewire('event-form', ['eventId' => $editingId], key($editingId ?: 'new'))
        </div>
    </div>
    @endif

    <!-- Modal de Confirmação de Exclusão -->
    @if($confirmingDelete)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex items-center mb-6">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirmar Exclusão</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Tem certeza que deseja excluir este evento? Esta ação não pode ser desfeita.
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3">
                <button wire:click="$set('confirmingDelete', false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Cancelar
                </button>
                <button wire:click="deleteEvent" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Excluir Evento
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
