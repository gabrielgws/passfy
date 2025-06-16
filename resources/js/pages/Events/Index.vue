<!-- resources/js/Pages/Events/Index.vue -->
<template>
    <AppLayout>

        <Head title="Eventos" />

        <div class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
                <div class="sm:flex sm:items-baseline sm:justify-between">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">Eventos</h1>

                    <div class="mt-4 sm:mt-0">
                        <label for="category" class="sr-only">Categoria</label>
                        <select id="category" v-model="selectedCategory"
                            class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                            <option value="">Todas as categorias</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div
                    class="mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                    <div v-for="event in events.data" :key="event.id">
                        <Link :href="route('events.show', event.slug)" class="group">
                        <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-200">
                            <img v-if="event.banner_image" :src="`/storage/${event.banner_image}`" :alt="event.title"
                                class="h-full w-full object-cover object-center group-hover:opacity-75 transition-opacity" />
                            <div v-else class="h-full w-full bg-gradient-to-br from-indigo-500 to-purple-600" />
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">{{ formatDate(event.start_date) }}</p>
                            <h3 class="mt-1 text-lg font-medium text-gray-900">{{ event.title }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ event.location }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">
                                    A partir de R$ {{ getLowestPrice(event) }}
                                </p>
                                <span
                                    class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800">
                                    {{ event.category.name }}
                                </span>
                            </div>
                        </div>
                        </Link>
                    </div>
                </div>

                <!-- Paginação -->
                <div v-if="events.last_page > 1" class="mt-12 flex justify-center">
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <Link v-for="link in events.links" :key="link.label" :href="link.url" v-html="link.label"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300"
                            :class="{
                                'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active,
                                'text-gray-900 hover:bg-gray-50 focus:outline-offset-0': !link.active,
                                'pointer-events-none opacity-50': !link.url
                            }" />
                    </nav>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

const props = defineProps<{
    events: {
        data: Array<{
            id: number;
            title: string;
            slug: string;
            banner_image: string | null;
            location: string;
            start_date: string;
            category: {
                id: number;
                name: string;
            };
            ticket_types: Array<{
                id: number;
                price: number;
            }>;
        }>;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        last_page: number;
    };
    categories?: Array<{
        id: number;
        name: string;
    }>;
}>();

const selectedCategory = ref('');

watch(selectedCategory, (value) => {
    router.get(route('events.index'), { category: value }, { preserveState: true });
});

const formatDate = (date: string) => {
    return format(new Date(date), "d 'de' MMMM", { locale: ptBR });
};

const getLowestPrice = (event: any) => {
    if (!event.ticket_types || event.ticket_types.length === 0) return '0,00';
    const prices = event.ticket_types.map((t: any) => parseFloat(t.price));
    return Math.min(...prices).toFixed(2).replace('.', ',');
};
</script>
