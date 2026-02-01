<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { FunnelIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline'; // Assuming configured

const props = defineProps({
    sealed: Array,
    singles: Object, // Paginator Object
    filters: Object,
});

// Filters State
const form = ref({
    q: props.filters.q || '',
    color: props.filters.color || '',
    rarity: props.filters.rarity || '',
    set: props.filters.set || '',
});

// Watch for changes (Debounce search could be better, but direct apply for now)
const applyFilters = () => {
    router.get(route('shop.index'), form.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Reset
const resetFilters = () => {
    form.value = { q: '', color: '', rarity: '', set: '' };
    applyFilters();
};

const cart = ref([]);
const isCartOpen = ref(false);
const isProcessing = ref(false);
const cartAnimating = ref(false);

const addToCart = (product) => {
    cart.value.push(product);
    isCartOpen.value = true;
    
    // Simple animation trigger
    cartAnimating.value = true;
    setTimeout(() => cartAnimating.value = false, 3000);
};

const removeFromCart = (index) => {
    cart.value.splice(index, 1);
};

const cartTotal = computed(() => {
    return cart.value.reduce((total, item) => total + (item.price || item.min_price), 0);
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(price);
}

const goToSingle = (cardId) => {
    router.get(route('catalog.show', cardId));
};

const checkout = () => {
    if (cart.value.length === 0) return;
    
    isProcessing.value = true;

    // Transform cart for backend
    // Simple aggregation or 1-to-1 mapping
    const cartPayload = cart.value.map(item => ({
        id: item.id,
        quantity: 1 // Default 1 for now
    }));

    router.post(route('marketplace.checkout'), {
        cart: cartPayload
    }, {
        onFinish: () => isProcessing.value = false
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Bazar Grand Line" />

        <div class="max-w-7xl mx-auto py-8 relative px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8 border-b border-grand-gold/20 pb-4">
                 <h1 class="font-display text-4xl text-grand-gold uppercase tracking-widest">Bazar Grand Line</h1>
                 <button @click="isCartOpen = !isCartOpen" class="flex items-center gap-2 text-grand-bone hover:text-grand-gold transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span class="font-bold">({{ cart.length }})</span>
                 </button>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- FILTER SIDEBAR -->
                <div class="w-full lg:w-64 flex-shrink-0 space-y-6">
                    <div class="bg-grand-800 p-4 rounded-lg border border-grand-700">
                        <h3 class="font-bold text-grand-gold uppercase mb-4 flex items-center gap-2">
                            <FunnelIcon class="w-5 h-5"/> Filtros
                        </h3>

                        <!-- Search -->
                        <div class="mb-4">
                            <label class="block text-xs text-grand-300 uppercase mb-1">Buscar</label>
                            <input v-model="form.q" @change="applyFilters" type="text" placeholder="Luffy, Zoro..." 
                                   class="w-full bg-grand-900 border border-grand-600 rounded px-3 py-2 text-white placeholder-grand-500 focus:border-grand-gold focus:ring-0">
                        </div>

                        <!-- Color -->
                        <div class="mb-4">
                            <label class="block text-xs text-grand-300 uppercase mb-1">Color</label>
                            <select v-model="form.color" @change="applyFilters" class="w-full bg-grand-900 border border-grand-600 rounded px-3 py-2 text-white focus:border-grand-gold">
                                <option value="">Todos</option>
                                <option value="Red">Red</option>
                                <option value="Green">Green</option>
                                <option value="Blue">Blue</option>
                                <option value="Purple">Purple</option>
                                <option value="Black">Black</option>
                                <option value="Yellow">Yellow</option>
                            </select>
                        </div>

                         <!-- Rarity -->
                        <div class="mb-4">
                            <label class="block text-xs text-grand-300 uppercase mb-1">Rareza</label>
                            <select v-model="form.rarity" @change="applyFilters" class="w-full bg-grand-900 border border-grand-600 rounded px-3 py-2 text-white focus:border-grand-gold">
                                <option value="">Todas</option>
                                <option value="L">Leader (L)</option>
                                <option value="C">Common (C)</option>
                                <option value="UC">Uncommon (UC)</option>
                                <option value="R">Rare (R)</option>
                                <option value="SR">Super Rare (SR)</option>
                                <option value="SEC">Secret (SEC)</option>
                            </select>
                        </div>
                        
                         <!-- Set -->
                        <div class="mb-4">
                             <label class="block text-xs text-grand-300 uppercase mb-1">Colección</label>
                             <!-- In real app, loop available sets -->
                             <select v-model="form.set" @change="applyFilters" class="w-full bg-grand-900 border border-grand-600 rounded px-3 py-2 text-white focus:border-grand-gold">
                                <option value="">Todas</option>
                                <option value="OP01">OP01 - Romance Dawn</option>
                                <option value="OP02">OP02 - Paramount War</option>
                                <option value="OP03">OP03 - Pillars of Strength</option>
                                <option value="OP04">OP04 - Kingdoms of Intrigue</option>
                                <option value="ST01">ST01 - Straw Hat Crew</option>
                            </select>
                        </div>

                        <button @click="resetFilters" class="w-full py-2 border border-grand-600 text-grand-400 hover:text-white hover:border-white transition-colors text-xs uppercase rounded">
                            Limpiar Filtros
                        </button>
                    </div>
                </div>

                <!-- MAIN CONTENT -->
                <div class="flex-1">
                    <!-- SEALED SECTION (Only show if no search filter active, strictly speaking? Or always show?) -->
                    <!-- Let's hide Sealed if searching for Singles explicitly to reduce noise? -->
                    <!-- Or just keep it at top -->
                    <div v-if="!form.q && !form.color && !form.rarity && !form.set" class="mb-12">
                        <h2 class="font-display text-2xl text-grand-gold mb-6 border-l-4 border-grand-gold pl-4">Producto Sellado</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div v-for="product in props.sealed" :key="product.id" class="group bg-grand-800 rounded-lg overflow-hidden border border-grand-700 hover:border-grand-gold transition-all duration-300 hover:shadow-2xl hover:shadow-grand-gold/10 flex flex-col">
                                <div class="aspect-square bg-grand-900 relative overflow-hidden p-4 flex items-center justify-center">
                                    <img :src="product.image_url" :alt="product.name" class="object-contain h-full w-full transform group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="p-4 flex-1 flex flex-col">
                                    <div class="flex justify-between items-start mb-2">
                                         <span class="text-xs font-bold text-grand-500 uppercase">Sellado</span>
                                         <span class="font-display font-bold text-lg text-grand-gold">{{ formatPrice(product.price) }}</span>
                                    </div>
                                    <h3 class="font-bold text-grand-bone mb-2 leading-tight">
                                        {{ product.name }}
                                        <span v-if="product.is_new" class="ml-2 bg-green-500 text-white text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wider font-bold">Nuevo</span>
                                    </h3>
                                    <button @click="addToCart(product)" class="mt-auto w-full py-2 bg-grand-900 border border-grand-gold text-grand-gold font-bold uppercase text-xs tracking-wider hover:bg-grand-gold hover:text-grand-900 transition-colors rounded">
                                        Añadir al Carrito
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SINGLES SECTION -->
                    <div>
                        <h2 class="font-display text-2xl text-grand-gold mb-6 border-l-4 border-orange-500 pl-4">
                            Singles (Mercado P2P)
                            <span v-if="form.q" class="text-sm text-grand-400 ml-2 normal-case font-sans">- Buscando "{{ form.q }}"</span>
                        </h2>
                        
                        <div v-if="singles.data.length === 0" class="text-grand-400 italic bg-grand-800 p-8 rounded border border-grand-700 text-center">
                            No se encontraron cartas con estos filtros.
                        </div>
                        
                        <div v-else>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">
                                <div v-for="single in props.singles.data" :key="single.id" @click="goToSingle(single.id)" class="cursor-pointer group bg-grand-800 rounded-lg overflow-hidden border border-grand-700 hover:border-orange-500 transition-all duration-300 hover:shadow-lg flex flex-col">
                                     <div class="aspect-[2/3] relative overflow-hidden">
                                        <img :src="single.image_url" :alt="single.name" class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-300">
                                        <!-- Overlay Badge -->
                                        <div class="absolute bottom-0 inset-x-0 bg-black/80 p-2 flex justify-between items-center">
                                            <div class="flex flex-col">
                                                <span class="text-xs text-grand-300">Desde</span>
                                                <span class="font-bold text-orange-400">{{ formatPrice(single.min_price) }}</span>
                                            </div>
                                            <span class="bg-grand-700 text-xs text-white px-2 py-1 rounded-full border border-grand-600">
                                                {{ single.listing_count }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                         <h3 class="font-bold text-sm text-white truncate">{{ single.name }}</h3>
                                         <p class="text-xs text-grand-400 truncate">{{ single.description }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <Pagination :links="singles.links" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Slide-over -->
            <div v-if="isCartOpen" class="fixed inset-0 z-50 flex justify-end">
                <div @click="isCartOpen = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                <div class="relative w-full max-w-md bg-grand-900 h-full shadow-2xl p-6 border-l border-grand-gold/30 flex flex-col">
                     <div class="flex justify-between items-center mb-6">
                        <h2 class="font-display text-2xl text-grand-gold">Tu Botín</h2>
                        <button @click="isCartOpen = false" class="text-grand-400 hover:text-white">&times;</button>
                     </div>
                     <div class="flex-1 overflow-y-auto space-y-4">
                        <div v-if="cart.length === 0" class="text-center text-grand-500 py-12">Vacío</div>
                        <div v-else v-for="(item, index) in cart" :key="index" class="flex gap-4 items-center bg-grand-800 p-3 rounded border border-grand-700">
                            <img :src="item.image_url" class="w-12 h-12 object-contain bg-grand-900 rounded">
                            <div class="flex-1">
                                <div class="text-sm font-bold text-grand-bone">{{ item.name }}</div>
                            </div>
                            <button @click="removeFromCart(index)" class="text-red-500">&times;</button>
                        </div>
                     </div>
                     <div class="mt-6 border-t border-grand-700 pt-4">
                     <div class="mt-6 border-t border-grand-700 pt-4">
                        <button @click="checkout" :disabled="isProcessing" class="w-full py-3 bg-grand-gold text-grand-900 font-bold uppercase rounded hover:bg-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2">
                            <svg v-if="isProcessing" class="animate-spin h-5 w-5 text-grand-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-if="isProcessing">Procesando...</span>
                            <span v-else>Tramitar Pedido</span>
                        </button>
                     </div>
                     </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
