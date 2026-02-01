<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CardGold from '@/Components/CardGold.vue';
import FilterSidebar from '@/Components/FilterSidebar.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    cards: Object, // Resource Collection
    filters: Object,
    expansions: Array,
    seo: Object, // Optional SEO metadata
});

const showFilters = ref(false);
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ seo?.meta_title || 'Enciclopedia - Catálogo de Cartas' }}</title>
            <meta name="description" :content="seo?.meta_description || 'Explora el catálogo completo de cartas de One Piece TCG. Filtra por colección, color, rareza y más.'" />
        </Head>

        <!-- SEO Header (Visible) -->
         <div v-if="seo" class="mb-8 w-full">
            <h1 class="font-display text-4xl text-grand-gold font-bold mb-2 uppercase tracking-wide">{{ seo.h1 }}</h1>
            <p class="text-grand-300 text-lg">{{ seo.meta_description }}</p>
         </div>

        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden mb-4">
            <button 
                @click="showFilters = !showFilters" 
                class="w-full bg-grand-800 border border-grand-600 text-grand-gold font-bold py-3 px-4 rounded flex justify-between items-center"
            >
                <span>{{ showFilters ? 'Ocultar Filtros' : 'Mostrar Filtros' }}</span>
                <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': showFilters}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="w-full lg:w-64 flex-shrink-0" :class="{'hidden lg:block': !showFilters}">
                <FilterSidebar :filters="filters" :expansions="expansions" />
            </aside>

            <!-- Grid -->
            <div class="flex-1">
                <div v-if="cards.data.length > 0" class="space-y-8">
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                        <Link v-for="card in cards.data" :key="card.id" :href="route('catalog.show', card.id)" class="block">
                            <CardGold :card="card" />
                        </Link>
                    </div>
                    
                    <!-- Pagination -->
                    <Pagination :links="cards.meta.links" />
                </div>
                
                <!-- Empty State -->
                <div v-else class="flex flex-col items-center justify-center p-12 text-center rounded-lg border-2 border-dashed border-grand-700 bg-grand-800/50">
                     <span class="text-4xl mb-4">🏴‍☠️</span>
                     <h3 class="font-display text-xl text-grand-gold font-bold mb-2">No se encontraron cartas</h3>
                     <p class="text-grand-500">Intenta ajustar tus filtros, Capitán.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
