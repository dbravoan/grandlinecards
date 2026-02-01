<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChartBarIcon, FireIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    topLeaders: Array,
    trendingCards: Array,
});
</script>

<template>
    <AppLayout>
        <Head title="Meta" />

        <div class="space-y-8">
            <div class="text-center">
                <h1 class="text-4xl font-display font-bold text-grand-gold uppercase tracking-wider mb-2">
                    Metajuego
                </h1>
                <p class="text-grand-300">Análisis basado en los mazos de la comunidad</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Top Leaders Section -->
                <div class="bg-grand-800 rounded-xl p-6 border border-grand-700 shadow-xl">
                    <div class="flex items-center mb-6">
                        <ChartBarIcon class="w-6 h-6 text-grand-gold mr-2" />
                        <h2 class="text-2xl font-display font-bold text-white uppercase tracking-wide">
                            Líderes Tier S
                        </h2>
                    </div>

                    <div v-if="topLeaders.length === 0" class="text-center py-8 text-grand-400 italic">
                        No hay suficientes datos de mazos públicos aún.
                    </div>
                    
                    <div v-else class="space-y-4">
                        <div v-for="(item, index) in topLeaders" :key="item.card.id" 
                             class="flex items-center bg-grand-900/50 p-4 rounded-lg border border-grand-700/50 hover:border-grand-500 transition-colors">
                            <div class="w-12 h-12 flex items-center justify-center font-display font-bold text-2xl text-grand-600 mr-4">
                                #{{ index + 1 }}
                            </div>
                            <img :src="item.card.image_url" :alt="item.card.name" class="w-16 h-22 object-cover rounded shadow-lg border border-gray-800">
                            <div class="ml-4 flex-1">
                                <Link :href="route('catalog.show', item.card.id)" class="text-lg font-bold text-white hover:text-grand-gold transition">
                                    {{ item.card.name }}
                                </Link>
                                <div class="text-sm text-grand-300">{{ item.card.color }} Leader</div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-grand-gold">{{ item.usage_count }}</div>
                                <div class="text-xs text-grand-400 uppercase">Mazos</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trending Cards Section -->
                <div class="bg-grand-800 rounded-xl p-6 border border-grand-700 shadow-xl">
                    <div class="flex items-center mb-6">
                        <FireIcon class="w-6 h-6 text-orange-500 mr-2" />
                        <h2 class="text-2xl font-display font-bold text-white uppercase tracking-wide">
                            Cartas En Tendencia
                        </h2>
                    </div>

                    <div v-if="trendingCards.length === 0" class="text-center py-8 text-grand-400 italic">
                        No hay suficientes datos de uso aún.
                    </div>

                    <div v-else class="grid grid-cols-2 sm:grid-cols-2 gap-4">
                         <div v-for="item in trendingCards" :key="item.card.id" 
                              class="relative group aspect-[2/3] rounded-lg overflow-hidden border border-grand-700 hover:border-grand-gold transition-all duration-300">
                             <img :src="item.card.image_url" :alt="item.card.name" class="w-full h-full object-cover">
                             <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/90 to-transparent p-3 pt-8">
                                 <Link :href="route('catalog.show', item.card.id)" class="block text-sm font-bold text-white truncate hover:underline">
                                     {{ item.card.name }}
                                 </Link>
                                 <div class="flex justify-between items-end mt-1">
                                     <span class="text-xs text-grand-300">{{ item.card.id }}</span>
                                     <span class="text-xs font-bold bg-orange-600/90 text-white px-2 py-0.5 rounded-full">
                                         {{ item.frequency }}x
                                     </span>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>

            <div class="text-center pt-8">
                 <Link :href="route('profile.decks.index')" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-grand-900 bg-grand-gold hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-grand-gold transition-colors">
                    Crear Mazo y Contribuir al Meta
                </Link>
            </div>
            
        </div>
    </AppLayout>
</template>
