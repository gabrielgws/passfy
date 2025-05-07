<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form wire:submit="save">
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome do
                                Ingresso</label>
                            <input type="text" id="name" wire:model="ticket.name"
                                class="w-full px-4 py-2 border rounded-lg @error('ticket.name') border-red-500 @enderror">
                            @error('ticket.name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição
                                (opcional)</label>
                            <textarea id="description" wire:model="ticket.description" rows="3"
                                class="w-full px-4 py-2 border rounded-lg @error('ticket.description') border-red-500 @enderror"></textarea>
                            @error('ticket.description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Preço
                                    (R$)</label>
                                <input type="number" id="price" wire:model="ticket.price" step="0.01"
                                    min="0"
                                    class="w-full px-4 py-2 border rounded-lg @error('ticket.price') border-red-500 @enderror">
                                @error('ticket.price')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="quantity_available"
                                    class="block text-sm font-medium text-gray-700 mb-1">Quantidade Disponível</label>
                                <input type="number" id="quantity_available" wire:model="ticket.quantity_available"
                                    min="1"
                                    class="w-full px-4 py-2 border rounded-lg @error('ticket.quantity_available') border-red-500 @enderror">
                                @error('ticket.quantity_available')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="sale_starts_at" class="block text-sm font-medium text-gray-700 mb-1">Início
                                    das Vendas (opcional)</label>
                                <input type="datetime-local" id="sale_starts_at" wire:model="ticket.sale_starts_at"
                                    class="w-full px-4 py-2 border rounded-lg @error('ticket.sale_starts_at') border-red-500 @enderror">
                                @error('ticket.sale_starts_at')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="sale_ends_at" class="block text-sm font-medium text-gray-700 mb-1">Término
                                    das Vendas (opcional)</label>
                                <input type="datetime-local" id="sale_ends_at" wire:model="ticket.sale_ends_at"
                                    class="w-full px-4 py-2 border rounded-lg @error('ticket.sale_ends_at') border-red-500 @enderror">
                                @error('ticket.sale_ends_at')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <a href="{{ route('organizer.event.edit', $event->id) }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                {{ $isEditing ? 'Atualizar Ingresso' : 'Criar Ingresso' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
