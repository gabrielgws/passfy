<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ShoppingBag, Calendar, Ticket, Eye } from 'lucide-vue-next';

interface Order {
    id: number;
    order_number: string;
    status: string;
    total: string;
    created_at: string;
    event: {
        id: number;
        title: string;
        start_date: string;
    };
    tickets: Array<{
        id: number;
    }>;
}

const props = defineProps<{
    orders: {
        data: Order[];
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
        title: 'Meus Pedidos',
        href: '/dashboard/orders',
    },
];

const formatCurrency = (value: string) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(parseFloat(value));
};

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
        pending: { class: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400', label: 'Pendente' },
        paid: { class: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400', label: 'Pago' },
        cancelled: { class: 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400', label: 'Cancelado' },
        refunded: { class: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300', label: 'Reembolsado' }
    };
    return badges[status] || badges.pending;
};
</script>

<template>

    <Head title="Meus Pedidos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold">Meus Pedidos</h1>
                <p class="text-gray-500 mt-1">Acompanhe suas compras de ingressos</p>
            </div>

            <!-- Lista de Pedidos -->
            <div class="rounded-xl border bg-white shadow-sm dark:bg-gray-950 dark:border-gray-800">
                <div v-if="orders.data.length > 0" class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b bg-gray-50 dark:bg-gray-900 dark:border-gray-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Pedido
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Evento
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Data do Evento
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Ingressos
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Total
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
                            <tr v-for="order in orders.data" :key="order.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ order.order_number }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ formatDate(order.created_at) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ order.event.title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                                        <Calendar class="h-4 w-4" />
                                        {{ formatDate(order.event.start_date) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-1">
                                        <Ticket class="h-4 w-4 text-gray-400" />
                                        <span class="font-medium">{{ order.tickets.length }}</span>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ formatCurrency(order.total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold"
                                        :class="getStatusBadge(order.status).class">
                                        {{ getStatusBadge(order.status).label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="route('orders.show', order.id)"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Ver detalhes
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="p-12 text-center">
                    <ShoppingBag class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Nenhum pedido ainda</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Quando você comprar ingressos, eles aparecerão aqui.
                    </p>
                    <div class="mt-6">
                        <Link :href="route('events.index')"
                            class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <Eye class="h-4 w-4" />
                        Explorar Eventos
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Paginação -->
            <div v-if="orders.last_page > 1" class="flex justify-center">
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <Link v-for="link in orders.links" :key="link.label" :href="link.url" v-html="link.label"
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
