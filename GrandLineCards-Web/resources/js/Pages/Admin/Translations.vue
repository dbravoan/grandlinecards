<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

// Mock Data
const pendingTranslations = [
    { id: 1, original: 'On Play: Look at 5 cards from the top of your deck.', translated: '' },
    { id: 2, original: 'Your Leader gains +1000 power during this turn.', translated: 'Tu Líder gana +1000 de poder durante este turno.' },
    { id: 3, original: 'Blocker (After your opponent declares an attack, you may rest this card to make it the new target of the attack.)', translated: '' },
];
</script>

<template>
    <AdminLayout>
        <Head title="Admin - Translations" />
        
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-grand-bone">Gestor de Traducciones</h2>
            <div class="space-x-2">
                 <span class="text-grand-500">Progreso:</span>
                 <span class="text-grand-gold font-bold">1 / 3</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div v-for="item in pendingTranslations" :key="item.id" class="bg-grand-900 border border-grand-700 rounded-lg overflow-hidden flex flex-col md:flex-row shadow-sm hover:border-grand-500 transition-colors">
                <!-- Original (EN) -->
                <div class="w-full md:w-1/2 p-4 border-b md:border-b-0 md:border-r border-grand-700 bg-grand-800/50">
                    <label class="block text-xs font-bold text-grand-500 uppercase mb-2">Original (EN)</label>
                    <p class="text-grand-bone font-mono text-sm leading-relaxed">{{ item.original }}</p>
                </div>

                <!-- Translation (ES) -->
                <div class="w-full md:w-1/2 p-4 bg-grand-900">
                    <label class="block text-xs font-bold text-grand-gold uppercase mb-2">Español (ES)</label>
                    <textarea 
                        class="w-full h-24 bg-grand-800 border border-grand-700 rounded p-2 text-grand-bone focus:border-grand-gold focus:ring-1 focus:ring-grand-gold outline-none resize-none"
                        :value="item.translated"
                        placeholder="Introduce la traducción aquí..."></textarea>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button class="px-6 py-2 bg-grand-gold text-grand-900 font-bold rounded shadow hover:bg-grand-accent transition-colors">
                Guardar Cambios
            </button>
        </div>
    </AdminLayout>
</template>
