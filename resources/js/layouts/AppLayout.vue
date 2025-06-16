<!-- <script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template> -->

<!-- resources/js/Layouts/AppLayout.vue -->
<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <nav class="bg-white shadow-sm border-b">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <Link :href="route('home')" class="text-2xl font-bold text-indigo-600">
                            EventHub
                            </Link>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <Link :href="route('events.index')"
                                class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium" :class="{
                                    'border-indigo-500 text-gray-900': $page.url.startsWith('/events'),
                                    'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': !$page.url.startsWith('/events')
                                }">
                            Eventos
                            </Link>
                            <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                                class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium" :class="{
                                    'border-indigo-500 text-gray-900': $page.url.startsWith('/dashboard'),
                                    'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': !$page.url.startsWith('/dashboard')
                                }">
                            Dashboard
                            </Link>
                        </div>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                        <Link v-if="$page.props.auth.user" :href="route('events.create')"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <Plus class="h-4 w-4 mr-1" />
                        Criar Evento
                        </Link>

                        <div v-if="$page.props.auth.user" class="relative ml-3">
                            <Menu as="div" class="relative inline-block text-left">
                                <MenuButton
                                    class="flex items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <span class="sr-only">Abrir menu do usuário</span>
                                    <div
                                        class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                    </div>
                                </MenuButton>

                                <transition enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('profile.edit')"
                                            :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">
                                        Perfil
                                        </Link>
                                        </MenuItem>
                                        <MenuItem v-if="$page.props.auth.user.is_admin" v-slot="{ active }">
                                        <Link :href="route('admin.categories.index')"
                                            :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">
                                        Admin
                                        </Link>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('logout')" method="post" as="button"
                                            :class="[active ? 'bg-gray-100' : '', 'block w-full px-4 py-2 text-left text-sm text-gray-700']">
                                        Sair
                                        </Link>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>

                        <div v-else class="flex items-center space-x-4">
                            <Link :href="route('login')" class="text-gray-500 hover:text-gray-700">
                            Entrar
                            </Link>
                            <Link :href="route('register')"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Cadastrar
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="mt-auto bg-white border-t">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    © 2024 EventHub. Todos os direitos reservados.
                </p>
            </div>
        </footer>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Plus } from 'lucide-vue-next';
</script>
