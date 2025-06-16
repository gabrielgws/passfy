<!-- resources/js/Components/Modal.vue -->
<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="$emit('close')" class="relative z-10">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel
                            class="w-full transform overflow-hidden rounded-2xl bg-white text-left align-middle shadow-xl transition-all"
                            :class="{
                                'max-w-md': maxWidth === 'sm',
                                'max-w-xl': maxWidth === 'md',
                                'max-w-2xl': maxWidth === 'lg',
                                'max-w-4xl': maxWidth === 'xl',
                                'max-w-6xl': maxWidth === '2xl',
                            }">
                            <slot />
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup lang="ts">
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';

withDefaults(
    defineProps<{
        show: boolean;
        maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
    }>(),
    {
        maxWidth: 'md',
    }
);

defineEmits(['close']);
</script>
