<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue'; 
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue'; // Assuming we have or will treat as select

const props = defineProps({
    activeListings: Array,
    soldListings: Array,
    analytics: Object,
});

const activeTab = ref('active');
const showListModal = ref(false);

const form = useForm({
    card_id: '',
    card_search: '', // For UX search
    price: '',
    condition: 'NM',
    quantity: 1,
    is_auction: false,
});

// Mock card search result
const searchResults = ref([]);
const isSearching = ref(false);

const searchCards = async () => {
    if (form.card_search.length < 3) return;
    isSearching.value = true;
    try {
        // We'd hit an API endpoint here. reusing catalog?
        // Let's assume user pastes an ID for MVP or we just use input
        // Real implementation requires an endpoint /api/cards?q=...
        // For MVP, lets assume user enters Code (OP01-001) which we resolve on backend or let them type.
    } catch (e) {}
    isSearching.value = false;
};

// Simple ID input for MVP
const submitListing = () => {
    // Ideally we resolve card_id from search.
    // Hack: User enters ID manually? No, they probably should search.
    // Let's rely on user entering the exact ID for this iteration or create a simple search feature later.
    // Or we link from the Catalog Page "Sell This Card" button which populates this form.
    
    // For now, let's assume we are triggered from Catalog mainly, 
    // OR we allow typing the ID. 
    // The Controller expects 'card_id' which is the DB ID, not the string code...
    // Uh oh. The controller validation says: 'exists:cards,id'.
    // The User knows the Code (OP01-001) not the internal ID (532).
    
    // ADJUSTMENT: We need a way to resolve Code -> ID.
    // I will update the form to send 'card_code' and have the controller resolve it.
    // For now, let's assume the user enters the ID if they know it (unlikely) 
    // OR more likely, they clicked "sell" from the catalog.
    
    form.post(route('sales.store'), {
        onSuccess: () => {
            showListModal.value = false;
            form.reset();
        }
    });
};

const deleteListing = (id) => {
    if (confirm('¿Estás seguro de retirar este producto?')) {
        router.delete(route('sales.destroy', id));
    }
};
</script>

<template>
    <div class="min-h-screen bg-grand-900 text-white font-sans">
        <Head title="Mis Ventas - Grand Line Cards" />

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-display font-bold text-grand-gold">Gestión de Ventas</h1>
                <div class="flex gap-4">
                     <Link href="/cartas" class="bg-grand-700 hover:bg-grand-600 text-white px-4 py-2 rounded transition-colors">
                        + Vender desde Catálogo
                     </Link>
                </div>
            </div>

            <!-- Stats Section -->
            <div v-if="analytics" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Revenue -->
                <div class="bg-grand-800 p-6 rounded-lg border border-grand-700 shadow flex flex-col items-center">
                    <span class="text-grand-400 text-sm uppercase tracking-wider font-bold mb-2">Ingresos Totales</span>
                    <span class="text-3xl font-display text-grand-gold">{{ new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(analytics.total_revenue) }}</span>
                </div>
                <!-- Sold Count -->
                <div class="bg-grand-800 p-6 rounded-lg border border-grand-700 shadow flex flex-col items-center">
                    <span class="text-grand-400 text-sm uppercase tracking-wider font-bold mb-2">Cartas Vendidas</span>
                    <span class="text-3xl font-display text-white">{{ analytics.items_sold }}</span>
                </div>
                <!-- Avg Price -->
                <div class="bg-grand-800 p-6 rounded-lg border border-grand-700 shadow flex flex-col items-center">
                     <span class="text-grand-400 text-sm uppercase tracking-wider font-bold mb-2">Precio Medio</span>
                    <span class="text-3xl font-display text-white">{{ new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(analytics.average_price) }}</span>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-grand-700 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button 
                        @click="activeTab = 'active'"
                        :class="[activeTab === 'active' ? 'border-grand-gold text-grand-gold' : 'border-transparent text-grand-400 hover:text-grand-200 hover:border-grand-500', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors']"
                    >
                        Listados Activos
                    </button>
                    <button 
                        @click="activeTab = 'sold'"
                        :class="[activeTab === 'sold' ? 'border-grand-gold text-grand-gold' : 'border-transparent text-grand-400 hover:text-grand-200 hover:border-grand-500', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors']"
                    >
                        Historial de Ventas
                    </button>
                    <button 
                        @click="activeTab = 'analytics'"
                        :class="[activeTab === 'analytics' ? 'border-grand-gold text-grand-gold' : 'border-transparent text-grand-400 hover:text-grand-200 hover:border-grand-500', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors']"
                    >
                        Analítica (Beta)
                    </button>
                </nav>
            </div>

            <!-- Active Listings -->
            <div v-if="activeTab === 'active'">
                <div v-if="activeListings.length > 0" class="bg-grand-800 shadow overflow-hidden sm:rounded-md border border-grand-700">
                    <ul role="list" class="divide-y divide-grand-700">
                        <li v-for="listing in activeListings" :key="listing.id">
                            <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                     <img :src="listing.card.image_url" class="h-16 w-12 object-cover rounded border border-grand-600" />
                                     <div>
                                         <p class="text-lg font-bold text-grand-bone">{{ listing.card.card_id }}</p>
                                         <p class="text-sm text-grand-400">Condición: {{ listing.condition }} | Stock: {{ listing.quantity }}</p>
                                         <p class="text-grand-gold font-bold">{{ listing.price }} €</p>
                                     </div>
                                </div>
                                <div class="flex items-center gap-2">
                                     <button @click="deleteListing(listing.id)" class="text-red-400 hover:text-red-300 text-sm font-bold">
                                         Retirar
                                     </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-center py-12 bg-grand-800 rounded-lg border border-dashed border-grand-600">
                    <p class="text-grand-400 mb-4">No tienes cartas a la venta.</p>
                    <Link href="/cartas" class="text-grand-gold hover:underline">Ir al catálogo para vender</Link>
                </div>
            </div>

             <!-- Sold Listings -->
            <div v-if="activeTab === 'sold'">
                 <div v-if="soldListings.length > 0" class="bg-grand-800 shadow overflow-hidden sm:rounded-md border border-grand-700">
                    <div class="px-4 py-4 sm:px-6 text-grand-gold font-bold bg-grand-900/50">
                        ¡Recuerda ir a "Mis Envíos" para enviar tus cartas vendidas al Vault!
                    </div>
                    <ul role="list" class="divide-y divide-grand-700">
                         <li v-for="listing in soldListings" :key="listing.id">
                            <div class="px-4 py-4 sm:px-6 flex items-center justify-between opacity-75">
                                <div class="flex items-center gap-4">
                                     <img :src="listing.card.image_url" class="h-16 w-12 object-cover rounded border border-grand-600 grayscale" />
                                     <div>
                                         <p class="text-lg font-bold text-grand-bone">{{ listing.card.card_id }}</p>
                                         <p class="text-sm text-lime-400 font-bold uppercase">{{ listing.status }}</p>
                                         <p class="text-grand-400 text-xs">Vendido por: {{ listing.price }} €</p>
                                     </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                 </div>
                 <div v-else class="text-center py-12 bg-grand-800 rounded-lg border border-dashed border-grand-600">
                    <p class="text-grand-400">Aún no has vendido nada.</p>
                </div>
            </div>

            <!-- Analytics Tab (Chart) -->
            <div v-if="activeTab === 'analytics' && analytics">
                <div class="bg-grand-800 shadow sm:rounded-lg border border-grand-700 p-6">
                    <h2 class="text-xl font-bold text-grand-bone mb-6">Ventas últimos 30 días</h2>
                    
                    <div class="h-64 flex items-end gap-1 overflow-x-auto pb-4 custom-scrollbar">
                        <div v-for="(amount, date) in analytics.chart_data" :key="date" class="flex-1 min-w-[30px] flex flex-col items-center group">
                             <div 
                                class="w-full bg-grand-gold/50 hover:bg-grand-gold transition-all duration-300 rounded-t relative group-hover:shadow-[0_0_10px_rgba(212,175,55,0.5)]"
                                :style="{ height: amount > 0 ? (amount / Math.max(...Object.values(analytics.chart_data)) * 200) + 'px' : '4px' }"
                             ></div>
                             <span class="text-[10px] text-grand-500 mt-2 rotate-45 transform origin-left md:rotate-0 truncate w-full text-center group-hover:text-grand-bone">{{ date.slice(5) }}</span> <!-- Show MM-DD -->
                        </div>
                    </div>
                    <p class="text-center text-xs text-grand-500 mt-4 italic">El eje Y escala dinámicamente según tu mejor día de ventas.</p>
                </div>
            </div>

        </div>
    </div>
</template>
