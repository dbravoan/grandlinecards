<template>
    <div class="h-full flex flex-col md:flex-row gap-8">
        <!-- Timeline Graphics -->
        <div class="relative py-4 md:w-1/2 flex flex-col justify-between">
            <!-- Connecting Line -->
            <div class="absolute left-[19px] top-4 bottom-4 w-1 bg-grand-700/50"></div>

            <button 
                v-for="(phase, index) in phases" 
                :key="index" 
                class="relative flex items-center gap-4 group text-left w-full pl-0"
                @click="selectedPhase = index"
            >
                <!-- Node -->
                <div class="z-10 w-10 h-10 rounded-full border-4 flex items-center justify-center transition-all duration-300"
                     :class="selectedPhase === index ? 'bg-grand-gold border-grand-gold text-grand-900 scale-110 shadow-foil' : 'bg-grand-900 border-grand-700 text-grand-500 group-hover:border-grand-gold group-hover:text-grand-gold'">
                    <span class="font-bold text-sm">{{ index + 1 }}</span>
                </div>
                
                <!-- Label -->
                <div class="flex-1 p-3 rounded-lg border transition-all duration-300"
                     :class="selectedPhase === index ? 'bg-grand-800 border-grand-gold shadow-lg' : 'bg-transparent border-transparent group-hover:bg-grand-800/50'">
                    <h4 class="font-bold uppercase tracking-wider text-sm transition-colors"
                        :class="selectedPhase === index ? 'text-grand-gold' : 'text-grand-bone group-hover:text-grand-gold'">
                        {{ phase.name }}
                    </h4>
                </div>
            </button>
        </div>

        <!-- Detail View -->
        <div class="md:w-1/2 bg-grand-800 rounded-xl p-6 border border-grand-700 flex flex-col justify-center relative overflow-hidden">
            <!-- Background Decoration -->
            <component :is="phases[selectedPhase].icon" class="absolute -right-8 -bottom-8 w-48 h-48 text-grand-900 rotate-12" />

            <div class="relative z-10">
                <div class="w-12 h-12 mb-4 bg-grand-700 rounded-lg flex items-center justify-center text-grand-gold shadow-inner border border-grand-600">
                     <component :is="phases[selectedPhase].icon" class="w-7 h-7" />
                </div>
                
                <h3 class="text-2xl font-display font-bold text-grand-gold mb-2 uppercase">{{ phases[selectedPhase].name }}</h3>
                <p class="text-lg text-grand-bone leading-relaxed mb-6">{{ phases[selectedPhase].desc }}</p>
                
                <div v-if="phases[selectedPhase].note" class="bg-grand-900/50 border-l-4 border-grand-accent p-4 rounded-r">
                    <p class="text-sm text-grand-bone/80 italic">
                        <span class="font-bold text-grand-accent not-italic block mb-1">Importante:</span>
                        {{ phases[selectedPhase].note }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, markRaw } from 'vue';
import { ArrowPathIcon, HandRaisedIcon, BoltIcon, FireIcon, NoSymbolIcon } from '@heroicons/vue/24/outline';

const selectedPhase = ref(0);

const phases = [
    {
        name: 'Fase de Enderezar (Refresh Phase)',
        desc: 'Endereza todas tus cartas giradas (Rest) y devuelve tus DON!! del campo al mazo de DON!!.',
        note: null,
        icon: markRaw(ArrowPathIcon)
    },
    {
        name: 'Fase de Robo (Draw Phase)',
        desc: 'Roba 1 carta de tu mazo.',
        note: 'El jugador que empieza la partida NO roba carta en su primer turno.',
        icon: markRaw(HandRaisedIcon)
    },
    {
        name: 'Fase de DON!! (DON!! Phase)',
        desc: 'Coloca 2 cartas de tu mazo de DON!! boca arriba en tu Cost Area.',
        note: 'El jugador que empieza solo pone 1 DON!! en su primer turno.',
        icon: markRaw(BoltIcon)
    },
    {
        name: 'Fase Principal (Main Phase)',
        desc: 'La fase principal. Juega cartas, activa efectos, mueve tus DON!! y ataca.',
        note: null,
        icon: markRaw(FireIcon)
    },
    {
        name: 'Fase Final (End Phase)',
        desc: 'Todos los efectos que duran "hasta el final del turno" expiran. El turno pasa a tu oponente.',
        note: null,
        icon: markRaw(NoSymbolIcon)
    }
];
</script>
