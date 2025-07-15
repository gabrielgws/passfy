@php
use Illuminate\Support\Facades\Storage;
@endphp

<form wire:submit.prevent="save" class="space-y-6">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
        {{ $eventId ? 'Editar Evento' : 'Novo Evento' }}
    </h2>

    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
        <input id="title" type="text" wire:model.defer="title" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required maxlength="120" placeholder="Ex: Festival de Música 2025" />
        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Upload de Imagem -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imagem do Evento</label>

        <!-- Preview da imagem atual -->
        @if($currentImage)
        <div class="mb-4">
            <div class="relative inline-block">
                <img src="{{ Storage::url($currentImage) }}" alt="Imagem atual" class="w-32 h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                <button type="button" wire:click="removeImage" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Imagem atual do evento</p>
        </div>
        @endif

        <!-- Preview da nova imagem -->
        @if($image)
        <div class="mb-4">
            <div class="relative inline-block">
                <img src="{{ $image->temporaryUrl() }}" alt="Nova imagem" class="w-32 h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                <button type="button" wire:click="$set('image', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Nova imagem selecionada</p>
        </div>
        @endif

        <!-- Campo de upload -->
        <div class="mt-2">
            <label for="image" class="cursor-pointer">
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-400 dark:hover:border-blue-400 transition-colors">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">Clique para fazer upload</span>
                        ou arraste e solte
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        PNG, JPG, GIF, WEBP até 2MB
                    </p>
                </div>
            </label>
            <input id="image" type="file" wire:model="image" class="hidden" accept="image/*">
        </div>
        @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição curta</label>
        <input id="short_description" type="text" wire:model.defer="short_description" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" maxlength="255" placeholder="Resumo do evento..." />
        @error('short_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição completa</label>
        <textarea id="description" wire:model.defer="description" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" rows="4" placeholder="Detalhes do evento..."></textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
        <select id="category_id" wire:model.defer="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required>
            <option value="">Selecione...</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data/hora início</label>
            <input id="start_date" type="datetime-local" wire:model.defer="start_date" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required />
            @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data/hora fim</label>
            <input id="end_date" type="datetime-local" wire:model.defer="end_date" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required />
            @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Local</label>
        <input id="location" type="text" wire:model.defer="location" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required maxlength="120" placeholder="Ex: Centro de Convenções" />
        @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cidade</label>
            <input id="city" type="text" wire:model.defer="city" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required maxlength="80" />
            @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UF</label>
            <input id="state" type="text" wire:model.defer="state" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required maxlength="2" placeholder="SP" />
            @error('state') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CEP</label>
            <input id="zip_code" type="text" wire:model.defer="zip_code" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" maxlength="10" />
            @error('zip_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select id="status" wire:model.defer="status" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required>
                <option value="draft">Rascunho</option>
                <option value="published">Publicado</option>
                <option value="cancelled">Cancelado</option>
                <option value="finished">Finalizado</option>
            </select>
            @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center mt-6">
            <input id="is_featured" type="checkbox" wire:model.defer="is_featured" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            <label for="is_featured" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Evento em destaque</label>
        </div>
        <div class="flex items-center mt-6">
            <input id="is_free" type="checkbox" wire:model.defer="is_free" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            <label for="is_free" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Evento gratuito</label>
        </div>
    </div>

    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
        <button type="button" wire:click="cancel" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
            Cancelar
        </button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ $eventId ? 'Atualizar' : 'Criar' }} Evento
        </button>
    </div>
</form>
