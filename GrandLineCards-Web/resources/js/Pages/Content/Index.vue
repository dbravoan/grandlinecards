<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    posts: Object, // Paginator
});
</script>

<template>
    <AppLayout>
        <Head title="Noticias" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-4xl font-display font-bold text-white mb-2">Noticias y Eventos</h1>
            <p class="text-slate-400 mb-8">Mantente al día con el meta, torneos y lanzamientos.</p>

            <div v-if="posts.data.length === 0" class="text-center py-20 bg-slate-900/50 rounded-lg">
                <p class="text-slate-400">No hay noticias disponibles en este momento.</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <Link 
                    v-for="post in posts.data" 
                    :key="post.id" 
                    :href="route('content.show', post.slug)"
                    class="group block bg-slate-900/50 rounded-2xl overflow-hidden hover:ring-1 hover:ring-grand-gold/50 transition duration-300"
                >
                    <div class="relative overflow-hidden aspect-video">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition z-10"></div>
                        <img 
                            :src="post.image_path || '/images/default-blog.jpg'" 
                            :alt="post.translation?.title" 
                            class="object-cover w-full h-full transform group-hover:scale-105 transition duration-500"
                        >
                        <div class="absolute top-4 left-4 z-20" v-if="post.category">
                            <span class="bg-grand-gold text-grand-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                {{ post.category.name?.es || post.category.name }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-xs text-slate-500 mb-3 space-x-2">
                             <span>{{ new Date(post.published_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                        </div>
                        <h2 class="text-xl font-bold text-white mb-3 group-hover:text-grand-gold transition leading-tight">
                            {{ post.translation?.title }}
                        </h2>
                        <p class="text-slate-400 text-sm leading-relaxed line-clamp-3">
                            {{ post.translation?.excerpt }}
                        </p>
                    </div>
                </Link>
            </div>

             <!-- Pagination -->
            <div v-if="posts.links.length > 3" class="mt-12 flex justify-center gap-2">
                 <Link 
                    v-for="(link, key) in posts.links" 
                    :key="key" 
                    :href="link.url || '#'" 
                    :class="{'bg-grand-gold text-grand-900 font-bold': link.active, 'bg-slate-800 text-slate-300 hover:bg-slate-700': !link.active, 'opacity-50 pointer-events-none': !link.url}"
                    class="px-4 py-2 rounded-lg text-sm transition-colors"
                    v-html="link.label"
                />
            </div>
        </div>
    </AppLayout>
</template>
