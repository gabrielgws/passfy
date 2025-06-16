<!-- resources/js/Pages/Orders/Create.vue -->
<template>
    <AppLayout>

        <Head :title="`Comprar ingressos - ${event.title}`" />

        <div class="bg-gray-50 min-h-screen">
            <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Comprar Ingressos</h1>
                    <p class="mt-2 text-lg text-gray-600">{{ event.title }}</p>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Seleção de ingressos -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Selecione os ingressos</h2>

                        <div class="space-y-4">
                            <div v-for="(ticket, index) in form.tickets" :key="ticket.ticket_type_id"
                                class="border rounded-lg p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ ticket.ticket_type.name }}</h3>
                                        <p class="text-sm text-gray-500">R$ {{ formatPrice(ticket.ticket_type.price) }}
                                        </p>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <button type="button" @click="decrementQuantity(index)"
                                            :disabled="ticket.quantity <= 0"
                                            class="rounded-full p-1 hover:bg-gray-100 disabled:opacity-50">
                                            <Minus class="h-5 w-5" />
                                        </button>
                                        <span class="w-12 text-center font-medium">{{ ticket.quantity }}</span>
                                        <button type="button" @click="incrementQuantity(index)"
                                            :disabled="ticket.quantity >= ticket.ticket_type.max_per_order || ticket.quantity >= ticket.ticket_type.available"
                                            class="rounded-full p-1 hover:bg-gray-100 disabled:opacity-50">
                                            <Plus class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>

                                <!-- Informações dos participantes -->
                                <div v-if="ticket.quantity > 0" class="space-y-3 mt-4 pt-4 border-t">
                                    <p class="text-sm font-medium text-gray-700">Informações dos participantes</p>
                                    <div v-for="(attendee, attendeeIndex) in ticket.attendees" :key="attendeeIndex"
                                        class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                Nome do participante {{ attendeeIndex + 1 }}
                                            </label>
                                            <input v-model="attendee.name" type="text" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                E-mail
                                            </label>
                                            <input v-model="attendee.email" type="email" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                CPF (opcional)
                                            </label>
                                            <input v-model="attendee.document" type="text"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do comprador -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Informações do comprador</h2>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Nome completo
                                </label>
                                <input v-model="form.buyer_info.name" type="text" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    E-mail
                                </label>
                                <input v-model="form.buyer_info.email" type="email" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Telefone
                                </label>
                                <input v-model="form.buyer_info.phone" type="tel" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    CPF
                                </label>
                                <input v-model="form.buyer_info.document" type="text" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            </div>
                        </div>
                    </div>

                    <!-- Resumo -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Resumo do pedido</h2>

                        <div class="space-y-2">
                            <div v-for="ticket in form.tickets.filter(t => t.quantity > 0)" :key="ticket.ticket_type_id"
                                class="flex justify-between text-sm">
                                <span>{{ ticket.ticket_type.name }} x {{ ticket.quantity }}</span>
                                <span>R$ {{ formatPrice(ticket.ticket_type.price * ticket.quantity) }}</span>
                            </div>

                            <div class="border-t pt-2">
                                <div class="flex justify-between text-sm">
                                    <span>Subtotal</span>
                                    <span>R$ {{ formatPrice(subtotal) }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>Taxa de serviço ({{ event.service_fee_percentage }}%)</span>
                                    <span>R$ {{ formatPrice(serviceFee) }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-semibold mt-2 pt-2 border-t">
                                    <span>Total</span>
                                    <span>R$ {{ formatPrice(total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <Link :href="route('events.show', event.slug)"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancelar
                        </Link>
                        <button type="submit" :disabled="form.processing || totalQuantity === 0"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
                            Continuar para pagamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { computed, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Plus, Minus } from 'lucide-vue-next';

const props = defineProps<{
    event: {
        id: number;
        title: string;
        slug: string;
        service_fee_percentage: number;
        ticket_types: Array<{
            id: number;
            name: string;
            price: number;
            min_per_order: number;
            max_per_order: number;
            available: number;
        }>;
    };
}>();

const form = useForm({
    tickets: props.event.ticket_types.map(ticketType => ({
        ticket_type_id: ticketType.id,
        ticket_type: ticketType,
        quantity: 0,
        attendees: [],
    })),
    buyer_info: {
        name: '',
        email: '',
        phone: '',
        document: '',
    },
});

// Atualizar participantes quando a quantidade mudar
watch(
    () => form.tickets,
    (tickets) => {
        tickets.forEach((ticket) => {
            const currentCount = ticket.attendees.length;
            if (ticket.quantity > currentCount) {
                // Adicionar novos participantes
                for (let i = currentCount; i < ticket.quantity; i++) {
                    ticket.attendees.push({
                        name: '',
                        email: '',
                        document: '',
                    });
                }
            } else if (ticket.quantity < currentCount) {
                // Remover participantes extras
                ticket.attendees = ticket.attendees.slice(0, ticket.quantity);
            }
        });
    },
    { deep: true }
);

const totalQuantity = computed(() => {
    return form.tickets.reduce((sum, ticket) => sum + ticket.quantity, 0);
});

const subtotal = computed(() => {
    return form.tickets.reduce((sum, ticket) => {
        return sum + (ticket.ticket_type.price * ticket.quantity);
    }, 0);
});

const serviceFee = computed(() => {
    return subtotal.value * (props.event.service_fee_percentage / 100);
});

const total = computed(() => {
    return subtotal.value + serviceFee.value;
});

const incrementQuantity = (index: number) => {
    const ticket = form.tickets[index];
    const max = Math.min(ticket.ticket_type.max_per_order, ticket.ticket_type.available);
    if (ticket.quantity < max) {
        ticket.quantity++;
    }
};

const decrementQuantity = (index: number) => {
    const ticket = form.tickets[index];
    if (ticket.quantity > 0) {
        ticket.quantity--;
    }
};

const formatPrice = (value: number) => {
    return value.toFixed(2).replace('.', ',');
};

const submit = () => {
    form.post(route('orders.store', props.event.id));
};
</script>
