<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form wire:submit="save">
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título do
                                Evento</label>
                            <input type="text" id="title" wire:model="event.title"
                                class="w-full px-4 py-2 border rounded-lg @error('event.title') border-red-500 @enderror">
                            @error('event.title')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                            <textarea id="description" wire:model="event.description" rows="5"
                                class="w-full px-4 py-2 border rounded-lg @error('event.description') border-red-500 @enderror"></textarea>
                            @error('event.description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Local</label>
                            <input type="text" id="location" wire:model="event.location"
                                class="w-full px-4 py-2 border rounded-lg @error('event.location') border-red-500 @enderror">
                            @error('event.location')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Data de
                                    Início</label>
                                <input type="datetime-local" id="start_date" wire:model="event.start_date"
                                    class="w-full px-4 py-2 border rounded-lg @error('event.start_date') border-red-500 @enderror">
                                @error('event.start_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Data de
                                    Término</label>
                                <input type="datetime-local" id="end_date" wire:model="event.end_date"
                                    class="w-full px-4 py-2 border rounded-lg @error('event.end_date') border-red-500 @enderror">
                                @error('event.end_date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="max_tickets" class="block text-sm font-medium text-gray-700 mb-1">Limite de
                                Ingressos (opcional)</label>
                            <input type="number" id="max_tickets" wire:model="event.max_tickets" min="0"
                                class="w-full px-4 py-2 border rounded-lg @error('event.max_tickets') border-red-500 @enderror">
                            @error('event.max_tickets')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">Imagem de
                                Capa</label>
                            <input type="file" id="cover_image" wire:model="coverImage" accept="image/*"
                                class="w-full px-4 py-2 border rounded-lg @error('coverImage') border-red-500 @enderror">
                            @error('coverImage')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror

                            @if ($isEditing && $event->cover_image)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Imagem atual:</p>
                                    <img src="{{ asset('storage/' . $event->cover_image) }}" alt="Capa do evento"
                                        class="mt-2 h-40 object-cover rounded">
                                </div>
                            @endif

                            @if ($coverImage)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Preview:</p>
                                    <img src="{{ $coverImage->temporaryUrl() }}" alt="Preview"
                                        class="mt-2 h-40 object-cover rounded">
                                </div>
                            @endif
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="event.is_published"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Publicar evento</span>
                            </label>
                        </div>

                        @if ($isEditing)
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-700 mb-4">Ingressos</h3>

                                @if ($event->tickets->count() > 0)
                                    <div class="space-y-4">
                                        @foreach ($event->tickets as $ticket)
                                            <div class="border rounded-lg p-4 flex justify-between items-center">
                                                <div>
                                                    <h4 class="font-medium">{{ $ticket->name }}</h4>
                                                    <p class="text-gray-600">R$
                                                        {{ number_format($ticket->price, 2, ',', '.') }} -
                                                        {{ $ticket->getTicketsRemaining() }} disponíveis</p>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('organizer.ticket.edit', ['eventId' => $event->id, 'ticketId' => $ticket->id]) }}"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                                        Editar
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-600">Nenhum ingresso criado ainda.</p>
                                @endif

                                <div class="mt-4">
                                    <a href="{{ route('organizer.ticket.create', $event->id) }}"
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded inline-block">
                                        Adicionar Ingresso
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <a href="{{ route('organizer.events') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                {{ $isEditing ? 'Atualizar Evento' : 'Criar Evento' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
