<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    post: Object,
    categories: Array,
    tags: Array,
});

const isEditing = computed(() => !!props.post);

const form = useForm({
    title: props.post?.translations?.[0]?.title || '',
    category_id: props.post?.category_id || '',
    slug: props.post?.slug || '',
    status: props.post?.status || 'draft',
    excerpt: props.post?.translations?.[0]?.excerpt || '',
    content: props.post?.translations?.[0]?.content || '',
    tags: props.post?.tags?.map(t => t.id) || [],
});

const submit = () => {
    if (isEditing.value) {
        form.put(route('admin.posts.update', props.post.id));
    } else {
        form.post(route('admin.posts.store'));
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="isEditing ? 'Editar Noticia' : 'Nueva Noticia'" />
        
        <div class="mb-6">
            <Link :href="route('admin.posts.index')" class="text-grand-500 hover:text-grand-gold text-sm mb-2 inline-block">&larr; Volver al listado</Link>
            <h2 class="text-2xl font-bold text-grand-bone">{{ isEditing ? 'Editar Noticia' : 'Nueva Publicación' }}</h2>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-grand-900 border border-grand-700 p-6 rounded-lg">
                    <label class="block text-grand-300 font-bold mb-2">Título</label>
                    <input v-model="form.title" type="text" class="w-full bg-grand-800 border border-grand-600 rounded p-2 text-grand-100 focus:border-grand-gold focus:ring-1 focus:ring-grand-gold" required />
                    <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                    
                    <div class="mt-4">
                        <label class="block text-grand-500 text-sm mb-1">Slug (URL)</label>
                        <input v-model="form.slug" type="text" placeholder="Auto-generado si se deja vacío" class="w-full bg-grand-950 border border-grand-800 rounded p-2 text-grand-300 text-sm" />
                    </div>
                </div>

                <!-- Content -->
                <div class="bg-grand-900 border border-grand-700 p-6 rounded-lg">
                    <label class="block text-grand-300 font-bold mb-2">Contenido</label>
                    <textarea v-model="form.content" rows="15" class="w-full bg-grand-800 border border-grand-600 rounded p-2 text-grand-100 font-mono text-sm leading-relaxed" placeholder="# Escribe tu artículo en Markdown..."></textarea>
                    <p class="text-xs text-grand-500 mt-2 text-right">Soporta Markdown básico</p>
                </div>

                <!-- Excerpt -->
                 <div class="bg-grand-900 border border-grand-700 p-6 rounded-lg">
                    <label class="block text-grand-300 font-bold mb-2">Extracto / Resumen</label>
                    <textarea v-model="form.excerpt" rows="3" class="w-full bg-grand-800 border border-grand-600 rounded p-2 text-grand-100" placeholder="Breve descripción para listados y SEO..."></textarea>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publish Settings -->
                <div class="bg-grand-900 border border-grand-700 p-6 rounded-lg">
                    <h3 class="font-bold text-grand-gold mb-4 uppercase text-xs tracking-wider">Publicación</h3>
                    
                    <div class="mb-4">
                        <label class="block text-grand-300 text-sm mb-2">Estado</label>
                        <select v-model="form.status" class="w-full bg-grand-800 border border-grand-600 rounded p-2 text-grand-100">
                            <option value="draft">Borrador</option>
                            <option value="published">Publicado</option>
                        </select>
                    </div>

                    <button type="submit" :disabled="form.processing" class="w-full py-2 bg-grand-gold text-grand-900 font-bold rounded shadow hover:bg-grand-accent transition-colors disabled:opacity-50">
                        {{ isEditing ? 'Actualizar' : 'Publicar' }}
                    </button>
                </div>

                <!-- Taxonomy -->
                <div class="bg-grand-900 border border-grand-700 p-6 rounded-lg">
                    <h3 class="font-bold text-grand-gold mb-4 uppercase text-xs tracking-wider">Organización</h3>
                    
                    <div class="mb-4">
                        <label class="block text-grand-300 text-sm mb-2">Categoría</label>
                        <select v-model="form.category_id" class="w-full bg-grand-800 border border-grand-600 rounded p-2 text-grand-100">
                            <option value="">Sin Categoría</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name.es || cat.name }} <!-- Assuming ES or raw json handling -->
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-grand-300 text-sm mb-2">Etiquetas</label>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                            <label v-for="tag in tags" :key="tag.id" class="flex items-center gap-2 text-grand-300 text-sm hover:text-grand-100 cursor-pointer">
                                <input type="checkbox" :value="tag.id" v-model="form.tags" class="rounded border-grand-600 bg-grand-800 text-grand-gold focus:ring-grand-gold" />
                                <span>{{ tag.name.es || tag.name }}</span>
                            </label>
                        </div>
                        <p v-if="tags.length === 0" class="text-xs text-grand-500 italic">No hay etiquetas creadas.</p>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
