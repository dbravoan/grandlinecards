<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    posts: Object, // Paginator
});

const deletePost = (id) => {
    if (confirm('¿Estás seguro de querer eliminar esta noticia?')) {
        router.delete(route('admin.posts.destroy', id));
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Admin - Noticias" />
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-grand-bone">Noticias y Blog</h2>
            <Link :href="route('admin.posts.create')" class="px-4 py-2 bg-grand-gold text-grand-900 font-bold rounded shadow hover:bg-grand-accent transition-colors">
                + Nueva Publicación
            </Link>
        </div>

        <div class="bg-grand-900 border border-grand-700 rounded-lg overflow-hidden">
             <!-- List -->
             <div v-if="posts.data.length === 0" class="p-8 text-center text-grand-500">
                No hay noticias publicadas aún.
             </div>

             <div v-for="post in posts.data" :key="post.id" class="p-4 border-b border-grand-700 flex items-center justify-between hover:bg-grand-800/50 transition-colors">
                <div class="flex items-start gap-4">
                    <div v-if="post.image_path" class="w-16 h-16 bg-gray-800 rounded object-cover overflow-hidden">
                        <!-- Image placeholder logic needed if storage is linked -->
                    </div>
                    <div>
                        <h3 class="font-bold text-grand-bone text-lg">
                            {{ post.translations?.[0]?.title || 'Sin título' }}
                        </h3>
                        <div class="flex gap-2 text-xs text-grand-500 mt-1">
                            <span v-if="post.category" class="px-2 py-0.5 rounded bg-grand-700 text-grand-100">
                                {{ post.category.name?.es || post.category.name }}
                            </span>
                            <span>{{ new Date(post.created_at).toLocaleDateString() }}</span>
                            <span>•</span>
                            <span :class="{'text-green-500': post.status === 'published', 'text-yellow-500': post.status === 'draft'}">
                                {{ post.status === 'published' ? 'Publicado' : 'Borrador' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('admin.posts.edit', post.id)" class="px-3 py-1 bg-grand-700 text-grand-bone text-xs rounded hover:bg-grand-600">
                        Editar
                    </Link>
                    <button @click="deletePost(post.id)" class="px-3 py-1 bg-red-900/50 text-red-500 text-xs rounded hover:bg-red-900 hover:text-red-400">
                        Eliminar
                    </button>
                </div>
             </div>
        </div>
        
        <!-- Pagination -->
        <div v-if="posts.links.length > 3" class="mt-4 flex justify-center gap-1">
             <Link 
                v-for="(link, key) in posts.links" 
                :key="key" 
                :href="link.url || '#'" 
                :class="{'bg-grand-gold text-grand-900': link.active, 'bg-grand-800 text-grand-bone': !link.active, 'opacity-50 pointer-events-none': !link.url}"
                class="px-3 py-1 rounded text-sm"
                v-html="link.label"
            />
        </div>
    </AdminLayout>
</template>
