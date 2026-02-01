<script setup>
import { ArrowPathIcon, HandRaisedIcon, BoltIcon, FireIcon, HandThumbDownIcon } from '@heroicons/vue/24/outline';

const phases = [
    { name: 'Fase de Enderezar (Refresh Phase)', icon: ArrowPathIcon, desc: 'Endereza todas tus cartas y devuelve los DON!! al mazo de DON!!.' },
    { name: 'Fase de Robo (Draw Phase)', icon: HandRaisedIcon, desc: 'Roba 1 carta de tu mazo.' },
    { name: 'Fase de DON!! (DON!! Phase)', icon: BoltIcon, desc: 'Toma 2 cartas DON!! y ponlas en tu Cost Area.' },
    { name: 'Fase Principal (Main Phase)', icon: FireIcon, desc: 'Juega personajes, ataca, activa efectos y asigna DON!!.' },
    { name: 'Fase Final (End Phase)', icon: HandThumbDownIcon, desc: 'Todos los efectos que duran "hasta el final del turno" terminan.' },
];
</script>

<template>
    <div class="relative py-10">
        <!-- Connecting Line -->
        <div class="absolute left-8 top-0 bottom-0 w-1 bg-grand-700/50 md:left-1/2 md:-ml-0.5"></div>

        <div class="space-y-12">
            <div 
                v-for="(phase, idx) in phases" 
                :key="phase.name"
                class="relative flex items-center md:justify-center"
            >
                <!-- Icon Bubble -->
                <div class="absolute left-0 w-16 h-16 rounded-full border-4 border-grand-900 bg-grand-700 text-white flex items-center justify-center z-10 md:static">
                    <component :is="phase.icon" class="w-8 h-8" />
                </div>

                <!-- Content Card -->
                <div class="ml-24 md:ml-0 md:absolute md:w-[45%] p-6 bg-grand-800 rounded-xl border border-grand-700 shadow-lg"
                    :class="idx % 2 === 0 ? 'md:left-0 md:text-right md:pr-12' : 'md:right-0 md:text-left md:pl-12'"
                >
                    <h4 class="text-xl font-bold text-grand-gold mb-2">{{ idx + 1 }}. {{ phase.name }}</h4>
                    <p class="text-gray-300 text-sm">{{ phase.desc }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
