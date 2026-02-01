<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    incoming: Array, // VaultShipments (pending)
    readyToShip: Array, // Grouped by User
});

const receiveShipment = (id) => {
    if (confirm('Confirm receipt of this box? Verify tracking number matches.')) {
        router.post(route('admin.logistics.receive', id));
    }
};

const shipToUser = (userId) => {
    if (confirm('Create consolidated shipment for this user?')) {
        router.post(route('admin.logistics.ship', userId));
    }
};
</script>

<template>
    <Head title="Logística - Admin" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Logística (The Vault)
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Incoming Shipments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-bold text-lg mb-4 text-orange-600">📥 Entrantes (Paquetes de Vendedores)</h3>
                        <div v-if="incoming.length === 0" class="text-gray-500 italic">No hay paquetes pendientes de recibir.</div>
                        <table v-else class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Tracking</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Vendedor</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Items</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="shipment in incoming" :key="shipment.id">
                                    <td class="px-4 py-3 font-mono text-sm">{{ shipment.carrier }} - {{ shipment.tracking_number }}</td>
                                    <td class="px-4 py-3 text-sm">{{ shipment.user.name }}</td>
                                    <td class="px-4 py-3 text-sm">{{ shipment.items.length }} cartas</td>
                                    <td class="px-4 py-3">
                                        <PrimaryButton @click="receiveShipment(shipment.id)" class="bg-green-600 hover:bg-green-700">
                                            Recibir en Vault
                                        </PrimaryButton>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Outgoing Consolidation -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-bold text-lg mb-4 text-blue-600">📤 Salientes (Consolidación a Compradores)</h3>
                        <p class="mb-4 text-sm text-gray-600">Usuarios con items acumulados en el Vault listos para enviar.</p>

                        <div v-if="readyToShip.length === 0" class="text-gray-500 italic">El Vault está vacío o todo está procesado.</div>
                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="group in readyToShip" :key="group.user.id" class="border border-gray-200 rounded p-4 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-lg">{{ group.user.name }}</h4>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded">
                                        {{ group.total_items }} items
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mb-4 h-32 overflow-y-auto">
                                    <ul class="list-disc ml-4 space-y-1">
                                        <li v-for="item in group.items" :key="item.id">
                                            {{ item.card.card_id }} - {{ item.price_per_unit }}€
                                        </li>
                                    </ul>
                                </div>
                                <PrimaryButton @click="shipToUser(group.user.id)" class="w-full justify-center">
                                    📦 Crear Envío Consolidado
                                </PrimaryButton>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>
