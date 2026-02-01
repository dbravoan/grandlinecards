<template>
    <div class="h-full flex flex-col md:flex-row gap-6">
        <!-- Steps List -->
        <div class="md:w-5/12 space-y-3">
            <h3 class="text-xl font-display font-bold text-grand-gold mb-4 uppercase">Secuencia de Batalla</h3>
            
            <button v-for="(step, index) in steps" :key="index"
                @click="activeStep = index"
                class="w-full text-left p-4 rounded-lg border-2 transition-all duration-300 relative overflow-hidden group"
                :class="activeStep === index ? 'bg-grand-800 border-grand-gold shadow-foil' : 'bg-grand-900 border-grand-700 hover:border-grand-500'"
            >
                <div class="flex items-center gap-3 relative z-10">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-display font-bold text-sm transition-colors"
                          :class="activeStep === index ? 'bg-grand-gold text-grand-900' : 'bg-grand-800 text-grand-500 group-hover:bg-grand-700'">
                        {{ index + 1 }}
                    </span>
                    <div>
                        <h4 class="font-bold text-sm uppercase" :class="activeStep === index ? 'text-white' : 'text-grand-bone'">{{ step.title }}</h4>
                        <p class="text-xs text-grand-bone/60 truncate max-w-[200px]">{{ step.short }}</p>
                    </div>
                </div>
            </button>
        </div>

        <!-- Visualizer -->
        <div class="md:w-7/12 bg-grand-800 rounded-xl border border-grand-700 p-6 flex flex-col relative overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-grand-gold/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="relative z-10 h-full flex flex-col">
                <div class="flex items-center gap-3 mb-6">
                     <span class="px-3 py-1 rounded bg-grand-900 border border-grand-gold/30 text-grand-gold text-xs font-bold uppercase tracking-wider">
                        Paso {{ activeStep + 1 }}
                     </span>
                     <h3 class="text-2xl font-display font-bold text-white">{{ steps[activeStep].title }}</h3>
                </div>

                <div class="flex-1 bg-grand-900/50 rounded-lg border border-grand-700/50 p-6 mb-6">
                    <p class="text-lg text-grand-bone leading-relaxed mb-4">
                        {{ steps[activeStep].desc }}
                    </p>
                    
                    <div v-if="steps[activeStep].tip" class="flex gap-3 items-start p-3 rounded bg-blue-900/20 border border-blue-500/30">
                        <span class="text-blue-400 text-xl">💡</span>
                         <p class="text-sm text-blue-200/80">{{ steps[activeStep].tip }}</p>
                    </div>

                    <div v-if="steps[activeStep].damage" class="mt-4 grid grid-cols-2 gap-4">
                        <div class="p-3 rounded bg-green-900/20 border border-green-500/30 text-center">
                            <span class="block text-green-400 font-bold text-sm mb-1 uppercase">ATK ≥ DEF</span>
                            <span class="text-xs text-grand-bone/70">Ataque Exitoso (Daño/KO)</span>
                        </div>
                        <div class="p-3 rounded bg-red-900/20 border border-red-500/30 text-center">
                            <span class="block text-red-500 font-bold text-sm mb-1 uppercase">ATK < DEF</span>
                            <span class="text-xs text-grand-bone/70">Ataque Fallido (Nada)</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center text-sm text-grand-bone/50 border-t border-grand-700/50 pt-4">
                    <span>Jugador Activo (Turno)</span>
                    <span>Oponente</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const activeStep = ref(0);

const steps = [
    {
        title: 'Paso de Ataque',
        short: 'Gira tu carta y elige objetivo',
        desc: 'El jugador activo gira (Rest) su Líder o Personaje y declara el objetivo del ataque (Líder o Personaje girado del oponente).',
        tip: 'Se activan los efectos [When Attacking].'
    },
    {
        title: 'Paso de Bloqueo',
        short: 'Oponente decide si bloquea',
        desc: 'El oponente puede activar una carta con la habilidad [Blocker] para redirigir el ataque hacia ella.',
        tip: 'El bloqueador se gira (Rest) al bloquear.'
    },
    {
        title: 'Paso de Counter',
        short: 'Oponente defiende',
        desc: 'El oponente puede usar cartas de su mano con Counter (+1000/+2000) o Eventos [Counter] para aumentar el poder de su carta atacada.',
        tip: 'Los counters se descartan después de la batalla.'
    },
    {
        title: 'Resolución de Daño',
        short: 'Compara poderes',
        desc: 'Se compara el Poder final del atacante contra el del defensor.',
        damage: true
    }
];
</script>
