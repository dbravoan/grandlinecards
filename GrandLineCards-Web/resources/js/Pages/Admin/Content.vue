<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

// Mock content list
const posts = [
    { id: 1, title: 'Reporte del Meta: Lanzamiento OP-05', status: 'Publicado', date: '2025-12-01' },
    { id: 2, title: 'Próximos cambios en la Banlist', status: 'Borrador', date: '2025-12-15' },
];
</script>

<template>
    <AdminLayout>
        <Head title="Admin - Contenido" />
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-grand-bone">Editor de Contenido</h2>
            <button class="px-4 py-2 bg-grand-gold text-grand-900 font-bold rounded shadow hover:bg-grand-accent transition-colors">
                + Nueva Publicación
            </button>
        </div>

        <div class="bg-grand-900 border border-grand-700 rounded-lg overflow-hidden">
             <!-- List -->
             <div v-for="post in posts" :key="post.id" class="p-4 border-b border-grand-700 flex items-center justify-between hover:bg-grand-800/50 transition-colors">
                <div>
                    <h3 class="font-bold text-grand-bone text-lg">{{ post.title }}</h3>
                    <div class="flex gap-2 text-xs text-grand-500 mt-1">
                        <span>{{ post.date }}</span>
                        <span>•</span>
                        <span :class="{'text-green-500': post.status === 'Publicado', 'text-yellow-500': post.status === 'Borrador'}">{{ post.status }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="px-3 py-1 bg-grand-700 text-grand-bone text-xs rounded hover:bg-grand-600">Editar</button>
                    <button class="px-3 py-1 bg-red-900/50 text-red-500 text-xs rounded hover:bg-red-900 hover:text-red-400">Eliminar</button>
                </div>
             </div>
        </div>
        
        <!-- Editor Area (Placeholder) -->
        <div class="mt-8 bg-grand-800 p-6 rounded-lg border border-grand-700 border-dashed min-h-[200px] flex items-center justify-center text-grand-500">
            [El componente del Editor de Bloques iría aquí]
        </div>
    </AdminLayout>
</template>
