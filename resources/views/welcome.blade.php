<div>
    <!-- Hero Section -->
    <div class="bg-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-4xl font-bold mb-4">Descubra eventos incríveis</h1>
            <div class="max-w-2xl">
                <form wire:submit.prevent>
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.live="search"
                            placeholder="Busque por eventos, artistas ou locais"
                            class="w-full px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-indigo-500">
                        <div class="absolute right-3 top-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Categories -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Categorias</h2>
            <div class="flex space-x-4 overflow-x-auto pb-4">
                <button
                    wire:click="filterByCategory(null)"
                    class="px-4 py-2 rounded-full {{ $selectedCategory === null ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Todos
                </button>
                @foreach($categories as $category)
                <button
                    wire:click="filterByCategory({{ $category->id }})"
                    class="px-4 py-2 rounded-full {{ $selectedCategory == $category->id ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md overflow-hidden">
                <img src="{{ asset('storage/' . $event->cover_image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-500">{{ $event->category->name }}</span>
                        <span class="text-sm text-gray-500">{{ $event->start_date->format('d/m/Y H:i') }}</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $event->description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-indigo-600">
                            @if($event->tickets->isNotEmpty())
                            R$ {{ number_format($event->tickets->min('price'), 2, ',', '.') }}
                            @else
                            Gratuito
                            @endif
                        </span>
                        <a
                            href="{{ route('events.show', $event) }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Ver detalhes
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $events->links() }}
        </div>
    </div>
</div>
