<!-- resources/js/Pages/Events/Show.vue -->
<template>
    <AppLayout>

        <Head :title="event.title" />

        <div class="bg-white">
            <!-- Banner -->
            <div class="relative h-96 w-full">
                <img v-if="event.banner_image" :src="`/storage/${event.banner_image}`" :alt="event.title"
                    class="h-full w-full object-cover" />
                <div v-else class="h-full w-full bg-gradient-to-br from-indigo-500 to-purple-600" />

                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent" />

                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <div class="mx-auto max-w-7xl">
                        <span
                            class="inline-flex items-center rounded-full bg-white/20 backdrop-blur px-3 py-1 text-sm font-medium text-white">
                            {{ event.category.name }}
                        </span>
                        <h1 class="mt-4 text-4xl font-bold text-white sm:text-5xl">
                            {{ event.title }}
                        </h1>
                    </div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                    <!-- Informações principais -->
                    <div class="lg:col-span-2">
                        <div class="prose prose-lg">
                            <h2>Sobre o evento</h2>
                            <div v-html="event.description"></div>
                        </div>

                        <!-- Galeria -->
                        <div v-if="event.gallery_images && event.gallery_images.length > 0" class="mt-12">
                            <h3 class="text-lg font-medium text-gray-900">Galeria</h3>
                            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                                <img v-for="(image, index) in event.gallery_images" :key="index"
                                    :src="`/storage/${image}`" :alt="`Imagem ${index + 1}`"
                                    class="h-40 w-full rounded-lg object-cover" />
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="mt-8 lg:mt-0">
                        <div class="sticky top-4 space-y-6">
                            <!-- Informações do evento -->
                            <div class="rounded-lg border bg-gray-50 p-6">
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <Calendar class="h-5 w-5 text-gray-400 mt-0.5" />
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Data e hora</p>
                                            <p class="text-sm text-gray-500">
                                                {{ formatDateTime(event.start_date) }}
                                            </p>
                                            <p v-if="event.end_date !== event.start_date" class="text-sm text-gray-500">
                                                até {{ formatDateTime(event.end_date) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <MapPin class="h-5 w-5 text-gray-400 mt-0.5" />
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Local</p>
                                            <p class="text-sm text-gray-500">{{ event.location }}</p>
                                            <p v-if="event.address" class="text-sm text-gray-500">
                                                {{ event.address }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ event.city }}, {{ event.state }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <User class="h-5 w-5 text-gray-400 mt-0.5" />
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Organizador</p>
                                            <p class="text-sm text-gray-500">{{ event.user.name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ingressos -->
                            <div class="rounded-lg border p-6">
                                <h3 class="text-lg font-medium text-gray-900">Ingressos</h3>

                                <div v-if="availableTickets.length > 0" class="mt-4 space-y-3">
                                    <div v-for="ticket in availableTickets" :key="ticket.id"
                                        class="rounded-lg border p-4">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ ticket.name }}</h4>
                                                <p v-if="ticket.description" class="mt-1 text-sm text-gray-500">
                                                    {{ ticket.description }}
                                                </p>
                                            </div>
                                            <p class="text-lg font-semibold text-gray-900">
                                                R$ {{ formatPrice(ticket.price) }}
                                            </p>
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500">
                                            {{ ticket.available }} disponíveis
                                        </p>
                                    </div>

                                    <Link :href="route('orders.create', event.id)"
                                        class="mt-6 w-full inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    Comprar Ingressos
                                    </Link>
                                </div>

                                <div v-else class="mt-4 text-center text-sm text-gray-500">
                                    Ingressos esgotados
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Calendar, MapPin, User } from 'lucide-vue-next';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

interface TicketType {
    id: number;
    name: string;
    description: string | null;
    price: string;
    quantity: number;
    sold: number;
    available: number;
}

interface Event {
    id: number;
    title: string;
    description: string;
    banner_image: string | null;
    gallery_images: string[] | null;
    location: string;
    address: string | null;
    city: string;
    state: string;
    start_date: string;
    end_date: string;
    category: {
        id: number;
        name: string;
    };
    user: {
        id: number;
        name: string;
    };
    ticket_types: TicketType[];
}

const props = defineProps<{
    event: Event;
}>();

const availableTickets = computed(() => {
    return props.event.ticket_types.filter(ticket => ticket.available > 0);
});

const formatDateTime = (date: string) => {
    return format(new Date(date), "d 'de' MMMM 'de' yyyy 'às' HH:mm", { locale: ptBR });
};

const formatPrice = (price: string) => {
    return parseFloat(price).toFixed(2).replace('.', ',');
};
</script>
