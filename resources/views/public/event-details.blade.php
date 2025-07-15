<x-layouts.public title="{{ $event->title }} - Passfy">
    <div class="bg-white dark:bg-gray-800">
        <!-- Breadcrumb -->
        <div class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('public.events') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                                Eventos
                            </a>
                        </li>
                        <li>
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </li>
                        <li>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $event->title }}</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Content -->
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @livewire('public.event-details', ['eventId' => $event->id])
            </div>
        </div>
    </div>
</x-layouts.public>
