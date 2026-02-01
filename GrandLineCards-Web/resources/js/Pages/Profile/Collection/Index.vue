<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    collection: Object, // Grouped by Expansion? Or Array? Controller returns Grouped collection?
    // Controller: ->groupBy('card.expansion_code'). This returns object { 'OP01': [...], 'OP02': [...] }
});

const searchQuery = ref('');

// Computed to flatten or filter if needed, but for now we iterate groups
</script>

<template>
    <Head title="Mi Colección" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Mi Colección
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                    
                    <div v-if="Object.keys(collection).length === 0" class="text-center py-10 text-gray-500">
                        <p>No tienes cartas en tu colección.</p>
                        <a href="/cartas" class="text-grand-gold hover:underline mt-2 inline-block">Ir al Catálogo</a>
                    </div>

                    <div v-else>
                        <div v-for="(items, expansion) in collection" :key="expansion" class="mb-8">
                            <h3 class="text-xl font-bold text-white mb-4 border-b border-gray-700 pb-2">{{ expansion }}</h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                <div v-for="item in items" :key="item.id" class="bg-gray-700 rounded p-3 relative">
                                    <div class="text-xs text-gray-400 mb-1">{{ item.card.card_id }}</div>
                                    <a :href="route('catalog.show', item.card.card_id)" class="text-sm font-bold text-white hover:text-grand-gold truncate block">
                                        {{ item.card.translations ? item.card.translations.find(t => t.locale === 'es')?.name || item.card.translations[0]?.name : 'Sin Nombre' }}
                                    </a>
                                    <div class="mt-2 flex justify-between items-center">
                                        <span class="text-xs px-1 rounded" :class="item.is_foil ? 'bg-yellow-600 text-yellow-100' : 'bg-gray-600 text-gray-300'">
                                            {{ item.is_foil ? 'FOIL' : 'Normal' }}
                                        </span>
                                        <span class="font-mono text-lg text-grand-gold">x{{ item.quantity }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
