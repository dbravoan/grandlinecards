<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AdminLayout>
        <Head title="Admin Dashboard" />
        
        <h2 class="text-2xl font-bold text-grand-bone mb-6">Camarote del Capitán</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Stats Cards -->
            <div class="bg-grand-900 border border-grand-700 p-6 rounded-lg shadow-lg">
                <h3 class="text-grand-500 text-sm uppercase font-bold tracking-wider">Total Cartas</h3>
                <p class="text-4xl font-display text-grand-gold mt-2">1,240</p>
            </div>
            <div class="bg-grand-900 border border-grand-700 p-6 rounded-lg shadow-lg">
                <h3 class="text-grand-500 text-sm uppercase font-bold tracking-wider">Traducciones Pendientes</h3>
                <p class="text-4xl font-display text-grand-bone mt-2">45</p>
            </div>
            <div class="bg-grand-900 border border-grand-700 p-6 rounded-lg shadow-lg">
                <h3 class="text-grand-500 text-sm uppercase font-bold tracking-wider">Pedidos Hoy</h3>
                <p class="text-4xl font-display text-grand-accent mt-2">12</p>
            </div>
        </div>

        <div class="bg-grand-800 border border-grand-700 rounded-lg p-6">
            <h3 class="font-bold text-grand-bone mb-4">Actividad Reciente</h3>
            <ul class="space-y-3 text-sm text-grand-300">
                <li class="flex justify-between items-center border-b border-grand-700 pb-2">
                    <span>Nueva carta añadida: <strong>Monkey D. Dragon (SEC)</strong></span>
                    <span class="text-xs text-grand-500">hace 2 min</span>
                </li>
                <li class="flex justify-between items-center border-b border-grand-700 pb-2">
                    <span>Traducción aprobada para <strong>Nami</strong></span>
                    <span class="text-xs text-grand-500">hace 1 hora</span>
                </li>
                <li class="flex justify-between items-center">
                    <span>Stock actualizado para <strong>Booster Box OP-03</strong></span>
                    <span class="text-xs text-grand-500">hace 3 horas</span>
                </li>
            </ul>
        </div>
    </AdminLayout>
</template>
