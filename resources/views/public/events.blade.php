<x-layouts.public title="Eventos - Passfy">
    <div class="bg-white dark:bg-gray-800">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-700 dark:to-blue-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white mb-4">
                        Descubra Eventos Incríveis
                    </h1>
                    <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                        Encontre os melhores eventos perto de você. Shows, workshops, palestras e muito mais.
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @livewire('public.event-list')
            </div>
        </div>
    </div>
</x-layouts.public>
