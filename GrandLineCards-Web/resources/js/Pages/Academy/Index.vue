<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ObjectiveCards from './Components/ObjectiveCards.vue';
import CardAnatomy from './Components/CardAnatomy.vue';
import BattlefieldMap from './Components/BattlefieldMap.vue';
import TurnFlow from './Components/TurnFlow.vue';
import CombatSimulator from './Components/CombatSimulator.vue';
import StarterDeckRecommendation from './Components/StarterDeckRecommendation.vue';
import battleBg from '../../../images/battle-bg.svg';

defineProps({
    starterDecks: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <AppLayout>
        <Head title="Academia Pirata - Aprende a Jugar" />

        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-grand-900 border-b border-grand-700">
             <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-900/20 to-transparent pointer-events-none"></div>
             
             <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 relative z-10">
                <span class="inline-block px-4 py-1 rounded-full bg-grand-gold/10 border border-grand-gold text-grand-gold text-sm font-bold tracking-wider uppercase mb-6">
                    Guía para Principiantes
                </span>
                <h1 class="text-4xl md:text-6xl font-display font-bold text-white mb-6 leading-tight">
                    Conviértete en el <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-grand-gold to-yellow-200">Rey de los Juegos de Cartas</span>
                </h1>
                <p class="text-xl text-gray-300 max-w-2xl leading-relaxed">
                    Domina las reglas de One Piece TCG en menos de 5 minutos. Aprende las condiciones de victoria, la anatomía de las cartas y la secuencia de turnos antes de zarpar.
                </p>
             </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-24">
            
            <!-- 1. Win Conditions -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-display font-bold text-white mb-4">Objetivo del Juego</h2>
                    <p class="text-gray-400 max-w-2xl mx-auto">Para ganar, debes cumplir una de estas dos condiciones. ¡Elige tu estrategia!</p>
                </div>
                <ObjectiveCards />
            </section>

            <!-- 2. Card Anatomy -->
            <section class="bg-white/5 rounded-3xl p-8 md:p-12 border border-white/10">
                <div class="mb-12">
                     <h2 class="text-3xl font-display font-bold text-white mb-4">Anatomía de una Carta</h2>
                    <p class="text-gray-400">Pasa el ratón (o toca) las diferentes partes de la carta para entender su función.</p>
                </div>
                <div class="flex justify-center">
                    <CardAnatomy />
                </div>
            </section>

            <!-- 3. Battlefield -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-display font-bold text-white mb-4">El Campo de Batalla</h2>
                    <p class="text-gray-400 max-w-2xl mx-auto">Conoce las zonas donde colocarás tus cartas durante la partida.</p>
                </div>
                <BattlefieldMap />
            </section>

            <!-- 4. Turn Flow -->
            <section class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-display font-bold text-white mb-4">Secuencia del Turno</h2>
                    <p class="text-gray-400">Sigue estos pasos en cada uno de tus turnos.</p>
                </div>
                <TurnFlow />
            </section>

            <!-- 5. Combat Mechanics -->
            <section class="bg-gray-900 rounded-3xl p-8 md:p-16 border border-gray-700 shadow-2xl overflow-hidden relative">
                <div class="absolute inset-0 opacity-10 mix-blend-overlay" :style="{ backgroundImage: `url(${battleBg})` }"></div>
                <div class="relative z-10">
                    <div class="text-center mb-12">
                        <span class="text-red-500 font-bold uppercase tracking-widest text-sm">Simulador de Batalla</span>
                        <h2 class="text-3xl font-display font-bold text-white mt-2 mb-4">Calcula tu Poder</h2>
                        <p class="text-gray-400 max-w-2xl mx-auto">
                            Añade cartas DON!! a tu personaje para aumentar su poder. Recuerda que el oponente también puede usar Counter.
                        </p>
                    </div>
                    <CombatSimulator />
                </div>
            </section>

            <!-- 6. Conversion / Starter Deck -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-display font-bold text-white mb-4">Tu Primer Paso</h2>
                    <p class="text-gray-400 max-w-2xl mx-auto">
                        No necesitas cartas sueltas para empezar. Los Starter Decks están listos para jugar nada más sacarlos de la caja.
                    </p>
                </div>
                
                <div v-for="deck in starterDecks" :key="deck.id" class="max-w-5xl mx-auto">
                    <StarterDeckRecommendation :deck="deck" />
                </div>
            </section>

        </div>
    </AppLayout>
</template>
