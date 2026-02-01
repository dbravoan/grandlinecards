<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { FunnelIcon, PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    availableCards: Array, // Mocked for now
});

const deck = ref([]);
const filters = ref({
    color: 'All',
    cost: 'All',
});

// Mock Data for UI Dev
const mockCards = [
    { id: 1, card_id: 'OP01-001', name: 'Monkey D. Luffy', cost: 5, power: 6000, color: 'Red', type: 'Character', image: 'https://via.placeholder.com/300x420' },
    { id: 2, card_id: 'OP01-002', name: 'Roronoa Zoro', cost: 3, power: 5000, color: 'Red', type: 'Character', image: 'https://via.placeholder.com/300x420' },
    { id: 3, card_id: 'OP01-003', name: 'Nami', cost: 1, power: 2000, color: 'Red', type: 'Character', image: 'https://via.placeholder.com/300x420' },
    { id: 4, card_id: 'OP02-001', name: 'Edward Newgate', cost: 6, power: 8000, color: 'Red', type: 'Leader', image: 'https://via.placeholder.com/300x420' },
];

const addToDeck = (card) => {
    if (deck.value.filter(c => c.id === card.id).length < 4) {
        deck.value.push({ ...card, uniqueId: Date.now() });
    }
};

const removeFromDeck = (uniqueId) => {
    deck.value = deck.value.filter(c => c.uniqueId !== uniqueId);
};

const deckCounts = computed(() => {
    return deck.value.length;
});

const groupedDeck = computed(() => {
    // Group by ID to show stacks
    const groups = {};
    deck.value.forEach(card => {
        if (!groups[card.id]) groups[card.id] = { ...card, count: 0 };
        groups[card.id].count++;
    });
    return Object.values(groups);
});

</script>

<template>
    <AppLayout>
        <Head title="Constructor de Mazos" />

        <div class="flex h-[calc(100vh-64px)] overflow-hidden">
            <!-- Sidebar: Card Pool -->
            <div class="w-1/3 min-w-[350px] bg-slate-900 border-r border-grand-700 flex flex-col">
                <!-- Filters -->
                <div class="p-4 border-b border-grand-700 bg-slate-800">
                    <div class="flex gap-2 mb-4">
                        <select v-model="filters.color" class="bg-grand-900 border border-grand-600 text-slate-300 rounded-lg text-sm flex-1">
                            <option value="All">Todos los Colores</option>
                            <option value="Red">Rojo</option>
                            <option value="Green">Verde</option>
                        </select>
                         <select v-model="filters.cost" class="bg-grand-900 border border-grand-600 text-slate-300 rounded-lg text-sm w-24">
                            <option value="All">Coste</option>
                            <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
                        </select>
                    </div>
                     <div class="relative">
                        <input type="text" placeholder="Buscar por nombre..." class="w-full bg-grand-900 border border-grand-600 rounded-lg text-slate-300 text-sm pl-9">
                        <FunnelIcon class="w-4 h-4 text-slate-500 absolute left-3 top-2.5" />
                    </div>
                </div>

                <!-- Card Grid -->
                <div class="flex-1 overflow-y-auto p-4 grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div v-for="card in mockCards" :key="card.id" 
                        class="relative group cursor-pointer transition hover:scale-105"
                        @click="addToDeck(card)"
                    >
                        <img :src="card.image" class="rounded-lg shadow-black shadow-md w-full aspect-[2.5/3.5] object-cover border border-slate-700">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center rounded-lg transition">
                            <PlusIcon class="w-8 h-8 text-white font-bold" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Area: Deck Canvas -->
            <div class="flex-1 bg-texture bg-slate-800 p-8 flex flex-col relative">
                <!-- Deck Stats Header -->
                <div class="flex justify-between items-center mb-8 bg-slate-900/50 p-4 rounded-xl border border-white/5 backdrop-blur-sm">
                    <div>
                        <h2 class="text-2xl font-display font-bold text-white">Nuevo Mazo</h2>
                        <span class="text-slate-400 text-sm">Leader: Monkey D. Luffy</span>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold font-mono text-grand-gold">{{ deckCounts }}<span class="text-slate-500 text-lg">/50</span></div>
                        <div class="text-xs text-slate-400 uppercase tracking-widest">Cartas</div>
                    </div>
                </div>

                <!-- Deck Visualizer -->
                <div class="flex-1 overflow-y-auto">
                    <!-- Leader Area -->
                    <div class="mb-8 flex justify-center">
                        <div class="w-48 h-64 border-2 border-dashed border-slate-600 rounded-xl flex items-center justify-center text-slate-500">
                            Arrastra tu Líder aquí
                        </div>
                    </div>

                    <!-- Main Deck Area -->
                    <div class="flex flex-wrap gap-4 justify-center">
                        <div v-for="card in groupedDeck" :key="card.id" class="relative group w-32">
                           <img :src="card.image" class="rounded-lg shadow-xl shadow-black/40 border border-slate-600">
                           <div class="absolute -top-2 -right-2 bg-grand-gold text-grand-900 font-bold w-6 h-6 rounded-full flex items-center justify-center shadow-lg border border-white">
                               {{ card.count }}
                           </div>
                           <div class="absolute inset-0 bg-red-500/50 opacity-0 group-hover:opacity-100 rounded-lg transition flex items-center justify-center cursor-pointer"
                                @click="removeFromDeck(card.uniqueId)">
                               <TrashIcon class="w-8 h-8 text-white" />
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.bg-texture {
    background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.05) 1px, transparent 0);
    background-size: 20px 20px;
}
</style>
