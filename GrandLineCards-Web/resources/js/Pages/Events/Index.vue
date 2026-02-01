<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    events: Array
});

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth.user);
// ... form logic ...
</script>

<template>
    <AppLayout>
        <Head>
            <title>Log Pose - Eventos de One Piece TCG</title>
            <meta name="description" content="Calendario oficial de eventos competitivos y casuales de One Piece Card Game. Encuentra torneos cerca de ti." />
        </Head>

        <div class="space-y-12">
            <h1 class="text-3xl font-display font-bold text-grand-gold uppercase tracking-wider text-center">
                Log Pose: Calendario de Eventos
            </h1>

            <!-- Calendar / List -->
            <div class="bg-grand-800 rounded-lg p-8 border border-grand-700 text-center text-grand-500">
                <p>Navega hacia tu próximo desafío.</p>
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Dynamic Events -->
                     <div v-for="event in events" :key="event.id" class="bg-grand-900 p-4 rounded border border-grand-600 hover:border-grand-gold transition-colors text-left group">
                        <div class="h-40 mb-4 overflow-hidden rounded relative">
                            <img :src="event.image" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500 opacity-80 group-hover:opacity-100" />
                            <span class="absolute top-2 right-2 px-2 py-1 bg-grand-900/80 text-xs font-bold text-white rounded border border-grand-gold/50 backdrop-blur-sm">
                                {{ event.type }}
                            </span>
                        </div>
                        <div class="text-grand-gold font-bold text-lg mb-1">{{ event.title }}</div>
                        <div class="text-sm text-grand-300 mb-2 flex items-center gap-2">
                            <span>📅 {{ event.date }}</span>
                        </div>
                        <div class="text-sm text-white mb-2 font-medium">📍 {{ event.location }}</div>
                        <p class="text-xs text-grand-400 line-clamp-2">{{ event.description }}</p>
                     </div>
                </div>
            </div>

            <!-- Suggestion Form -->
            <div class="max-w-2xl mx-auto bg-grand-800/50 rounded-lg p-8 border border-grand-700/50">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-display font-bold text-grand-bone">¿Organizandor o Tienda?</h2>
                    <p class="text-sm text-grand-400">Sugiere un evento para que aparezca en el Log Pose.</p>
                </div>

                <div v-if="!isAuthenticated" class="text-center py-4 bg-grand-900 rounded border border-grand-700 border-dashed">
                    <p class="text-grand-400 mb-2">Debes iniciar sesión para sugerir eventos.</p>
                    <a href="/login" class="text-grand-gold font-bold hover:underline">Iniciar Sesión</a>
                </div>

                <form v-else @submit.prevent="submitSuggestion" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-grand-400 uppercase mb-1">Nombre del Evento</label>
                            <input v-model="form.title" type="text" required class="w-full bg-grand-900 border border-grand-700 rounded p-2 text-white focus:border-grand-gold outline-none">
                        </div>
                        <div>
                             <label class="block text-xs font-bold text-grand-400 uppercase mb-1">Fecha</label>
                            <input v-model="form.event_date" type="datetime-local" class="w-full bg-grand-900 border border-grand-700 rounded p-2 text-white focus:border-grand-gold outline-none">
                        </div>
                    </div>
                    
                    <div>
                         <label class="block text-xs font-bold text-grand-400 uppercase mb-1">Ubicación</label>
                        <input v-model="form.location" type="text" required placeholder="Ej: Tienda X, Online, etc." class="w-full bg-grand-900 border border-grand-700 rounded p-2 text-white focus:border-grand-gold outline-none">
                    </div>

                     <div>
                         <label class="block text-xs font-bold text-grand-400 uppercase mb-1">Descripción / Detalles</label>
                        <textarea v-model="form.description" rows="3" class="w-full bg-grand-900 border border-grand-700 rounded p-2 text-white focus:border-grand-gold outline-none"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 bg-grand-gold text-grand-900 font-bold uppercase tracking-wider hover:bg-white transition-colors rounded shadow-lg shadow-grand-gold/10">
                            Enviar Propuesta
                        </button>
                    </div>

                    <div v-if="submitStatus === 'success'" class="p-2 bg-green-900/50 text-green-400 text-center rounded border border-green-800 text-sm">
                        ¡Propuesta enviada al Almirante! Pendiente de aprobación.
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
