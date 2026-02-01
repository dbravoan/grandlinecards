<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';
import { Bar } from 'vue-chartjs';
import axios from 'axios';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth.user);

const deckName = ref('Mi Nuevo Mazo');
const deckCount = ref(0);
const maxDeckSize = 50;

// Mock Data for Chart
const chartData = {
  labels: ['1', '2', '3', '4', '5', '6+', 'Eventos', 'Escenarios'],
  datasets: [
    {
      label: 'Curva de Coste',
      backgroundColor: '#FFC300',
      data: [4, 8, 12, 10, 6, 4, 4, 2]
    }
  ]
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false }
    },
    scales: {
        y: { 
            beginAtZero: true,
            grid: { color: '#1E293B' },
            ticks: { color: '#94A3B8' }
        },
        x: {
            grid: { display: false },
            ticks: { color: '#94A3B8' }
        }
    }
}

const saveDeck = async () => {
    if (!isAuthenticated.value) {
        alert('Debes iniciar sesión para guardar tu mazo.');
        return;
    }

    try {
        await axios.post('/api/v1/decks', {
            name: deckName.value,
            leader_id: 'OP01-001', // Mock Leader
            is_public: true,
            cards: [
                { id: 'OP01-006', quantity: 4 }, // Mock cards
                { id: 'OP01-016', quantity: 4 }
            ]
        });
        alert('¡Mazo guardado con éxito en tu perfil!');
    } catch (error) {
        console.error(error);
        alert('Error al guardar el mazo. Inténtalo de nuevo.');
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Constructor de Mazos" />

        <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-160px)]">
                <!-- Left: Card Pool (Search & Results) -->
            <div class="w-full lg:w-1/2 flex flex-col bg-grand-800 rounded-lg border border-grand-700 overflow-hidden">
                <div class="p-4 border-b border-grand-700 bg-grand-900">
                    <input type="text" placeholder="Buscar cartas..." class="w-full bg-grand-800 border border-grand-700 rounded p-2 text-grand-bone focus:border-grand-gold outline-none">
                </div>
                <div class="flex-1 p-4 overflow-y-auto grid grid-cols-4 gap-2">
                    <!-- Mocks -->
                    <div v-for="i in 12" :key="i" class="aspect-[2/3] bg-grand-700 rounded border border-grand-600 hover:border-grand-gold cursor-pointer"></div>
                </div>
            </div>

            <!-- Right: Deck Stats & List -->
            <div class="w-full lg:w-1/2 flex flex-col gap-4">
                <!-- Chart & Stats -->
                <div class="h-1/3 bg-grand-800 rounded-lg border border-grand-700 p-4 relative">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-display font-bold text-grand-gold">Curva de Coste</h3>
                        <span class="text-grand-bone font-mono">{{ deckCount }} / {{ maxDeckSize }} Cartas</span>
                    </div>
                    <div class="h-32">
                        <Bar :data="chartData" :options="chartOptions" />
                    </div>
                </div>

                <!-- Deck List -->
                <div class="flex-1 bg-grand-800 rounded-lg border border-grand-700 p-4 overflow-y-auto flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <input v-model="deckName" class="bg-transparent border-b border-grand-gold text-grand-gold font-bold focus:outline-none w-1/2" />
                        
                        <button v-if="isAuthenticated" @click="saveDeck" class="bg-grand-gold text-grand-900 px-4 py-1 rounded font-bold hover:bg-white transition-colors">
                            Guardar Mazo
                        </button>
                        <Link v-else href="/login" class="text-grand-400 text-sm hover:text-grand-gold underline">
                            Ingresa para guardar
                        </Link>
                    </div>

                    <div class="space-y-1 flex-1">
                        <div v-for="i in 5" :key="i" class="flex justify-between items-center p-2 bg-grand-900 rounded border border-grand-700">
                            <span>Luffy (OP01-001)</span>
                            <span class="font-bold text-grand-gold">x4</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
