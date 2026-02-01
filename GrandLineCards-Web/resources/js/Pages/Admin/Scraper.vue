<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { CloudArrowDownIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    sets: Array, // [{value: 'OP-01', label: 'OP-01'}]
});

const form = useForm({
    set: '',
});

const submit = () => {
    form.post(route('admin.scraper.run'), {
        preserveScroll: true,
        onSuccess: () => {
             // Flash message handled by layout or inline check
             form.reset();
        }
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Rastreador Sitio Oficial" />
        
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl font-bold text-grand-bone mb-6 flex items-center gap-3">
                <CloudArrowDownIcon class="w-8 h-8 text-grand-gold" />
                Rastreador Sitio Oficial
            </h2>

            <div class="bg-grand-900 border border-grand-700 rounded-lg p-6 shadow-xl relative overflow-hidden">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-grand-gold/5 rounded-full blur-3xl pointer-events-none"></div>

                <p class="text-grand-300 mb-6">
                    Selecciona un set de cartas para ingerir desde el sitio web oficial de One Piece Card Game.
                    Este proceso descargará los datos de las cartas y las imágenes a la base de datos local.
                </p>

                <form @submit.prevent="submit" class="space-y-6 relative z-10">
                    <div>
                        <label for="set" class="block text-sm font-medium text-grand-400 mb-2">Set de Expansión Objetivo</label>
                        <select id="set" v-model="form.set" class="block w-full rounded-md border-grand-700 bg-grand-800 text-grand-bone shadow-sm focus:border-grand-gold focus:ring focus:ring-grand-gold/50 sm:text-sm py-3 px-4">
                            <option value="" disabled>Selecciona un Set</option>
                            <option v-for="option in sets" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>
                        <p v-if="form.errors.set" class="mt-2 text-sm text-red-500">{{ form.errors.set }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-grand-700/50">
                        <div class="text-xs text-grand-500">
                            * El proceso se ejecuta de forma síncrona. Por favor espere.
                        </div>
                        <button type="submit" :disabled="form.processing" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-md shadow-sm text-grand-900 bg-grand-gold hover:bg-grand-accent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-grand-gold disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-grand-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Ingestando Datos...' : 'Iniciar Ingesta' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Flash Messages Area -->
            <div v-if="$page.props.flash.success" class="mt-6 bg-green-900/50 border border-green-500/30 text-green-200 p-4 rounded-lg flex items-start gap-3">
                <span class="text-green-500">✅</span>
                <div>
                    <h4 class="font-bold text-sm">Operación Exitosa</h4>
                    <p class="text-sm opacity-90 mt-1 whitespace-pre-wrap">{{ $page.props.flash.success }}</p>
                </div>
            </div>

            <div v-if="$page.props.flash.error" class="mt-6 bg-red-900/50 border border-red-500/30 text-red-200 p-4 rounded-lg flex items-start gap-3">
                <span class="text-red-500">❌</span>
                <div>
                    <h4 class="font-bold text-sm">Operación Fallida</h4>
                    <p class="text-sm opacity-90 mt-1">{{ $page.props.flash.error }}</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
