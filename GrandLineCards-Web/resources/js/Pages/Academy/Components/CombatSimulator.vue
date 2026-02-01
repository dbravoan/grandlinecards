<script setup>
import { ref, computed } from 'vue';

const basePower = 5000;
const donAttached = ref(0);
const opponentPower = 6000;

const currentPower = computed(() => basePower + (donAttached.value * 1000));
const formatNumber = (num) => num.toLocaleString();

const addDon = () => {
    if (donAttached.value < 10) donAttached.value++;
};

const removeDon = () => {
    if (donAttached.value > 0) donAttached.value--;
};
</script>

<template>
    <div class="bg-grand-800 p-8 rounded-xl border border-grand-700 max-w-2xl mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between gap-12">
            <!-- Attacker -->
            <div class="text-center group relative">
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-bold uppercase tracking-wider rounded-full mb-2">Atacante</span>
                    <div class="w-32 h-44 bg-gray-700 rounded-lg border-2 border-red-500 relative mx-auto flex items-center justify-center overflow-visible transition-transform duration-300"
                        :class="{'scale-105': donAttached > 0}"
                    >
                         <!-- DON!! Cards Visualization -->
                        <div v-for="n in donAttached" :key="n" 
                             class="absolute w-full h-full bg-white rounded-lg border border-black shadow-lg"
                             :style="{ transform: `translate(${n * 4}px, ${n * 4}px) rotate(${n * 2}deg)`, zIndex: -1 }"
                        >
                            <span class="absolute bottom-1 right-1 text-[10px] text-black font-bold">DON!!</span>
                        </div>
                        
                        <div class="text-4xl font-black text-white z-10 drop-shadow-md">
                            {{ formatNumber(currentPower) }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-4">
                    <button @click="removeDon" class="w-8 h-8 rounded bg-gray-700 hover:bg-gray-600 outline outline-1 outline-gray-500">-</button>
                    <span class="font-bold text-grand-gold">{{ donAttached }} DON!!</span>
                    <button @click="addDon" class="w-8 h-8 rounded bg-grand-500 hover:bg-grand-400 outline outline-1 outline-blue-400 font-bold">+</button>
                </div>
            </div>

            <!-- VS Badge -->
            <div class="text-2xl font-black font-display text-gray-500 italic">VS</div>

            <!-- Defender -->
            <div class="text-center opacity-75">
                <span class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-bold uppercase tracking-wider rounded-full mb-2">Defensor</span>
                <div class="w-32 h-44 bg-gray-700 rounded-lg border-2 border-blue-500 mx-auto flex items-center justify-center">
                    <div class="text-3xl font-bold text-gray-400">
                        {{ formatNumber(opponentPower) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Result Bar -->
        <div class="mt-8 pt-8 border-t border-grand-700 text-center">
            <p class="text-lg mb-2 text-gray-300">Resultado del combate:</p>
            <div class="text-2xl font-bold font-display uppercase tracking-widest transition-colors duration-300"
                :class="currentPower >= opponentPower ? 'text-green-400' : 'text-red-400'"
            >
                {{ currentPower >= opponentPower ? '¡Victoria! (Daño infligido)' : 'Derrota (Bloqueado)' }}
            </div>
            <p class="text-sm text-gray-500 mt-2">
                {{ currentPower >= opponentPower 
                    ? `Tu ataque de ${formatNumber(currentPower)} supera los ${formatNumber(opponentPower)} del oponente.` 
                    : `Necesitas +${formatNumber(opponentPower - currentPower + 1000)} de poder (o más) para vencer.` 
                }}
            </p>
        </div>
    </div>
</template>
