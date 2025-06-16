<!-- resources/js/Pages/Dashboard/EventDetails.vue -->
<template>
    <AppLayout>

        <Head :title="`Dashboard - ${event.title}`" />

        <div class="min-h-screen bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ event.title }}</h1>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ formatDate(event.start_date) }} • {{ event.location }}
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0 space-x-3">
                            <Link :href="route('events.edit', event.id)"
                                class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <Pencil class="h-4 w-4 mr-1" />
                            Editar
                            </Link>
                            <Link :href="route('tickets.scanner')"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            <QrCode class="h-4 w-4 mr-1" />
                            Validar Ingressos
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total de Ingressos
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                                {{ stats.total_tickets }}
                            </dd>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Vendidos
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                                {{ stats.tickets_sold }}
                            </dd>
                            <dd class="mt-1 text-sm text-gray-600">
                                {{ ((stats.tickets_sold / stats.total_tickets) * 100).toFixed(0) }}% vendido
                            </dd>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Disponíveis
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                                {{ stats.tickets_available }}
                            </dd>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Receita Total
                            </dt>
                            <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                                R$ {{ formatPrice(stats.total_revenue) }}
                            </dd>
                            <dd class="mt-1 text-sm text-gray-600">
                                {{ stats.total_orders }} pedidos
                            </dd>
                        </div>
                    </div>
                </div>

                <!-- Tipos de Ingresso -->
                <div class="mt-8 bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="sm:flex sm:items-center sm:justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Tipos de Ingresso</h3>
                            <button @click="showAddTicketModal = true"
                                class="mt-3 sm:mt-0 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                <Plus class="h-4 w-4 mr-1" />
                                Adicionar
                            </button>
                        </div>

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Tipo
                                        </th>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Preço
                                        </th>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Quantidade
                                        </th>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Vendidos
                                        </th>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Status
                                        </th>
                                        <th class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Ações</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="ticketType in event.ticket_types" :key="ticketType.id">
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                            {{ ticketType.name }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                            R$ {{ formatPrice(ticketType.price) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                            {{ ticketType.quantity }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                            {{ ticketType.sold }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                                :class="{
                                                    'bg-green-100 text-green-800': ticketType.is_active,
                                                    'bg-gray-100 text-gray-800': !ticketType.is_active
                                                }">
                                                {{ ticketType.is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td
                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <button @click="editTicketType(ticketType)"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                Editar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pedidos Recentes -->
                <div class="mt-8 bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pedidos Recentes</h3>

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Pedido
                                        </th>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Comprador
                                        </th>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Ingressos
                                        </th>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Total
                                        </th>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Status
                                        </th>
                                        <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Data
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="order in event.orders" :key="order.id">
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                            {{ order.order_number }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                            {{ order.buyer_info.name }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                            {{ order.tickets.length }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
                                            R$ {{ formatPrice(order.total) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                                :class="{
                                                    'bg-green-100 text-green-800': order.status === 'paid',
                                                    'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                                    'bg-red-100 text-red-800': order.status === 'cancelled'
                                                }">
                                                {{ getStatusLabel(order.status) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ formatDateTime(order.created_at) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de adicionar/editar tipo de ingresso -->
        <Modal :show="showAddTicketModal" @close="closeTicketModal">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ editingTicketType ? 'Editar' : 'Adicionar' }} Tipo de Ingresso
                </h3>

                <form @submit.prevent="saveTicketType" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <input v-model="ticketForm.name" type="text" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea v-model="ticketForm.description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Preço</label>
                            <input v-model="ticketForm.price" type="number" step="0.01" min="0" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantidade</label>
                            <input v-model="ticketForm.quantity" type="number" min="1" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mínimo por pedido</label>
                            <input v-model="ticketForm.min_per_order" type="number" min="1" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Máximo por pedido</label>
                            <input v-model="ticketForm.max_per_order" type="number" min="1" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" @click="closeTicketModal"
                            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import { Plus, Pencil, QrCode } from 'lucide-vue-next';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

const props = defineProps<{
    event: any;
    stats: any;
}>();

const showAddTicketModal = ref(false);
const editingTicketType = ref<any>(null);

const ticketForm = useForm({
    name: '',
    description: '',
    price: 0,
    quantity: 1,
    min_per_order: 1,
    max_per_order: 10,
    is_active: true,
});

const editTicketType = (ticketType: any) => {
    editingTicketType.value = ticketType;
    ticketForm.name = ticketType.name;
    ticketForm.description = ticketType.description || '';
    ticketForm.price = parseFloat(ticketType.price);
    ticketForm.quantity = ticketType.quantity;
    ticketForm.min_per_order = ticketType.min_per_order;
    ticketForm.max_per_order = ticketType.max_per_order;
    ticketForm.is_active = ticketType.is_active;
    showAddTicketModal.value = true;
};

const closeTicketModal = () => {
    showAddTicketModal.value = false;
    editingTicketType.value = null;
    ticketForm.reset();
};

const saveTicketType = () => {
    if (editingTicketType.value) {
        ticketForm.put(route('tickets.update', [props.event.id, editingTicketType.value.id]), {
            onSuccess: () => closeTicketModal(),
        });
    } else {
        ticketForm.post(route('tickets.store', props.event.id), {
            onSuccess: () => closeTicketModal(),
        });
    }
};

const formatDate = (date: string) => {
    return format(new Date(date), "d 'de' MMMM 'de' yyyy", { locale: ptBR });
};

const formatDateTime = (date: string) => {
    return format(new Date(date), "dd/MM/yyyy HH:mm", { locale: ptBR });
};

const formatPrice = (price: string | number) => {
    return parseFloat(price.toString()).toFixed(2).replace('.', ',');
};

const getStatusLabel = (status: string) => {
    const labels: Record<string, string> = {
        pending: 'Pendente',
        paid: 'Pago',
        cancelled: 'Cancelado',
        refunded: 'Reembolsado',
    };
    return labels[status] || status;
};
</script>
