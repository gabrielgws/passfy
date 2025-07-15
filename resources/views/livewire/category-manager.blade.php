<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gerenciar Categorias</h1>
            <p class="text-gray-600 dark:text-gray-400">Crie e gerencie as categorias de eventos da plataforma</p>
        </div>
        <button wire:click="showCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nova Categoria
        </button>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <!-- Categories List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Categoria</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Slug</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Eventos</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Status</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-900 dark:text-white">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    @if($category->icon)
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3" style="background-color: {{ $category->color }}">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                        </svg>
                                    </div>
                                    @else
                                    <div class="w-8 h-8 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</div>
                                        @if($category->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($category->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $category->slug }}</code>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $category->active_events_count }} eventos</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($category->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Ativa
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Inativa
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="showEditModal({{ $category->id }})" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $category->id }})" class="text-red-600 hover:text-red-900">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma categoria encontrada</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Comece criando sua primeira categoria de eventos.</p>
                <button wire:click="showCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center mx-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Criar Primeira Categoria
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $editingId ? 'Editar Categoria' : 'Nova Categoria' }}
                </h2>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="saveCategory" class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome da Categoria</label>
                    <input
                        id="name"
                        type="text"
                        wire:model="name"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex: Shows e Música"
                        required />
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug (URL)</label>
                    <input
                        id="slug"
                        type="text"
                        wire:model="slug"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex: shows-musica" />
                    <p class="mt-1 text-sm text-gray-500">Deixe em branco para gerar automaticamente</p>
                    @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                    <textarea
                        id="description"
                        wire:model="description"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        rows="3"
                        placeholder="Descreva brevemente esta categoria..."></textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color and Icon -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cor</label>
                        <div class="mt-1 flex items-center space-x-2">
                            <input
                                type="color"
                                wire:model="color"
                                class="w-12 h-10 rounded border border-gray-300 dark:border-gray-600" />
                            <input
                                type="text"
                                wire:model="color"
                                class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="#3B82F6" />
                        </div>
                        @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ícone (opcional)</label>
                        <input
                            id="icon"
                            type="text"
                            wire:model="icon"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ex: music, calendar, star" />
                        <p class="mt-1 text-sm text-gray-500">Nome do ícone (Heroicons)</p>
                        @error('icon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            wire:model="is_active"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Categoria ativa</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-500">Categorias inativas não aparecem na criação de eventos</p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" wire:click="$set('showModal', false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $editingId ? 'Atualizar' : 'Criar' }} Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
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
                        Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <button wire:click="$set('confirmingDelete', false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Cancelar
                </button>
                <button wire:click="deleteCategory" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Excluir Categoria
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
