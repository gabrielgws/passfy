<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Estatísticas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2 text-black">Total de Eventos</h3>
                    <p class="text-3xl font-bold text-black">{{ $totalEvents }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2 text-black">Ingressos Vendidos</h3>
                    <p class="text-3xl font-bold text-black">{{ $totalTicketsSold }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2 text-black">Receita Total</h3>
                    <p class="text-3xl font-bold text-black">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</p>
                </div>
            </div>

            <!-- Próximos eventos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Próximos Eventos</h2>
                    <a href="{{ route('organizer.events') }}" class="text-blue-600 hover:text-blue-800">Ver todos</a>
                </div>

                @if($upcomingEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingEvents as $event)
                    <div class="border rounded-lg p-4 flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h3 class="font-medium text-lg text-black">{{ $event->title }}</h3>
                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-2 md:mt-0 flex space-x-2">
                            <a href="{{ route('organizer.event.edit', $event) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Editar</a>
                            <a href="{{ route('organizer.event.scan', $event) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Validar</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-600">Você não tem eventos próximos.</p>
                @endif
            </div>
        </div>
    </div>
</div>
