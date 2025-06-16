<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Calendar, MapPin, Image, DollarSign, Save } from 'lucide-vue-next';
import { ref } from 'vue';

interface Category {
    id: number;
    name: string;
    slug: string;
}

const props = defineProps<{
    categories: Category[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Eventos',
        href: '/dashboard/events',
    },
    {
        title: 'Criar Evento',
        href: '/events/create',
    },
];

const form = useForm({
    category_id: '',
    title: '',
    description: '',
    banner_image: null as File | null,
    gallery_images: [] as File[],
    location: '',
    address: '',
    city: '',
    state: '',
    zip_code: '',
    start_date: '',
    end_date: '',
});

const bannerPreview = ref<string | null>(null);
const galleryPreviews = ref<string[]>([]);

const handleBannerChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.banner_image = target.files[0];

        const reader = new FileReader();
        reader.onload = (e) => {
            bannerPreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(target.files[0]);
    }
};

const handleGalleryChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        form.gallery_images = Array.from(target.files);

        galleryPreviews.value = [];
        Array.from(target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                galleryPreviews.value.push(e.target?.result as string);
            };
            reader.readAsDataURL(file);
        });
    }
};

const submit = () => {
    form.post(route('events.store'), {
        forceFormData: true,
    });
};

// Estados brasileiros
const states = [
    { value: 'AC', label: 'Acre' },
    { value: 'AL', label: 'Alagoas' },
    { value: 'AP', label: 'Amapá' },
    { value: 'AM', label: 'Amazonas' },
    { value: 'BA', label: 'Bahia' },
    { value: 'CE', label: 'Ceará' },
    { value: 'DF', label: 'Distrito Federal' },
    { value: 'ES', label: 'Espírito Santo' },
    { value: 'GO', label: 'Goiás' },
    { value: 'MA', label: 'Maranhão' },
    { value: 'MT', label: 'Mato Grosso' },
    { value: 'MS', label: 'Mato Grosso do Sul' },
    { value: 'MG', label: 'Minas Gerais' },
    { value: 'PA', label: 'Pará' },
    { value: 'PB', label: 'Paraíba' },
    { value: 'PR', label: 'Paraná' },
    { value: 'PE', label: 'Pernambuco' },
    { value: 'PI', label: 'Piauí' },
    { value: 'RJ', label: 'Rio de Janeiro' },
    { value: 'RN', label: 'Rio Grande do Norte' },
    { value: 'RS', label: 'Rio Grande do Sul' },
    { value: 'RO', label: 'Rondônia' },
    { value: 'RR', label: 'Roraima' },
    { value: 'SC', label: 'Santa Catarina' },
    { value: 'SP', label: 'São Paulo' },
    { value: 'SE', label: 'Sergipe' },
    { value: 'TO', label: 'Tocantins' }
];
</script>

<template>

    <Head title="Criar Evento" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold">Criar Novo Evento</h1>
                <p class="text-gray-500 mt-1">Preencha as informações do seu evento</p>
            </div>

            <!-- Formulário -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Informações Básicas -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-950 dark:border-gray-800">
                    <h2 class="text-lg font-semibold mb-4">Informações Básicas</h2>

                    <div class="space-y-4">
                        <!-- Título -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Título do Evento *
                            </label>
                            <input id="title" v-model="form.title" type="text" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900"
                                placeholder="Ex: Show de Rock no Parque" />
                            <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <!-- Categoria -->
                        <div>
                            <label for="category"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Categoria *
                            </label>
                            <select id="category" v-model="form.category_id" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900">
                                <option value="">Selecione uma categoria</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.category_id }}
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Descrição *
                            </label>
                            <textarea id="description" v-model="form.description" rows="4" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900"
                                placeholder="Descreva seu evento em detalhes..."></textarea>
                            <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                {{ form.errors.description }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data e Hora -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-950 dark:border-gray-800">
                    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <Calendar class="h-5 w-5" />
                        Data e Hora
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Data/Hora Início -->
                        <div>
                            <label for="start_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Data e Hora de Início *
                            </label>
                            <input id="start_date" v-model="form.start_date" type="datetime-local" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900" />
                            <div v-if="form.errors.start_date" class="mt-1 text-sm text-red-600">
                                {{ form.errors.start_date }}
                            </div>
                        </div>

                        <!-- Data/Hora Fim -->
                        <div>
                            <label for="end_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Data e Hora de Término *
                            </label>
                            <input id="end_date" v-model="form.end_date" type="datetime-local" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900" />
                            <div v-if="form.errors.end_date" class="mt-1 text-sm text-red-600">
                                {{ form.errors.end_date }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Localização -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-950 dark:border-gray-800">
                    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <MapPin class="h-5 w-5" />
                        Localização
                    </h2>

                    <div class="space-y-4">
                        <!-- Nome do Local -->
                        <div>
                            <label for="location"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome do Local *
                            </label>
                            <input id="location" v-model="form.location" type="text" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900"
                                placeholder="Ex: Teatro Municipal" />
                            <div v-if="form.errors.location" class="mt-1 text-sm text-red-600">
                                {{ form.errors.location }}
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div>
                            <label for="address"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Endereço
                            </label>
                            <input id="address" v-model="form.address" type="text"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900"
                                placeholder="Ex: Rua das Flores, 123" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Cidade -->
                            <div class="md:col-span-1">
                                <label for="city"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Cidade *
                                </label>
                                <input id="city" v-model="form.city" type="text" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900"
                                    placeholder="Ex: São Paulo" />
                                <div v-if="form.errors.city" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.city }}
                                </div>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="state"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Estado *
                                </label>
                                <select id="state" v-model="form.state" required
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900">
                                    <option value="">Selecione</option>
                                    <option v-for="state in states" :key="state.value" :value="state.value">
                                        {{ state.label }}
                                    </option>
                                </select>
                                <div v-if="form.errors.state" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.state }}
                                </div>
                            </div>

                            <!-- CEP -->
                            <div>
                                <label for="zip_code"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    CEP
                                </label>
                                <input id="zip_code" v-model="form.zip_code" type="text"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900"
                                    placeholder="00000-000" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Imagens -->
                <div class="rounded-xl border bg-white p-6 shadow-sm dark:bg-gray-950 dark:border-gray-800">
                    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <Image class="h-5 w-5" />
                        Imagens
                    </h2>

                    <div class="space-y-4">
                        <!-- Banner -->
                        <div>
                            <label for="banner" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Banner Principal
                            </label>
                            <input id="banner" type="file" accept="image/*" @change="handleBannerChange"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900" />
                            <p class="mt-1 text-xs text-gray-500">Recomendado: 1920x1080px, máximo 5MB</p>

                            <!-- Preview do Banner -->
                            <div v-if="bannerPreview" class="mt-2">
                                <img :src="bannerPreview" alt="Preview" class="h-32 w-auto rounded-lg object-cover" />
                            </div>
                        </div>

                        <!-- Galeria -->
                        <div>
                            <label for="gallery"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Galeria de Imagens
                            </label>
                            <input id="gallery" type="file" accept="image/*" multiple @change="handleGalleryChange"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900" />
                            <p class="mt-1 text-xs text-gray-500">Selecione múltiplas imagens, máximo 5MB cada</p>

                            <!-- Preview da Galeria -->
                            <div v-if="galleryPreviews.length > 0" class="mt-2 grid grid-cols-4 gap-2">
                                <img v-for="(preview, index) in galleryPreviews" :key="index" :src="preview"
                                    alt="Preview" class="h-24 w-full rounded-lg object-cover" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-end gap-4">
                    <Link :href="route('dashboard.events')"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancelar
                    </Link>
                    <button type="submit" :disabled="form.processing"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                        <Save class="h-4 w-4" />
                        Criar Evento
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
