<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: Array,
    tags: Array,
});

const activeTab = ref('categories'); // 'categories' | 'tags'
const editingItem = ref(null);

const form = useForm({
    name: '',
    slug: '',
});

const switchTab = (tab) => {
    activeTab.value = tab;
    cancelEdit();
};

const submit = () => {
    const isCategory = activeTab.value === 'categories';
    const routeName = isCategory ? 'admin.categories' : 'admin.tags';
    
    if (editingItem.value) {
        form.put(route(`${routeName}.update`, editingItem.value.id), {
            onSuccess: () => cancelEdit(),
        });
    } else {
        form.post(route(`${routeName}.store`), {
            onSuccess: () => cancelEdit(),
        });
    }
};

const editItem = (item) => {
    editingItem.value = item;
    form.name = item.name.es || item.name; // Handle JSON or string
    form.slug = item.slug;
};

const cancelEdit = () => {
    editingItem.value = null;
    form.reset();
    form.clearErrors();
};

const deleteItem = (item) => {
    if (!confirm('¿Estás seguro de eliminar este elemento?')) return;
    
    const isCategory = activeTab.value === 'categories';
    const routeName = isCategory ? 'admin.categories' : 'admin.tags';
    
    router.delete(route(`${routeName}.destroy`, item.id));
};
</script>

<template>
    <AdminLayout>
        <Head title="Gestión de Taxonomías" />

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-grand-bone">Organización de Contenido</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="lg:col-span-1">
                <div class="bg-grand-900 border border-grand-700 p-6 rounded-lg sticky top-6">
                    <h3 class="font-bold text-grand-gold mb-4 uppercase text-xs tracking-wider">
                        {{ editingItem ? 'Editar' : 'Crear' }} {{ activeTab === 'categories' ? 'Categoría' : 'Etiqueta' }}
                    </h3>
                    
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-grand-300 text-sm mb-1">Nombre</label>
                            <input v-model="form.name" type="text" class="w-full bg-grand-800 border border-grand-600 rounded p-2 text-grand-100 placeholder-grand-500 focus:border-grand-gold focus:ring-1 focus:ring-grand-gold" placeholder="Ej. Guías, Noticias..." required />
                            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-grand-300 text-sm mb-1">Slug (Opcional)</label>
                            <input v-model="form.slug" type="text" class="w-full bg-grand-950 border border-grand-800 rounded p-2 text-grand-300 text-sm placeholder-grand-700" placeholder="Auto-generado" />
                            <div v-if="form.errors.slug" class="text-red-500 text-xs mt-1">{{ form.errors.slug }}</div>
                        </div>

                        <div class="pt-2 flex gap-2">
                            <button type="submit" :disabled="form.processing" class="flex-1 py-2 bg-grand-gold text-grand-900 font-bold rounded shadow hover:bg-grand-accent transition-colors disabled:opacity-50 text-sm">
                                {{ editingItem ? 'Actualizar' : 'Guardar' }}
                            </button>
                            <button v-if="editingItem" type="button" @click="cancelEdit" class="px-4 py-2 bg-grand-800 text-grand-300 font-bold rounded border border-grand-600 hover:text-white transition-colors text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Card -->
            <div class="lg:col-span-2">
                <!-- Tabs -->
                <div class="flex border-b border-grand-700 mb-6">
                    <button 
                        @click="switchTab('categories')" 
                        class="px-6 py-3 font-bold text-sm transition-colors border-b-2"
                        :class="activeTab === 'categories' ? 'text-grand-gold border-grand-gold' : 'text-grand-500 border-transparent hover:text-grand-300'"
                    >
                        Categorías
                    </button>
                    <button 
                        @click="switchTab('tags')" 
                        class="px-6 py-3 font-bold text-sm transition-colors border-b-2"
                        :class="activeTab === 'tags' ? 'text-grand-gold border-grand-gold' : 'text-grand-500 border-transparent hover:text-grand-300'"
                    >
                        Etiquetas
                    </button>
                </div>

                <div class="bg-grand-900 border border-grand-700 rounded-lg overflow-hidden">
                    <div v-if="(activeTab === 'categories' ? categories : tags).length === 0" class="p-8 text-center text-grand-500 italic">
                        No hay elementos creados aún.
                    </div>

                    <table v-else class="w-full text-left text-sm text-grand-300">
                        <thead class="bg-grand-950 text-grand-500 uppercase font-bold text-xs">
                            <tr>
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Slug</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-grand-800">
                            <tr v-for="item in (activeTab === 'categories' ? categories : tags)" :key="item.id" class="hover:bg-grand-800/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-grand-100">
                                    {{ item.name.es || item.name }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-grand-500">
                                    /{{ item.slug }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="editItem(item)" class="text-blue-400 hover:text-blue-300 mr-3 font-bold">Editar</button>
                                    <button @click="deleteItem(item)" class="text-red-500 hover:text-red-400 font-bold">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
