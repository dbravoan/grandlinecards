<script setup>
import { ShoppingBagIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';

defineProps({
    deck: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div class="bg-gradient-to-br from-red-900 via-grand-900 to-black rounded-2xl overflow-hidden border border-red-500/30 relative">
        <div class="absolute top-0 right-0 p-32 bg-red-500/10 blur-[100px] rounded-full"></div>
        
        <div class="flex flex-col md:flex-row items-center gap-8 p-8 md:p-12 relative z-10">
            <div class="w-full md:w-1/3">
                <img :src="deck.image_url" :alt="deck.name" class="w-full rounded-lg shadow-2xl hover:scale-105 transition-transform duration-500 border border-white/10" />
            </div>

            <div class="w-full md:w-2/3 text-center md:text-left">
                <span class="text-red-400 font-bold tracking-widest text-sm uppercase mb-2 block">Recomendado para Principiantes</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-4">{{ deck.name }}</h2>
                <p class="text-gray-300 text-lg mb-6 leading-relaxed">{{ deck.description }}</p>

                <div class="flex flex-wrap justify-center md:justify-start gap-3 mb-8">
                    <span v-for="feat in deck.features" :key="feat" class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-sm text-gray-300">
                        {{ feat }}
                    </span>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="text-3xl font-bold text-grand-gold">{{ deck.price }} €</div>
                    <Link :href="route('shop.index')" class="relative overflow-hidden group bg-red-600 hover:bg-red-500 text-white font-bold py-4 px-8 rounded-xl transition-all shadow-lg hover:shadow-red-500/25 w-full md:w-auto text-center inline-block">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <ShoppingBagIcon class="w-5 h-5" />
                            Comprar Mazo Inicial
                        </span>
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
