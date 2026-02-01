<script setup>
import { ref } from 'vue';

const selectedZone = ref(null);

const zones = [
    { id: 'life', label: 'Área de Vida (Life)', desc: 'Aquí van tus cartas de vida. Si te golpean cuando tienes 0, ¡pierdes!', class: 'top-0 left-0 w-32 h-[300px] bg-red-500/10 border-red-500' },
    { id: 'stage', label: 'Escenario (Stage)', desc: 'Zona para cartas de Escenario que dan efectos pasivos.', class: 'top-0 right-0 w-32 h-24 bg-purple-500/10 border-purple-500' },
    { id: 'leader', label: 'Líder (Leader)', desc: 'Tu carta principal. Determina tu vida y color.', class: 'top-10 left-40 w-24 h-32 bg-yellow-500/10 border-yellow-500' },
    { id: 'character', label: 'Área de Personajes (Character Area)', desc: 'Donde juegas a tus tripulantes para atacar.', class: 'top-10 right-40 left-[18rem] h-32 bg-blue-500/10 border-blue-500' },
    { id: 'deck', label: 'Mazo (Deck)', desc: 'Tu mazo de 50 cartas.', class: 'bottom-0 right-0 w-32 h-24 bg-gray-500/10 border-gray-500' },
    { id: 'trash', label: 'Basura (Trash)', desc: 'Cementerio. Aquí van las cartas KO o usadas.', class: 'bottom-28 right-0 w-32 h-24 bg-gray-700/10 border-gray-600' },
    { id: 'cost', label: 'Área de Coste (Cost Area)', desc: 'Donde pones tus cartas DON!! para pagar costes.', class: 'bottom-0 left-40 w-[300px] h-24 bg-white/10 border-white' },
];
</script>

<template>
    <div class="flex flex-col md:flex-row gap-8">
        <div class="relative w-full aspect-video bg-grand-900 border-2 border-grand-700 rounded-xl overflow-hidden shadow-inner p-4">
             <!-- Simplified Board Layout -->
             <div class="absolute inset-4 opacity-50 pointer-events-none">
                <div class="w-full h-full border-2 border-dashed border-gray-600 rounded"></div>
             </div>

             <button
                v-for="zone in zones"
                :key="zone.id"
                @click="selectedZone = zone"
                class="absolute flex items-center justify-center border-2 border-dashed rounded transition-colors hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-grand-gold text-xs font-bold uppercase tracking-wider text-white/50 hover:text-white"
                :class="[zone.class, selectedZone?.id === zone.id ? 'bg-white/20 text-white !border-solid ring-2' : '']"
             >
                {{ zone.label }}
             </button>
        </div>

        <div class="md:w-1/3 min-h-[150px] bg-grand-800 p-6 rounded-xl border border-grand-700">
            <h3 class="text-xl font-bold text-grand-gold mb-4 font-display">
                {{ selectedZone?.label || 'Mapa del Tablero' }}
            </h3>
            <p class="text-gray-300">
                {{ selectedZone?.desc || 'Haz clic en una zona del tablero para saber para qué sirve.' }}
            </p>
        </div>
    </div>
</template>
