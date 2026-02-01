<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ArrowRightIcon } from '@heroicons/vue/24/outline';
</script>

<template>
    <AppLayout>
        <Head title="Inicio" />

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <!-- Background gradients -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-blue-600/20 rounded-full blur-[100px] -z-10"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 text-center">
                <h1 class="text-5xl md:text-7xl font-display font-bold text-white mb-6">
                    La Ruta al <span class="text-transparent bg-clip-text bg-gradient-to-r from-grand-gold to-yellow-200">Rey de los Piratas</span>
                </h1>
                <p class="text-xl text-slate-400 max-w-2xl mx-auto mb-10">
                    La base de datos en español más completa de One Piece Card Game. 
                    Traduce cartas, construye mazos y domina el meta.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link :href="route('catalog.index')" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-full text-grand-900 bg-grand-gold hover:bg-yellow-400 transition shadow-lg shadow-orange-500/20">
                        Explorar Cartas
                    </Link>
                    <Link :href="route('meta.index')" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-full text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/10 transition">
                        Ver Meta Actual
                    </Link>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card Database -->
                <Link :href="route('catalog.index')" class="p-8 rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700 hover:border-grand-gold/50 transition group cursor-pointer block">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition">
                        <span class="text-2xl">🔍</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Traducciones Precisas</h3>
                    <p class="text-slate-400 mb-6">Accede a miles de cartas traducidas al español con erratas corregidas y FAQs oficiales.</p>
                    <div class="text-blue-400 flex items-center text-sm font-bold group-hover:translate-x-2 transition">
                        Buscar Cartas <ArrowRightIcon class="w-4 h-4 ml-2"/>
                    </div>
                </Link>

                <!-- Deck Builder -->
                <Link :href="route('profile.decks.index')" class="p-8 rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700 hover:border-grand-gold/50 transition group cursor-pointer block">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition">
                        <span class="text-2xl">🃏</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Constructor de Mazos</h3>
                    <p class="text-slate-400 mb-6">Crea, guarda y comparte tus estrategias. Analiza tu curva de coste y tipos.</p>
                    <div class="text-purple-400 flex items-center text-sm font-bold group-hover:translate-x-2 transition">
                        Crear Mazo <ArrowRightIcon class="w-4 h-4 ml-2"/>
                    </div>
                </Link>

                <!-- Market -->
                <Link :href="route('shop.index')" class="p-8 rounded-2xl bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700 hover:border-grand-gold/50 transition group cursor-pointer block">
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center mb-6 group-hover:scale-110 transition">
                        <span class="text-2xl">💎</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Distribuidor Oficial</h3>
                    <p class="text-slate-400 mb-6">Compra sobres, cajas y cartas sueltas con la garantía de una tienda oficial.</p>
                    <div class="text-green-400 flex items-center text-sm font-bold group-hover:translate-x-2 transition">
                        Ir a la Tienda <ArrowRightIcon class="w-4 h-4 ml-2"/>
                    </div>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
