<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ $editingCategoryId ? 'Editar Categoria' : 'Nova Categoria' }}
            </h2>
            <form wire:submit.prevent="save" class="mt-4 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input
                        type="text"
                        id="name"
                        wire:model="name"
                        class="text-black mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                    <textarea
                        id="description"
                        wire:model="description"
                        rows="3"
                        class="text-black mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('description') border-red-500 @enderror"></textarea>
                    @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    @if($editingCategoryId)
                    <button
                        type="button"
                        wire:click="cancel"
                        class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancelar
                    </button>
                    @endif
                    <button
                        type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ $editingCategoryId ? 'Atualizar' : 'Criar' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Categorias Existentes</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eventos</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $category->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $category->events_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button
                                    wire:click="edit({{ $category->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Editar
                                </button>
                                <button
                                    wire:click="delete({{ $category->id }})"
                                    wire:confirm="Tem certeza que deseja excluir esta categoria?"
                                    class="text-red-600 hover:text-red-900">
                                    Excluir
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
