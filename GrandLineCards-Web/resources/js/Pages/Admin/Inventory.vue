<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const inventory = [
    { id: 'op01-001', name: 'Luffy (Leader)', stock: 5, price: 45.50 },
    { id: 'op01-002', name: 'Zoro', stock: 12, price: 8.00 },
    { id: 'op01-003', name: 'Nami', stock: 0, price: 15.00 },
];
</script>

<template>
    <AdminLayout>
        <Head title="Admin - Inventario" />
        
        <h2 class="text-2xl font-bold text-grand-bone mb-6">Control de Inventario</h2>

        <div class="bg-grand-900 border border-grand-700 rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-grand-800">
                    <tr>
                        <th class="p-4 text-xs font-bold text-grand-500 uppercase">Carta</th>
                        <th class="p-4 text-xs font-bold text-grand-500 uppercase">Stock</th>
                        <th class="p-4 text-xs font-bold text-grand-500 uppercase">Precio (€)</th>
                        <th class="p-4 text-xs font-bold text-grand-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-grand-700">
                    <tr v-for="item in inventory" :key="item.id" class="hover:bg-grand-800/50">
                        <td class="p-4">
                            <div class="font-bold text-grand-bone">{{ item.name }}</div>
                            <div class="text-xs text-grand-500 font-mono">{{ item.id }}</div>
                        </td>
                        <td class="p-4">
                            <input type="number" v-model="item.stock" class="w-20 bg-grand-800 border border-grand-700 rounded p-1 text-center text-grand-bone" :class="{'border-red-500': item.stock === 0}">
                        </td>
                        <td class="p-4">
                            <input type="number" step="0.50" v-model="item.price" class="w-24 bg-grand-800 border border-grand-700 rounded p-1 text-right text-grand-bone">
                        </td>
                        <td class="p-4">
                            <button class="text-xs text-grand-gold hover:underline">Actualizar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
