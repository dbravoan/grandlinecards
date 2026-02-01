<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    status: Number,
});

const title = computed(() => {
    return {
        503: 'Servicio No Disponible',
        500: 'Error del Servidor',
        404: 'Página No Encontrada',
        403: 'Acceso Prohibido',
    }[props.status] || 'Error';
});

const description = computed(() => {
    return {
        503: 'Lo sentimos, estamos realizando mantenimiento. Volveremos pronto.',
        500: '¡Rayos! Algo se ha roto en las cubiertas inferiores. Nuestros carpinteros están en ello.',
        404: '¡Hombre al agua! La página que buscas no existe o se ha hundido en el mar.',
        403: '¡Alto ahí! No tienes los credenciales necesarios para entrar en esta zona restringida.',
    }[props.status] || 'Ha ocurrido un error inesperado.';
});

const image = computed(() => {
    // Ideally use different SVGs/Images. For now, emojis or simple graphics.
    return {
        404: '🌊',
        500: '🔥',
        403: '⚔️',
    }[props.status] || '⚠️';
});
</script>

<template>
    <div class="min-h-screen bg-grand-900 text-white font-sans flex items-center justify-center relative overflow-hidden">
        <Head :title="title" />
        
        <!-- Background Texture -->
        <div class="absolute inset-0 z-0 opacity-10 bg-paper-texture pointer-events-none mix-blend-overlay"></div>

        <div class="relative z-10 max-w-xl w-full px-6 text-center">
            <div class="text-9xl mb-4 animate-bounce">{{ image }}</div>
            
            <h1 class="font-display text-6xl text-grand-gold mb-2">{{ props.status }}</h1>
            <h2 class="font-display text-3xl text-grand-bone mb-6 uppercase tracking-wider">{{ title }}</h2>
            
            <p class="text-grand-300 text-lg mb-10 leading-relaxed border-t border-b border-grand-700 py-6">
                {{ description }}
            </p>

            <Link href="/" class="inline-flex items-center px-8 py-3 bg-grand-gold text-grand-900 font-bold uppercase tracking-widest hover:bg-white transition-colors rounded shadow-lg">
                Volver al Barco
            </Link>
        </div>
    </div>
</template>
