<div>
    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Busca -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nome do evento..." class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Categoria -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                <select wire:model.live="categoryFilter" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Cidade -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cidade</label>
                <select wire:model.live="cityFilter" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select wire:model.live="stateFilter" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    @foreach($states as $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Data -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data</label>
                <select wire:model.live="dateFilter" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Qualquer data</option>
                    <option value="today">Hoje</option>
                    <option value="tomorrow">Amanhã</option>
                    <option value="this_week">Esta semana</option>
                    <option value="this_month">Este mês</option>
                </select>
            </div>
        </div>

        <!-- Botão Limpar Filtros -->
        @if($search || $categoryFilter || $cityFilter || $stateFilter || $dateFilter)
        <div class="mt-4">
            <button wire:click="clearFilters" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                Limpar filtros
            </button>
        </div>
        @endif
    </div>

    <!-- Resultados -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ $this->events->total() }} evento{{ $this->events->total() != 1 ? 's' : '' }} encontrado{{ $this->events->total() != 1 ? 's' : '' }}
            </h2>
        </div>
    </div>

    <!-- Grid de Eventos -->
    @if($this->events->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($this->events as $event)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <!-- Imagem do Evento -->
            <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-700">
                @if($event->hasImage())
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Conteúdo do Card -->
            <div class="p-4">
                <!-- Categoria -->
                <div class="mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $event->category->name }}
                    </span>
                </div>

                <!-- Título -->
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                    <a href="{{ route('public.event.details', $event->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ $event->title }}
                    </a>
                </h3>

                <!-- Descrição -->
                @if($event->short_description)
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                    {{ $event->short_description }}
                </p>
                @endif

                <!-- Data e Local -->
                <div class="space-y-1 mb-3">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $event->start_date->format('d/m/Y H:i') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $event->city }}, {{ $event->state }}
                    </div>
                </div>

                <!-- Preços -->
                @if($event->tickets->count() > 0)
                <div class="mb-3">
                    @php
                        $minPrice = $event->tickets->where('is_active', true)->min('price');
                        $maxPrice = $event->tickets->where('is_active', true)->max('price');
                    @endphp
                    @if($minPrice == $maxPrice)
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">
                            R$ {{ number_format($minPrice, 2, ',', '.') }}
                        </span>
                    @else
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">
                            R$ {{ number_format($minPrice, 2, ',', '.') }} - R$ {{ number_format($maxPrice, 2, ',', '.') }}
                        </span>
                    @endif
                </div>
                @endif

                <!-- Botão Ver Detalhes -->
                <a href="{{ route('public.event.details', $event->slug) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                    Ver Detalhes
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="mt-8">
        {{ $this->events->links() }}
    </div>

    @else
    <!-- Estado Vazio -->
    <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 4h6"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum evento encontrado</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-4">
            Tente ajustar os filtros ou volte mais tarde.
        </p>
        @if($search || $categoryFilter || $cityFilter || $stateFilter || $dateFilter)
        <button wire:click="clearFilters" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Limpar Filtros
        </button>
        @endif
    </div>
    @endif
</div>
