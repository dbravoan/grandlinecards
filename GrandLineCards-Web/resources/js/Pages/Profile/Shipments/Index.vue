<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    itemsToShip: Array, // OrderItems status=awaiting_seller_shipment
    shipments: Array, // Historical VaultShipments
});

const form = useForm({
    item_ids: [],
    carrier: '',
    tracking_number: '',
});

// Selection Logic
const toggleSelection = (itemId) => {
    if (form.item_ids.includes(itemId)) {
        form.item_ids = form.item_ids.filter(id => id !== itemId);
    } else {
        form.item_ids.push(itemId);
    }
};

const createShipment = () => {
    form.post(route('shipments.store'), {
        onSuccess: () => form.reset(),
    });
};

const allSelected = computed(() => {
    return props.itemsToShip.length > 0 && form.item_ids.length === props.itemsToShip.length;
});

const toggleAll = () => {
    if (allSelected.value) {
        form.item_ids = [];
    } else {
        form.item_ids = props.itemsToShip.map(i => i.id);
    }
};
</script>

<template>
    <div class="min-h-screen bg-grand-900 text-white font-sans">
        <Head title="Mis Envíos - Grand Line Cards" />

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-display font-bold text-grand-gold mb-8">Gestión de Envíos al Vault</h1>

            <!-- Section 1: To Ship -->
            <div class="bg-grand-800 shadow sm:rounded-lg border border-grand-700 p-6 mb-8">
                <h2 class="text-xl font-bold text-grand-bone mb-4">📦 Pendiente de Envío</h2>
                <p class="text-grand-400 text-sm mb-6">
                    Selecciona los artículos vendidos que vas a enviar juntos en un mismo paquete hacia nuestro almacén (The Vault).
                    <br>
                    <strong>Recuerda:</strong> Solo pagas un envío si agrupas tus ventas.
                </p>

                <div v-if="itemsToShip.length > 0">
                    <div class="flex items-center gap-2 mb-4">
                        <input type="checkbox" :checked="allSelected" @change="toggleAll" class="rounded bg-grand-900 border-grand-700 text-grand-gold focus:ring-0">
                        <span class="text-xs text-grand-400 uppercase tracking-widest font-bold">Seleccionar Todo</span>
                    </div>

                    <ul class="divide-y divide-grand-700 mb-6">
                        <li v-for="item in itemsToShip" :key="item.id" class="py-4 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <input 
                                    type="checkbox" 
                                    :checked="form.item_ids.includes(item.id)" 
                                    @change="toggleSelection(item.id)"
                                    class="rounded bg-grand-900 border-grand-700 text-grand-gold focus:ring-0"
                                >
                                <img :src="item.card.image_url" class="h-12 w-9 object-cover rounded border border-grand-600">
                                <div>
                                    <p class="font-bold text-grand-bone">{{ item.card.card_id }}</p>
                                    <p class="text-xs text-grand-400">Vendido el: {{ new Date(item.created_at).toLocaleDateString() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="bg-yellow-900 text-yellow-200 text-xs px-2 py-1 rounded font-bold uppercase">Pendiente</span>
                            </div>
                        </li>
                    </ul>

                    <!-- Create Shipment Form -->
                    <div v-if="form.item_ids.length > 0" class="bg-grand-900 p-4 rounded border border-grand-700">
                        <h3 class="font-bold text-grand-gold mb-4">Detalles del Paquete</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel value="Transportista (Correos, Seur...)" />
                                <TextInput v-model="form.carrier" class="w-full mt-1 bg-grand-800 text-white border-grand-600" placeholder="Ej: Correos" />
                            </div>
                            <div>
                                <InputLabel value="Número de Seguimiento" />
                                <TextInput v-model="form.tracking_number" class="w-full mt-1 bg-grand-800 text-white border-grand-600" placeholder="Ej: PK123456789ES" />
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <PrimaryButton @click="createShipment" :disabled="form.processing">
                                Registrar Envío ({{ form.item_ids.length }} artículos)
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-6 border border-dashed border-grand-600 rounded bg-grand-900/50">
                    <p class="text-grand-400 italic">No tienes artículos pendientes de envío.</p>
                </div>
            </div>

            <!-- Section 2: History -->
            <div class="bg-grand-800 shadow sm:rounded-lg border border-grand-700 p-6">
                <h2 class="text-xl font-bold text-grand-bone mb-4">📜 Historial de Envíos</h2>
                <ul class="divide-y divide-grand-700">
                    <li v-for="shipment in shipments" :key="shipment.id" class="py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-lg text-grand-gold">{{ shipment.carrier }} - {{ shipment.tracking_number }}</p>
                                <p class="text-sm text-grand-400">
                                    Enviado el: {{ new Date(shipment.created_at).toLocaleDateString() }}
                                    •
                                    items: {{ shipment.items.length }}
                                </p>
                            </div>
                            <div>
                                <span class="bg-grand-700 text-grand-200 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider block mb-2 text-center">
                                    {{ shipment.status }}
                                </span>
                                <a :href="route('shipments.label', shipment.id)" class="text-xs text-grand-gold hover:text-white underline block text-center">
                                    Imprimir Etiqueta
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
                <div v-if="shipments.length === 0" class="text-center py-6 text-grand-500">
                    Sin envíos registrados.
                </div>
            </div>

        </div>
    </div>
</template>
