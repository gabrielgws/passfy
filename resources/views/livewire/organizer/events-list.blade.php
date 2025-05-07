<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="w-1/3">
                            <input wire:model.live="search" type="text" placeholder="Buscar eventos..."
                                class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <a href="{{ route('organizer.event.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            Criar Novo Evento
                        </a>
                    </div>

                    @if ($events->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 bg-gray-100 text-left">Título</th>
                                        <th class="py-3 px-4 bg-gray-100 text-left">Data</th>
                                        <th class="py-3 px-4 bg-gray-100 text-left">Status</th>
                                        <th class="py-3 px-4 bg-gray-100 text-left">Ingressos Vendidos</th>
                                        <th class="py-3 px-4 bg-gray-100 text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr class="border-b">
                                            <td class="py-3 px-4">{{ $event->title }}</td>
                                            <td class="py-3 px-4">
                                                {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="py-3 px-4">
                                                @if ($event->is_published)
                                                    <span
                                                        class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Publicado</span>
                                                @else
                                                    <span
                                                        class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Rascunho</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                {{ $event->tickets->sum(function ($ticket) {
                                                    return $ticket->orderItems->sum('quantity');
                                                }) }}
                                                /
                                                {{ $event->tickets->sum('quantity_available') }}
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('organizer.event.edit', $event) }}"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                                        Editar
                                                    </a>
                                                    <a href="{{ route('organizer.event.scan', $event) }}"
                                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                                        Validar
                                                    </a>
                                                    <button wire:click="deleteEvent({{ $event->id }})"
                                                        wire:confirm="Tem certeza que deseja excluir este evento?"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                                        Excluir
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600">Nenhum evento encontrado.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
