<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, MapPin, Ticket, Eye, Edit, Plus, MoreVertical } from 'lucide-vue-next';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';

interface Event {
    id: number;
    title: string;
    slug: string;
    location: string;
    city: string;
    state: string;
    start_date: string;
    end_date: string;
    status: string;
    orders_count: number;
    ticket_types_sum_sold: number;
    created_at: string;
}

const props = defineProps<{
    events: {
        data: Event[];
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        current_page: number;
        last_page: number;
        total: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Meus Eventos',
        href: '/dashboard/events',
    },
];

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getStatusBadge = (status: string) => {
    const badges = {
        draft: { class: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300', label: 'Rascunho' },
        published: { class: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400', label: 'Publicado' },
        cancelled: { class: 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400', label: 'Cancelado' },
        finished: { class: 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400', label: 'Finalizado' }
    };
    return badges[status] || badges.draft;
};
</script>

<template>

    <Head title="Meus Eventos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Meus Eventos</h1>
                    <p class="text-gray-500 mt-1">Gerencie todos os seus eventos</p>
                </div>
                <Link :href="route('events.create')"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                <Plus class="h-4 w-4" />
                Criar Evento
                </Link>
            </div>

            <!-- Lista de Eventos -->
            <div class="rounded-xl border bg-white shadow-sm dark:bg-gray-950 dark:border-gray-800">
                <div v-if="events.data.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b bg-gray-50 dark:bg-gray-900 dark:border-gray-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Evento
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Data
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Local
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Vendas
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Status
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Ações</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            <tr v-for="event in events.data" :key="event.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ event.title }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Criado em {{ formatDate(event.created_at) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                        <Calendar class="h-4 w-4" />
                                        {{ formatDate(event.start_date) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                        <MapPin class="h-4 w-4" />
                                        {{ event.city }}, {{ event.state }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <div class="flex items-center gap-1">
                                                <Ticket class="h-4 w-4 text-gray-400" />
                                                <span class="font-medium">{{ event.ticket_types_sum_sold || 0 }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500">ingressos</div>
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ event.orders_count || 0 }}</div>
                                            <div class="text-xs text-gray-500">pedidos</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                        :class="getStatusBadge(event.status).class">
                                        {{ getStatusBadge(event.status).label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Menu as="div" class="relative inline-block text-left">
                                        <MenuButton
                                            class="flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <MoreVertical class="h-5 w-5" />
                                        </MenuButton>

                                        <transition enter-active-class="transition ease-out duration-100"
                                            enter-from-class="transform opacity-0 scale-95"
                                            enter-to-class="transform opacity-100 scale-100"
                                            leave-active-class="transition ease-in duration-75"
                                            leave-from-class="transform opacity-100 scale-100"
                                            leave-to-class="transform opacity-0 scale-95">
                                            <MenuItems
                                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-900 dark:ring-gray-800">
                                                <div class="py-1">
                                                    <MenuItem v-slot="{ active }">
                                                    <Link :href="route('dashboard.events.show', event.id)" :class="[
                                                        active ? 'bg-gray-100 dark:bg-gray-800' : '',
                                                        'flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300'
                                                    ]">
                                                    <Eye class="h-4 w-4" />
                                                    Visualizar
                                                    </Link>
                                                    </MenuItem>
                                                    <MenuItem v-slot="{ active }">
                                                    <Link :href="route('events.edit', event.id)" :class="[
                                                        active ? 'bg-gray-100 dark:bg-gray-800' : '',
                                                        'flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300'
                                                    ]">
                                                    <Edit class="h-4 w-4" />
                                                    Editar
                                                    </Link>
                                                    </MenuItem>
                                                </div>
                                            </MenuItems>
                                        </transition>
                                    </Menu>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="p-12 text-center">
                    <Calendar class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Nenhum evento ainda</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Comece criando seu primeiro evento.
                    </p>
                    <div class="mt-6">
                        <Link :href="route('events.create')"
                            class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <Plus class="h-4 w-4" />
                        Criar Evento
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Paginação -->
            <div v-if="events.last_page > 1" class="flex justify-center">
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <Link v-for="link in events.links" :key="link.label" :href="link.url" v-html="link.label"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 dark:ring-gray-700"
                        :class="{
                            'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active,
                            'text-gray-900 hover:bg-gray-50 dark:text-gray-100 dark:hover:bg-gray-800': !link.active && link.url,
                            'cursor-not-allowed opacity-50': !link.url
                        }" />
                </nav>
            </div>
        </div>
    </AppLayout>
</template>
