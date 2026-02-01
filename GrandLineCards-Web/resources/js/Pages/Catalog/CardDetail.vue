<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
} from 'chart.js';
import { Line } from 'vue-chartjs';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
);

const props = defineProps({
    card: Object,
    priceHistory: Array,
    listings: Array,
    variants: Array,
    collectionItem: Object,
});

const isSpanish = ref(true);

const toggleLanguage = () => {
    isSpanish.value = !isSpanish.value;
};

// Foil Effect Logic
const cardElement = ref(null);
const style = ref({});

const handleMouseMove = (e) => {
    if (!cardElement.value) return;

    const { left, top, width, height } = cardElement.value.getBoundingClientRect();
    const x = (e.clientX - left) / width;
    const y = (e.clientY - top) / height;

    const rX = (0.5 - x) * 20;
    const rY = (0.5 - y) * 20;

    const bgX = 50 + (x - 0.5) * 50;
    const bgY = 50 + (y - 0.5) * 50;

    style.value = {
        transform: `perspective(1000px) rotateY(${rX}deg) rotateX(${rY}deg)`,
        '--mx': `${x * 100}%`,
        '--my': `${y * 100}%`,
        '--tx': `${bgX}%`,
        '--ty': `${bgY}%`,
        '--op': Math.abs(x - 0.5) + Math.abs(y - 0.5), // Opacity intensity
    };
};

const resetCard = () => {
    style.value = {
        transform: 'perspective(1000px) rotateY(0deg) rotateX(0deg)',
        '--mx': '50%',
        '--my': '50%',
        '--tx': '50%',
        '--ty': '50%',
        '--op': 0,
    };
};

// Computed Translation Accessor
const translation = computed(() => {
    if (!props.card.translations || !Array.isArray(props.card.translations)) {
        return { 
            name: props.card.name, 
            effect_text: props.card.effect_text, 
            trigger_text: props.card.trigger_text 
        };
    }
    
    const targetLocale = isSpanish.value ? 'es' : 'en';
    const found = props.card.translations.find(t => t.locale === targetLocale);
    
    return found || props.card.translations[0] || {};
});

// JSON-LD
const jsonLd = computed(() => ({
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": `One Piece TCG - ${translation.value.name || props.card.card_id}`,
    "image": props.card.image_url,
    "description": translation.value.effect_text,
    "sku": props.card.card_id,
    "brand": {
        "@type": "Brand",
        "name": "Bandai Namco"
    },
}));

// Chart Data
const chartData = computed(() => {
    if (!props.priceHistory || props.priceHistory.length === 0) return null;

    // Filter/Simplify data for chart
    // Assuming labels are dates, data is price
    const labels = props.priceHistory.map(p => new Date(p.created_at).toLocaleDateString());
    const data = props.priceHistory.map(p => parseFloat(p.price));

    return {
        labels,
        datasets: [
            {
                label: 'Precio Medio (€)',
                backgroundColor: '#f8b400', // grand-gold
                borderColor: '#f8b400',
                data,
                tension: 0.1,
                pointRadius: 4
            }
        ]
    }
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            mode: 'index',
            intersect: false,
        }
    },
    scales: {
        y: {
            grid: { color: '#333' },
            ticks: { color: '#aaa' }
        },
        x: {
            grid: { display: false },
            ticks: { color: '#aaa' }
        }
    }
};

// Bid Logic
const showBidModal = ref(false);
const selectedListing = ref(null);
const bidForm = useForm({
    amount: '',
});

const openBidModal = (listing) => {
    selectedListing.value = listing;
    // Pre-fill with min bid? 
    bidForm.amount = parseFloat(listing.price) + 0.5;
    showBidModal.value = true;
};

const closeBidModal = () => {
    showBidModal.value = false;
    bidForm.reset();
    selectedListing.value = null;
};

const submitBid = () => {
    if (!selectedListing.value) return;
    
    bidForm.post(route('auctions.bid', selectedListing.value.id), {
        onSuccess: () => {
             closeBidModal();
             // Optimistic update or page reload happens automatically with Inertia
        }
    });
};

import { onMounted } from 'vue';
onMounted(() => {
    console.log('Translation Computed:', translation.value);
});

// Collection Logic
const collectionForm = useForm({
    card_id: props.card.card_id,
    quantity: props.collectionItem ? props.collectionItem.quantity : 0,
    is_foil: props.collectionItem ? Boolean(props.collectionItem.is_foil) : false,
});

const updateCollection = (delta) => {
    const newVal = parseInt(collectionForm.quantity) + delta;
    if (newVal >= 0) {
        collectionForm.quantity = newVal;
    }
};

const saveCollection = () => {
    collectionForm.post(route('profile.collection.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Toast handled by layout/flash or button state
        }
    });
};

</script>

<template>
    <Head>
        <title>{{ translation.name || card.card_id }} - Grand Line Cards</title>
        <meta name="description" :content="`Buy ${translation.name} (${card.card_id}) from One Piece Card Game. Effect: ${translation.effect_text}`" />
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="product" />
        <meta property="og:title" :content="`${translation.name} - Grand Line Cards`" />
        <meta property="og:description" :content="translation.effect_text" />
        <meta property="og:image" :content="card.image_url" />
        
        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="`${translation.name} - Grand Line Cards`" />
        <meta name="twitter:description" :content="translation.effect_text" />
        <meta name="twitter:image" :content="card.image_url" />

        <script type="application/ld+json" v-html="JSON.stringify(jsonLd)"></script>
    </Head>

    <div class="min-h-screen bg-grand-900 text-grand-bone font-sans overflow-x-hidden p-6 flex flex-col items-center">
        <!-- Breadcrumbs / Back -->
        <div class="w-full max-w-6xl mb-8">
            <Link href="/cartas" class="text-grand-500 hover:text-grand-gold transition-colors flex items-center gap-2">
                &larr; Volver al Catálogo
            </Link>
        </div>

        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            <!-- Card Visual (Holo Effect) -->
            <div class="flex justify-center perspective">
                <div 
                    ref="cardElement"
                    @mousemove="handleMouseMove" 
                    @mouseleave="resetCard"
                    class="relative w-[350px] h-[490px] rounded-xl shadow-2xl transition-transform duration-100 ease-linear cursor-grab active:cursor-grabbing group"
                    :style="style"
                >
                    <!-- Base Image -->
                    <img :src="card.image_url || '/placeholder-card.png'" :alt="`Carta One Piece TCG ${translation.name} ${card.card_id} en español`" class="w-full h-full object-cover rounded-xl" />
                    
                    <!-- Foil Overlay -->
                    <div 
                        class="absolute inset-0 rounded-xl pointer-events-none mix-blend-color-dodge opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        style="background: linear-gradient(115deg, transparent 20%, rgba(255, 215, 0, 0.4) 40%, rgba(255, 0, 128, 0.4) 60%, transparent 80%); background-position: var(--tx) var(--ty); background-size: 300% 300%;"
                    ></div>
                    
                    <!-- Shine Glint -->
                    <div 
                        class="absolute inset-0 rounded-xl pointer-events-none mix-blend-overlay opacity-0 group-hover:opacity-60 transition-opacity duration-300"
                         style="background: radial-gradient(circle at var(--mx) var(--my), rgba(255,255,255,0.8) 0%, transparent 40%);"
                    ></div>
                </div>
            </div>

            <!-- Details Panel -->
            <div class="space-y-8">
                <div>
                    <div class="flex items-center gap-4 mb-2">
                        <span class="bg-grand-gold text-grand-900 font-bold px-3 py-1 rounded text-sm uppercase tracking-wider">
                            {{ card.rarity }}
                        </span>
                        <span class="text-grand-500 font-mono">{{ card.card_id }}</span>
                    </div>
                    <h1 class="text-5xl font-display font-bold text-transparent bg-clip-text bg-gradient-to-r from-grand-bone to-grand-gold">
                        {{ translation.name || 'Nombre Desconocido' }}
                    </h1>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-4 gap-4 bg-grand-800 p-6 rounded-lg border border-grand-700">
                    <div class="text-center">
                        <div class="text-xs text-grand-500 uppercase">Coste</div>
                        <div class="text-2xl font-bold font-display bg-black w-10 h-10 rounded-full flex items-center justify-center mx-auto mt-2 border-2 border-grand-gold">
                            {{ card.cost }}
                        </div>
                    </div>
                     <div class="text-center">
                        <div class="text-xs text-grand-500 uppercase">Poder</div>
                        <div class="text-xl font-bold font-display mt-3">{{ card.power }}</div>
                    </div>
                     <div class="text-center">
                        <div class="text-xs text-grand-500 uppercase">Contra</div>
                        <div class="text-xl font-bold font-display mt-3">{{ card.counter || '-' }}</div>
                    </div>
                     <div class="text-center">
                        <div class="text-xs text-grand-500 uppercase">Color</div>
                        <div class="text-xl font-bold font-display mt-3">{{ card.color }}</div>
                    </div>
                </div>

                <!-- Effect Box -->
                <div class="bg-grand-800 p-8 rounded-lg border border-grand-700 relative overflow-hidden">
                    <div class="absolute top-4 right-4">
                        <button @click="toggleLanguage" class="text-xs font-bold uppercase tracking-wider text-grand-400 hover:text-grand-gold transition-colors border border-grand-600 px-3 py-1 rounded-full flex items-center gap-2">
                            <span :class="{ 'text-grand-gold': isSpanish }">ES</span>
                            <span class="text-grand-700">|</span>
                            <span :class="{ 'text-grand-gold': !isSpanish }">EN</span>
                        </button>
                    </div>

                    <h3 class="text-grand-500 text-sm uppercase font-bold tracking-wider mb-4">Efecto</h3>
                    <div class="font-serif text-lg leading-relaxed text-grand-100 whitespace-pre-line">
                        {{ translation.effect_text || 'Sin efecto.' }}
                    </div>

                    <div v-if="translation.trigger_text" class="mt-6 pt-6 border-t border-grand-700">
                        <h3 class="text-yellow-600 text-xs uppercase font-bold tracking-wider mb-2">Trigger</h3>
                        <div class="font-serif text-grand-200 italic bg-black/20 p-4 rounded border-l-2 border-yellow-600">
                            {{ translation.trigger_text }}
                        </div>
                    </div>

                <!-- Variants -->
                <div v-if="variants && variants.length > 0" class="bg-grand-900 border border-grand-700 rounded-lg p-6">
                    <h3 class="text-grand-gold text-sm uppercase font-bold tracking-wider mb-4">Otras Versiones ({{ variants.length }})</h3>
                    <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3">
                         <Link 
                            v-for="variant in variants" 
                            :key="variant.id" 
                            :href="`/cartas/${variant.id}`"
                            class="block group relative aspect-[2/3] rounded-lg overflow-hidden border border-grand-700 hover:border-grand-gold transition-colors"
                        >
                            <img 
                                :src="variant.image_url || '/placeholder-card.png'" 
                                :alt="variant.id" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                            />
                            <div class="absolute inset-x-0 bottom-0 bg-black/60 backdrop-blur-sm p-1 text-[10px] text-center font-mono text-grand-200">
                                {{ variant.rarity }}
                            </div>
                        </Link>
                    </div>
                </div>
                </div>

                <!-- Rulings (Stub) -->
                <div>
                     <h3 class="text-grand-500 text-sm uppercase font-bold tracking-wider mb-4">Reglas y FAQ</h3>
                     <div class="text-sm text-grand-400">
                        No se han encontrado reglas para esta carta.
                     </div>
                </div>
                
                <!-- Price Chart -->
                <div v-if="chartData" class="bg-grand-900 border border-grand-700 rounded-lg p-6">
                    <h3 class="text-grand-gold text-lg uppercase font-bold tracking-wider mb-4">📈 Historial de Precios</h3>
                    <div class="h-64 relative">
                         <!-- Chart.js Canvas -->
                         <Line :data="chartData" :options="chartOptions" />
                    </div>
                </div>

                <!-- Collection Widget -->
                <div v-if="$page.props.auth.user" class="bg-grand-900 border border-grand-700 rounded-lg p-6 mb-8">
                    <h3 class="text-grand-gold text-sm uppercase font-bold tracking-wider mb-4 flex items-center justify-between">
                        <span>Mi Colección</span>
                        <Link href="/profile/coleccion" class="text-xs text-grand-500 hover:text-white underline">Ver Todo</Link>
                    </h3>
                    
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" v-model="collectionForm.is_foil" class="rounded border-grand-600 bg-grand-800 text-grand-gold focus:ring-grand-gold">
                            <span class="text-sm text-grand-300">Foil</span>
                        </label>
                        
                        <div class="flex items-center bg-grand-800 rounded border border-grand-600">
                            <button @click="updateCollection(-1)" class="px-3 py-1 hover:bg-grand-700 text-grand-400 font-bold">-</button>
                            <input type="number" v-model="collectionForm.quantity" class="w-12 text-center bg-transparent border-0 text-white p-0 text-sm focus:ring-0 appearance-none" min="0">
                            <button @click="updateCollection(1)" class="px-3 py-1 hover:bg-grand-700 text-grand-400 font-bold">+</button>
                        </div>

                        <button @click="saveCollection" :disabled="collectionForm.processing" class="text-xs bg-grand-700 hover:bg-grand-600 text-white px-3 py-1.5 rounded transition-colors ml-auto">
                            {{ collectionForm.recentlySuccessful ? 'Guardado!' : 'Guardar' }}
                        </button>
                    </div>
                </div>

                <!-- Bazaar / Market -->
                <div class="bg-grand-900 border border-grand-gold/30 rounded-lg p-6 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <svg class="w-32 h-32 text-grand-gold" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.15-1.46-3.27-3.4h1.96c.1 1.05 1.18 1.91 2.53 1.91 1.29 0 2.13-.72 2.13-1.71 0-2.69-4.5-2.91-4.5-6.67 0-1.84 1.4-3.26 3.15-3.63V2.5h2.67v1.9c1.8.35 3.02 1.54 3.02 3.45h-1.96c-.05-1.12-1.04-1.84-2.26-1.84-1.19 0-2.02.7-2.02 1.7 0 2.62 4.5 2.87 4.5 6.64.03 2.05-1.5 3.49-3.28 3.74z"/></svg>
                    </div>

                    <h3 class="text-grand-gold text-lg uppercase font-bold tracking-wider mb-4 flex items-center gap-2">
                        <span>💰</span> Bazar ({{ listings ? listings.length : 0 }} ofertas)
                    </h3>

                    <div v-if="listings && listings.length > 0" class="space-y-3">
                        <div v-for="listing in listings" :key="listing.id" class="flex flex-col sm:flex-row sm:items-center justify-between bg-black/40 p-3 rounded border border-grand-700 hover:border-grand-gold transition-colors group gap-3">
                             <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                <span v-if="listing.is_auction" class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-600 text-white shadow-sm shimmer">SUBASTA</span>
                                <span class="bg-grand-700 text-grand-200 px-2 py-0.5 rounded text-xs font-mono whitespace-nowrap">{{ listing.condition }}</span>
                                <span class="text-grand-300 text-xs text-grand-400">
                                    {{ listing.user ? listing.user.name : 'Unknown' }}
                                </span>
                             </div>
                             
                             <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
                                <span class="text-xl font-bold text-grand-bone">{{ listing.price }} <span class="text-xs">€</span></span>
                                <button 
                                    v-if="listing.is_auction"
                                    @click="openBidModal(listing)"
                                    class="bg-purple-600 hover:bg-purple-500 text-white font-bold px-4 py-1.5 rounded text-sm transition-colors border border-purple-400 shadow-[0_0_10px_rgba(147,51,234,0.3)]"
                                >
                                    Pujar
                                </button>
                                <button 
                                    v-else
                                    @click="addToCart(listing.id)"
                                    class="bg-grand-gold text-grand-900 font-bold px-4 py-1.5 rounded text-sm hover:bg-white transition-colors"
                                >
                                    Comprar
                                </button>
                             </div>
                        </div>
                    </div>
                     <div v-else class="text-grand-400 text-sm italic">
                        No hay ofertas disponibles actualmente.
                    </div>
                </div>

            </div>
        </div>

        <!-- Bid Modal -->
        <Modal :show="showBidModal" @close="closeBidModal">
            <div class="p-6 bg-grand-800 border border-grand-gold/20 rounded-lg">
                <h2 class="text-lg font-bold text-grand-gold mb-4">Realizar Puja</h2>
                <p class="text-grand-300 text-sm mb-4">Introduzca su puja máxima. Debe ser superior a {{ selectedListing?.price }} €</p>
                
                <form @submit.prevent="submitBid">
                    <div class="mb-4">
                        <label class="block text-grand-400 text-xs uppercase mb-1">Monto (€)</label>
                        <input 
                            v-model="bidForm.amount" 
                            type="number" 
                            step="0.5" 
                            class="w-full bg-grand-900 border border-grand-600 rounded p-2 text-white focus:border-grand-gold focus:ring-1 focus:ring-grand-gold"
                            autofocus
                        />
                        <p v-if="bidForm.errors.amount" class="text-red-400 text-xs mt-1">{{ bidForm.errors.amount }}</p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="closeBidModal" class="px-4 py-2 bg-grand-700 text-white rounded hover:bg-grand-600">Cancelar</button>
                        <button 
                            type="submit" 
                            :disabled="bidForm.processing"
                            class="px-4 py-2 bg-grand-gold text-grand-900 font-bold rounded hover:bg-white disabled:opacity-50"
                        >
                            {{ bidForm.processing ? 'Enviando...' : 'Confirmar Puja' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

    </div>
</template>

<style scoped>
.perspective {
    perspective: 1000px;
}
.shimmer {
    background: linear-gradient(110deg, #9333ea 8%, #d8b4fe 18%, #9333ea 33%);
    background-size: 200% 100%;
    animation: shimmer 1.5s linear infinite;
}
@keyframes shimmer {
    to { background-position-x: -200%; }
}
</style>
