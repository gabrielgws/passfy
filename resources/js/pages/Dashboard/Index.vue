<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Ticket, DollarSign, TrendingUp, Plus } from 'lucide-vue-next';
import { computed } from 'vue';

interface Stats {
    total_events: number;
    active_events: number;
    total_tickets_sold: number;
    total_revenue: number;
}

interface Order {
    id: number;
    order_number: string;
    total: string;
    created_at: string;
    user: {
        name: string;
        email: string;
    };
    event: {
        title: string;
    };
    tickets: Array<any>;
}

const props = defineProps<{
    stats: Stats;
    recentOrders: Order[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const formatCurrency = (value: number | string) => {
    const num = typeof value === 'string' ? parseFloat(value) : value;
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(num);
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header com ação -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Dashboard</h1>
                    <p class="text-gray-500 mt-1">Acompanhe o desempenho dos seus eventos</p>
                </div>
                <Link :href="route('events.create')"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
                <Plus class="h-4 w-4" />
                Criar Evento
                </Link>
            </div>

            <!-- Cards de estatísticas -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Total de Eventos -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-950 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Eventos</p>
                            <p class="text-3xl font-bold mt-2">{{ stats.total_events }}</p>
                        </div>
                        <div class="rounded-lg bg-indigo-100 p-3 dark:bg-indigo-900/20">
                            <Calendar class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
                        </div>
                    </div>
                </div>

                <!-- Eventos Ativos -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-950 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Eventos Ativos</p>
                            <p class="text-3xl font-bold mt-2">{{ stats.active_events }}</p>
                        </div>
                        <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/20">
                            <TrendingUp class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                </div>

                <!-- Ingressos Vendidos -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-950 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ingressos Vendidos</p>
                            <p class="text-3xl font-bold mt-2">{{ stats.total_tickets_sold }}</p>
                        </div>
                        <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900/20">
                            <Ticket class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                    </div>
                </div>

                <!-- Receita Total -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-950 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Receita Total</p>
                            <p class="text-3xl font-bold mt-2">{{ formatCurrency(stats.total_revenue) }}</p>
                        </div>
                        <div class="rounded-lg bg-yellow-100 p-3 dark:bg-yellow-900/20">
                            <DollarSign class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="grid gap-6 md:grid-cols-3">
                <Link :href="route('dashboard.events')"
                    class="rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-shadow dark:bg-gray-950 dark:border-gray-800">
                <h3 class="font-semibold text-lg mb-2">Meus Eventos</h3>
                <p class="text-gray-500 text-sm dark:text-gray-400">Gerencie todos os seus eventos em um só lugar</p>
                </Link>

                <Link :href="route('tickets.scanner')"
                    class="rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-shadow dark:bg-gray-950 dark:border-gray-800">
                <h3 class="font-semibold text-lg mb-2">Validar Ingressos</h3>
                <p class="text-gray-500 text-sm dark:text-gray-400">Escaneie QR codes para validar entradas</p>
                </Link>

                <Link :href="route('dashboard.analytics')"
                    class="rounded-xl border bg-white p-6 shadow-sm hover:shadow-md transition-shadow dark:bg-gray-950 dark:border-gray-800">
                <h3 class="font-semibold text-lg mb-2">Relatórios</h3>
                <p class="text-gray-500 text-sm dark:text-gray-400">Análises detalhadas de vendas e desempenho</p>
                </Link>
            </div>

            <!-- Pedidos Recentes -->
            <div class="rounded-xl border bg-white shadow-sm dark:bg-gray-950 dark:border-gray-800">
                <div class="p-6 border-b dark:border-gray-800">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold">Vendas Recentes</h2>
                        <Link :href="route('dashboard.orders')"
                            class="text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                        Ver todas →
                        </Link>
                    </div>
                </div>

                <div v-if="recentOrders.length > 0" class="overflow-x-auto">
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
                                    Comprador
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
                                    Data
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            <tr v-for="order in recentOrders.slice(0, 5)" :key="order.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    {{ order.order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ order.event.title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div>
                                        <div class="font-medium">{{ order.user.name }}</div>
                                        <div class="text-gray-500 text-xs">{{ order.user.email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ order.tickets.length }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    {{ formatCurrency(order.total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(order.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="p-12 text-center">
                    <Ticket class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Nenhuma venda ainda</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Comece criando um evento e divulgando para seus clientes.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
