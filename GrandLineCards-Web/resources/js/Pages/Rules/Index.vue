<template>
    <div class="min-h-screen bg-grand-900 text-grand-bone font-sans pb-20">
        <!-- Hero Header -->
        <header class="bg-grand-800 border-b border-grand-gold/30 p-4 sticky top-0 z-20 shadow-lg">
            <h1 class="text-xl md:text-2xl font-display text-grand-gold text-center uppercase tracking-widest">
                Reglas Rápidas
            </h1>
        </header>

        <!-- Main Content -->
        <main class="max-w-5xl mx-auto p-4 md:p-8">
            <InteractiveRules />

            <!-- CTA -->
            <div class="mt-12 bg-grand-800/50 border border-grand-gold/30 rounded-2xl p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-grand-gold to-transparent opacity-50"></div>
                
                <h3 class="text-grand-gold font-display text-2xl mb-3">¿Listo para zarpar?</h3>
                <p class="text-grand-bone/80 mb-6 max-w-xl mx-auto">Ya conoces las reglas básicas. Es hora de conseguir tu primer mazo y empezar tu aventura en Grand Line.</p>
                
                <Link href="/tienda" class="inline-flex items-center gap-2 bg-grand-gold text-grand-900 font-bold py-3 px-8 rounded-full hover:bg-grand-accent transition-all hover:scale-105 shadow-lg shadow-grand-gold/20">
                    <span>Ver Starter Decks</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import InteractiveRules from '@/Components/Rules/InteractiveRules.vue';
</script>

<style scoped>
html {
    scroll-behavior: smooth;
}
</style>
