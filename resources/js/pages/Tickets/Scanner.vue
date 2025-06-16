<!-- resources/js/Pages/Tickets/Scanner.vue -->
<template>
    <AppLayout>

        <Head title="Validar Ingressos" />

        <div class="min-h-screen bg-gray-50 py-8">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h1 class="text-2xl font-bold text-gray-900 mb-6">Validar Ingressos</h1>

                        <!-- Scanner -->
                        <div v-if="!result" class="space-y-4">
                            <div v-if="hasCamera" class="relative">
                                <video ref="videoElement" class="w-full rounded-lg" autoplay playsinline></video>
                                <div class="absolute inset-0 pointer-events-none">
                                    <div class="h-full w-full border-2 border-dashed border-white/50 rounded-lg"></div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="h-64 w-64 border-2 border-white rounded-lg"></div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="text-center py-12">
                                <Camera class="mx-auto h-12 w-12 text-gray-400" />
                                <p class="mt-2 text-sm text-gray-600">
                                    Câmera não disponível. Por favor, permita o acesso à câmera.
                                </p>
                            </div>

                            <div class="text-center">
                                <p class="text-sm text-gray-600">
                                    Posicione o QR Code do ingresso na área marcada
                                </p>
                            </div>
                        </div>

                        <!-- Resultado -->
                        <div v-else class="space-y-4">
                            <div class="rounded-lg p-4" :class="{
                                'bg-green-50 border border-green-200': result.success,
                                'bg-red-50 border border-red-200': !result.success
                            }">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <CheckCircle v-if="result.success" class="h-6 w-6 text-green-400" />
                                        <XCircle v-else class="h-6 w-6 text-red-400" />
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-sm font-medium" :class="{
                                            'text-green-800': result.success,
                                            'text-red-800': !result.success
                                        }">
                                            {{ result.message }}
                                        </h3>

                                        <div v-if="result.ticket" class="mt-3 space-y-2">
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Ingresso:</span> {{
                                                result.ticket.ticket_number }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Tipo:</span> {{ result.ticket.ticket_type.name
                                                }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Participante:</span> {{
                                                result.ticket.attendee_name }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Evento:</span> {{
                                                result.ticket.order.event.title }}
                                            </p>
                                            <p v-if="result.ticket.validated_at" class="text-sm text-gray-600">
                                                <span class="font-medium">Validado em:</span> {{
                                                formatDateTime(result.ticket.validated_at) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button @click="resetScanner"
                                class="w-full rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Escanear outro ingresso
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Camera, CheckCircle, XCircle } from 'lucide-vue-next';
import jsQR from 'jsqr';
import axios from 'axios';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

const videoElement = ref<HTMLVideoElement | null>(null);
const hasCamera = ref(false);
const result = ref<any>(null);
let stream: MediaStream | null = null;
let animationId: number | null = null;

const formatDateTime = (date: string) => {
    return format(new Date(date), "d 'de' MMMM 'de' yyyy 'às' HH:mm", { locale: ptBR });
};

const startCamera = async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' }
        });

        if (videoElement.value) {
            videoElement.value.srcObject = stream;
            hasCamera.value = true;
            scanQRCode();
        }
    } catch (error) {
        console.error('Erro ao acessar câmera:', error);
        hasCamera.value = false;
    }
};

const stopCamera = () => {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    if (animationId) {
        cancelAnimationFrame(animationId);
        animationId = null;
    }
};

const scanQRCode = () => {
    if (!videoElement.value || result.value) return;

    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');

    if (!context) return;

    if (videoElement.value.readyState === videoElement.value.HAVE_ENOUGH_DATA) {
        canvas.height = videoElement.value.videoHeight;
        canvas.width = videoElement.value.videoWidth;
        context.drawImage(videoElement.value, 0, 0, canvas.width, canvas.height);

        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
            validateTicket(code.data);
            return;
        }
    }

    animationId = requestAnimationFrame(scanQRCode);
};

const validateTicket = async (qrCode: string) => {
    try {
        const response = await axios.post(route('tickets.validate'), {
            qr_code: qrCode
        });

        result.value = response.data;
        stopCamera();
    } catch (error: any) {
        result.value = error.response?.data || {
            success: false,
            message: 'Erro ao validar ingresso'
        };
        stopCamera();
    }
};

const resetScanner = () => {
    result.value = null;
    startCamera();
};

onMounted(() => {
    startCamera();
});

onUnmounted(() => {
    stopCamera();
});
</script>
