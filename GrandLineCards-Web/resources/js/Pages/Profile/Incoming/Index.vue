<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    itemsInVault: Array,
    incomingShipments: Array,
});

const form = useForm({}); // Empty form for post request

const requestShipment = () => {
    form.post(route('incoming.store'), {
        preserveScroll: true,
    });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(price);
}
</script>

<template>
    <div class="min-h-screen bg-grand-900 text-white font-sans">
        <Head title="Mis Compras (Aduana) - Grand Line Cards" />

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-display font-bold text-grand-gold mb-8">Mis Compras & Aduana (The Vault)</h1>

            <!-- Section 1: In Vault -->
            <div class="bg-grand-800 shadow sm:rounded-lg border border-grand-700 p-6 mb-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                         <h2 class="text-xl font-bold text-grand-bone mb-2">🔒 Items en el Vault</h2>
                         <p class="text-grand-400 text-sm">
                            Estos artículos han sido recibidos en nuestra sede y están listos para ser enviados a tu domicilio.
                            <br>
                            Solicita un envío consolidado para ahorrar costes.
                         </p>
                    </div>
                    <div v-if="itemsInVault.length > 0">
                        <PrimaryButton @click="requestShipment" :disabled="form.processing">
                            Solicitar Envío a Casa
                        </PrimaryButton>
                    </div>
                </div>

                <div v-if="itemsInVault.length > 0">
                    <ul class="divide-y divide-grand-700">
                        <li v-for="item in itemsInVault" :key="item.id" class="py-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <img :src="item.card.image_url" class="h-16 w-12 object-cover rounded border border-grand-600">
                                <div>
                                    <p class="font-bold text-grand-bone">{{ item.card.name }} <span class="text-xs text-grand-500">({{ item.card.card_id }})</span></p>
                                    <p class="text-xs text-grand-400">Vendedor: {{ item.market_listing.user.name }}</p>
                                    <p class="text-xs text-grand-400">Comprado el: {{ new Date(item.created_at).toLocaleDateString() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-grand-gold">{{ formatPrice(item.price_per_unit) }}</p>
                                <span class="bg-blue-900 text-blue-200 text-xs px-2 py-1 rounded font-bold uppercase">En Vault</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-center py-12 border border-dashed border-grand-600 rounded bg-grand-900/50">
                    <p class="text-grand-400 italic">No tienes artículos almacenados en el Vault actualmente.</p>
                </div>
            </div>

            <!-- Section 2: Incoming Shipments -->
            <div class="bg-grand-800 shadow sm:rounded-lg border border-grand-700 p-6">
                 <h2 class="text-xl font-bold text-grand-bone mb-4">🚚 Envíos en Camino</h2>
                 <ul class="divide-y divide-grand-700">
                    <li v-for="shipment in incomingShipments" :key="shipment.id" class="py-4">
                         <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-lg text-grand-gold">Envío #{{ shipment.id }}</h3>
                                <!-- Tracking Number Simulation if available -->
                                <p v-if="shipment.tracking_number" class="text-grand-300 font-mono text-sm mt-1">
                                    Tracking: {{ shipment.tracking_number }}
                                </p>
                                <p class="text-sm text-grand-400 mt-2">
                                    Solicitado: {{ new Date(shipment.created_at).toLocaleDateString() }}
                                    •
                                    Artículos: {{ shipment.items.length }}
                                </p>
                            </div>
                            <div>
                                <span :class="{
                                    'bg-yellow-900 text-yellow-200': shipment.status === 'processing',
                                    'bg-green-900 text-green-200': shipment.status === 'shipped',
                                    'bg-blue-900 text-blue-200': shipment.status === 'delivered',
                                }" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider block mb-2 text-center">
                                    {{ shipment.status }}
                                </span>
                                <a :href="route('incoming.label', shipment.id)" class="text-xs text-grand-gold hover:text-white underline block text-center">
                                    Ver Albarán
                                </a>
                            </div>
                        </div>
                    </li>
                 </ul>
                 <div v-if="incomingShipments.length === 0" class="text-center py-6 text-grand-500">
                    No has solicitado ningún envío todavía.
                </div>
            </div>

        </div>
    </div>
</template>
