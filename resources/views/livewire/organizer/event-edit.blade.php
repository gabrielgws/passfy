<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-semibold mb-6">Editar Evento</h1>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <form wire:submit.prevent="update" class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Título do Evento</label>
                <input
                    type="text"
                    id="title"
                    wire:model="title"
                    class="text-black mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('title') border-red-500 @enderror"
                    placeholder="Digite o título do evento">
                @error('title')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                <textarea
                    id="description"
                    wire:model="description"
                    rows="4"
                    class="text-black mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('description') border-red-500 @enderror"
                    placeholder="Descrição detalhada do evento"></textarea>
                @error('description')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Local</label>
                <input
                    type="text"
                    id="location"
                    wire:model="location"
                    class="text-black mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('location') border-red-500 @enderror"
                    placeholder="Local do evento">
                @error('location')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Data de Início</label>
                    <input
                        type="datetime-local"
                        id="start_date"
                        wire:model="start_date"
                        class="text-black mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Data de Término</label>
                    <input
                        type="datetime-local"
                        id="end_date"
                        wire:model="end_date"
                        class="text-black mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="max_tickets" class="block text-sm font-medium text-gray-700">Número Máximo de Ingressos (opcional)</label>
                <input
                    type="number"
                    id="max_tickets"
                    wire:model="max_tickets"
                    class="text-black mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('max_tickets') border-red-500 @enderror"
                    placeholder="Número máximo de ingressos">
                @error('max_tickets')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="is_published"
                    wire:model="is_published"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_published" class="ml-2 block text-sm text-gray-900">
                    Publicar evento
                </label>
            </div>

            <div>
                <label for="cover_image" class="block text-sm font-medium text-gray-700">Imagem de Capa</label>
                <input
                    type="file"
                    id="cover_image"
                    wire:model="cover_image"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium hover:file:bg-indigo-100 @error('cover_image') border-red-500 @enderror">
                @error('cover_image')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if($cover_image)
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700">Nova Imagem (Pré-visualização):</p>
                    <img src="{{ $cover_image->temporaryUrl() }}" class="mt-2 h-40 w-auto object-cover rounded-md">
                </div>
                @elseif($current_cover_image)
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700">Imagem Atual:</p>
                    <img src="{{ asset('storage/' . $current_cover_image) }}" class="mt-2 h-40 w-auto object-cover rounded-md">
                </div>
                @endif
            </div>

            <div class="flex justify-end">
                <button
                    type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Atualizar Evento
                </button>
            </div>
        </form>
    </div>
</div>
