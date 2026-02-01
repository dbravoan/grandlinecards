<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    suggestions: Array
});

const updateStatus = (suggestion, status) => {
    if (confirm(`¿Estás seguro de que quieres marcar esta sugerencia como ${status}?`)) {
        router.put(route('admin.event_suggestions.update', suggestion.id), { status }, {
            onSuccess: () => alert(`Sugerencia ${status === 'approved' ? 'aprobada' : 'rechazada'} correctamente.`)
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Sugerencias de Eventos" />

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-grand-bone">Sugerencias de Eventos</h2>
            <span class="bg-grand-gold/10 text-grand-gold px-3 py-1 rounded text-sm font-bold border border-grand-gold/50">
                {{ suggestions.length }} Pendientes
            </span>
        </div>

        <div v-if="suggestions.length === 0" class="bg-grand-800 border border-grand-700 rounded-lg p-12 text-center text-grand-500">
            <p>No hay sugerencias pendientes de revisión.</p>
        </div>

        <div v-else class="grid gap-6">
            <div v-for="suggestion in suggestions" :key="suggestion.id" class="bg-grand-800 border border-grand-700 rounded-lg p-6 shadow-lg flex flex-col md:flex-row gap-6">
                
                <!-- Info -->
                <div class="flex-1 space-y-2">
                    <div class="flex items-center justify-between mb-2">
                         <span class="bg-blue-900/50 text-blue-300 text-xs font-bold px-2 py-1 rounded border border-blue-500/30 uppercase">
                            {{ suggestion.status }}
                        </span>
                        <span class="text-xs text-grand-500 font-mono">
                            Sugerido por: {{ suggestion.user?.name || 'Usuario desconocido' }}
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-grand-gold">{{ suggestion.title }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-grand-300">
                        <div class="flex items-center gap-2">
                            <span class="text-grand-500 uppercase text-xs font-bold">Fecha:</span>
                            <span>{{ new Date(suggestion.event_date).toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) }}</span>
                        </div>
                         <div class="flex items-center gap-2">
                            <span class="text-grand-500 uppercase text-xs font-bold">Lugar:</span>
                            <span>{{ suggestion.location }}</span>
                        </div>
                    </div>

                    <div class="bg-grand-900/50 p-4 rounded border border-grand-700/50 text-sm text-gray-400 italic">
                        "{{ suggestion.description }}"
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex md:flex-col gap-2 justify-center border-t md:border-t-0 md:border-l border-grand-700 pt-4 md:pt-0 md:pl-6">
                    <button @click="updateStatus(suggestion, 'approved')" class="flex-1 bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-4 rounded transition-colors text-sm uppercase tracking-wide flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                          <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                        </svg>
                        Aprobar
                    </button>
                    <button @click="updateStatus(suggestion, 'rejected')" class="flex-1 bg-red-900/50 hover:bg-red-900 border border-red-700 text-red-300 hover:text-white font-bold py-2 px-4 rounded transition-colors text-sm uppercase tracking-wide flex items-center justify-center gap-2">
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                          <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                        </svg>
                        Rechazar
                    </button>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>
