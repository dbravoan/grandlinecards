<template>
    <div class="h-full flex flex-col">
        <div class="mb-6 text-center">
            <h3 class="text-2xl font-display font-bold text-grand-gold mb-2">Comienza la Aventura</h3>
            <p class="text-grand-bone/70 max-w-lg mx-auto">Antes de empezar, asegúrate de tener todo listo. Sigue estos 4 pasos esenciales.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1">
            <div v-for="(step, index) in steps" :key="index" 
                 class="group bg-grand-800 border-2 border-transparent rounded-xl p-4 transition-all duration-300 hover:border-grand-gold hover:bg-grand-800/80 hover:shadow-lg relative overflow-hidden"
                 :class="{ 'border-grand-gold/30 bg-grand-800/50': !activeStep && activeStep !== 0, 'border-grand-gold shadow-foil': activeStep === index }"
                 @click="activeStep = index"
            >
                <!-- Number -->
                <div class="absolute -right-4 -bottom-4 text-8xl font-display font-bold text-grand-900 group-hover:text-grand-950 transition-colors opacity-50 select-none">
                    {{ index + 1 }}
                </div>

                <div class="relative z-10 flex gap-4 items-start">
                    <div class="p-3 rounded-lg bg-grand-900 border border-grand-700 text-grand-gold group-hover:scale-110 transition-transform duration-300">
                        <component :is="step.icon" class="w-6 h-6" />
                    </div>
                    <div>
                        <h4 class="font-bold text-lg text-grand-bone mb-1 group-hover:text-grand-gold transition-colors">{{ step.title }}</h4>
                        <p class="text-sm text-grand-bone/70 leading-relaxed">{{ step.desc }}</p>
                        
                        <div v-if="step.check" class="mt-3 inline-flex items-center gap-2 px-3 py-1 rounded bg-grand-900/80 border border-grand-500/30 text-xs font-mono text-grand-gold">
                            <span class="text-yellow-500">⚠</span> {{ step.check }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { QueueListIcon, UserIcon, HandRaisedIcon, ArrowsRightLeftIcon } from '@heroicons/vue/24/outline';

const activeStep = ref(null);

const steps = [
    { 
        title: 'Mazo & DON!!', 
        desc: 'Construye tu mazo con exactamente 50 cartas y un mazo separado de 10 cartas DON!!.',
        check: 'Max 4 copias/carta',
        icon: QueueListIcon
    },
    { 
        title: 'Tu Líder', 
        desc: 'Coloca tu carta de Líder boca arriba en la zona de Líder central.',
        check: null,
        icon: UserIcon
    },
    { 
        title: 'Mano & Vidas', 
        desc: 'Roba 5 cartas. Mira las vidas de tu Líder y coloca ese número de cartas del mazo en el área de vidas.',
        check: '1 Mulligan permitido',
        icon: HandRaisedIcon
    },
    { 
        title: 'Primer Jugador', 
        desc: 'Decididlo con Piedra-Papel-Tijera. El ganador elige quién va primero.',
        check: null,
        icon: ArrowsRightLeftIcon
    }
];
</script>
