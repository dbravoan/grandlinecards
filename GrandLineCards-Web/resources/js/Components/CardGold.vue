<script setup>
import { computed } from 'vue';

const props = defineProps({
    card: Object, // { name, image_url, rarity, ... }
    showFoil: {
        type: Boolean,
        default: true
    }
});

const isfoil = computed(() => props.showFoil && ['SR', 'SEC', 'L'].includes(props.card?.rarity));

</script>

<template>
    <div class="relative group w-full aspect-[2/3] rounded-lg overflow-hidden cursor-pointer transform transition-transform duration-300 hover:scale-105 hover:z-10 bg-grand-800 border border-grand-700 shadow-lg">
        <!-- Card Image -->
        <img :src="card.image_url || '/images/card-back.png'" 
             :alt="card.name" 
             class="w-full h-full object-cover"
             loading="lazy" />

        <!-- Fail-safe / Placeholder -->
        <div v-if="!card.image_url" class="absolute inset-0 flex items-center justify-center text-grand-500 font-display text-2xl opacity-20">
            GLC
        </div>

        <!-- Foil Effect Overlay -->
        <div v-if="isfoil" 
             class="absolute inset-0 pointer-events-none opacity-0 group-hover:opacity-40 transition-opacity duration-300 bg-gradient-to-tr from-transparent via-white to-transparent mix-blend-overlay filter brightness-150 contrast-125 card-foil-shim">
        </div>

        <!-- Frame/Border Highlight (Gold for SR+) -->
        <div class="absolute inset-0 border-2 rounded-lg pointer-events-none transition-colors duration-300"
             :class="isfoil ? 'border-grand-gold/50 group-hover:border-grand-gold' : 'border-transparent group-hover:border-grand-500/30'">
        </div>
        
        <!-- Rarity Badge (Optional, corner) -->
         <div class="absolute bottom-2 right-2 bg-grand-900/80 px-1.5 py-0.5 rounded text-[10px] font-bold text-grand-bone border border-grand-gold/30">
            {{ card.code }}
         </div>
    </div>
</template>

<style scoped>
.card-foil-shim {
    background-size: 200% 200%;
    animation: galaxy 3s infinite linear;
}

@keyframes galaxy {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>
