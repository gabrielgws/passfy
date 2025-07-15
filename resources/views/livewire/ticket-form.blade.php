<form wire:submit.prevent="save" class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
        {{ $ticketId ? 'Editar Ingresso' : 'Novo Ingresso' }}
    </h2>

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome do Ingresso</label>
        <input id="name" type="text" wire:model.defer="name" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required maxlength="100" placeholder="Ex: Inteira, Meia, VIP, etc." />
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
        <textarea id="description" wire:model.defer="description" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" rows="3" maxlength="500" placeholder="Descrição detalhada do ingresso..."></textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preço (R$)</label>
            <input id="price" type="number" wire:model.defer="price" step="0.01" min="0" max="999999.99" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required placeholder="0.00" />
            @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantidade Disponível</label>
            <input id="quantity" type="number" wire:model.defer="quantity" min="1" max="999999" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required placeholder="100" />
            @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="min_per_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mínimo por Pedido</label>
            <input id="min_per_order" type="number" wire:model.defer="min_per_order" min="1" max="100" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required placeholder="1" />
            @error('min_per_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="max_per_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Máximo por Pedido</label>
            <input id="max_per_order" type="number" wire:model.defer="max_per_order" min="1" max="100" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="10 (opcional)" />
            @error('max_per_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="sale_start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Início da Venda</label>
            <input id="sale_start_date" type="datetime-local" wire:model.defer="sale_start_date" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" />
            @error('sale_start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="sale_end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fim da Venda</label>
            <input id="sale_end_date" type="datetime-local" wire:model.defer="sale_end_date" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" />
            @error('sale_end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex items-center">
        <input id="is_active" type="checkbox" wire:model.defer="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
        <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Ingresso ativo para venda</label>
    </div>

    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
        <button type="button" wire:click="cancel" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
            Cancelar
        </button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            {{ $ticketId ? 'Atualizar' : 'Criar' }} Ingresso
        </button>
    </div>
</form>
