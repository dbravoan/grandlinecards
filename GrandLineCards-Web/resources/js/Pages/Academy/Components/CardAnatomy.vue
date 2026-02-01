<script setup>
import { ref } from 'vue';

const activePart = ref(null);

const parts = [
    {
        id: 'cost',
        name: 'Coste (Cost)',
        description: 'El número de DON!! requeridos para jugar esta carta.',
        position: 'top-2 left-2 w-8 h-8 rounded-full'
    },
    {
        id: 'attribute',
        name: 'Atributo (Attribute)',
        description: 'Tipo de ataque: Slash (Corte), Strike (Golpe), Shoot (Disparo), etc.',
        position: 'top-2 right-2 w-8 h-8'
    },
    {
        id: 'power',
        name: 'Poder (Power)',
        description: 'La fuerza de batalla de la carta. ¡Úsala para atacar o defenderte!',
        position: 'top-12 right-2 w-12 h-6'
    },
    {
        id: 'counter',
        name: 'Counter',
        description: 'Puntos extra para defender a tu Líder o Personajes durante el turno del oponente.',
        position: 'left-2 top-24 w-6 h-12 -rotate-90'
    },
    {
        id: 'effect',
        name: 'Efecto (Effect)',
        description: 'Habilidades especiales que se activan bajo ciertas condiciones.',
        position: 'bottom-4 left-4 right-4 h-24'
    },
    {
        id: 'type',
        name: 'Tipos (Type)',
        description: 'Categorías a las que pertenece (ej. Straw Hat Crew). Importante para sinergias.',
        position: 'bottom-32 right-4 w-32 h-6'
    }
];
</script>

<template>
    <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16">
        <!-- Card Visualization -->
        <div class="relative group w-[300px] aspect-[2.5/3.5] bg-gray-800 rounded-xl shadow-2xl overflow-hidden border-4 border-gray-700 select-none">
             <!-- Placeholder Background for Card -->
            <div class="absolute inset-0 bg-gradient-to-br from-red-900 to-gray-900 opacity-50"></div>
            
            <!-- Card Mockup Structure -->
            <div class="absolute inset-2 border border-yellow-500/30 rounded-lg">
                <div class="text-center mt-4 text-grand-bone font-display font-bold text-2xl tracking-widest">LUFFY</div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-9xl opacity-10">☠️</div>
            </div>

            <!-- Interactive Zones -->
            <div 
                v-for="part in parts" 
                :key="part.id"
                @mouseenter="activePart = part"
                @mouseleave="activePart = null"
                class="absolute cursor-help bg-white/5 hover:bg-yellow-400/30 border border-transparent hover:border-yellow-400 transition-all duration-300 backdrop-blur-[1px] rounded"
                :class="part.position"
            >
                <span class="sr-only">{{ part.name }}</span>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="flex-1 min-h-[150px] lg:max-w-md">
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-2"
                mode="out-in"
            >
                <div v-if="activePart" class="bg-gray-800/80 border border-gray-700 p-6 rounded-xl backdrop-blur-md">
                    <h3 class="text-xl font-bold text-grand-gold mb-2">{{ activePart.name }}</h3>
                    <p class="text-gray-300">{{ activePart.description }}</p>
                </div>
                <div v-else class="text-gray-500 text-center p-6 border border-dashed border-gray-700 rounded-xl">
                    <p>Pasa el ratón sobre la carta para ver los detalles.</p>
                </div>
            </transition>
        </div>
    </div>
</template>
