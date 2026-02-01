<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    tag: Object,
    posts: Array,
    events: Array,
});
</script>

<template>
    <AppLayout>
        <Head :title="`Etiqueta: ${tag.name?.es || tag.name}`" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-12">
                <span class="text-slate-500 uppercase tracking-widest text-xs font-bold">Explorando etiqueta</span>
                <h1 class="text-4xl font-display font-bold text-white mt-2">
                    #{{ tag.name?.es || tag.name }}
                </h1>
            </div>

            <!-- Posts Section -->
            <div v-if="posts.length > 0">
                <h2 class="text-2xl text-grand-bone font-bold mb-6 border-b border-grand-gold/20 pb-2 inline-block">Noticias</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                     <Link 
                        v-for="post in posts" 
                        :key="post.id" 
                        :href="route('content.show', post.slug)"
                        class="group block bg-slate-900/50 rounded-2xl overflow-hidden hover:ring-1 hover:ring-grand-gold/50 transition duration-300"
                    >
                        <div class="p-6">
                            <div class="flex items-center text-xs text-slate-500 mb-3 space-x-2">
                                <span>{{ new Date(post.published_at).toLocaleDateString('es-ES') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3 group-hover:text-grand-gold transition leading-tight">
                                {{ post.translation?.title }}
                            </h3>
                             <p class="text-slate-400 text-sm leading-relaxed line-clamp-3">
                                {{ post.translation?.excerpt }}
                            </p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Events Section (Placeholder for now as logic was empty) -->
            <div v-if="events.length > 0">
                 <h2 class="text-2xl text-grand-bone font-bold mb-6 border-b border-grand-gold/20 pb-2 inline-block">Eventos</h2>
                 <!-- Event listing logic -->
            </div>

            <div v-if="posts.length === 0 && events.length === 0" class="py-12 text-center text-slate-500 bg-slate-900/50 rounded-lg">
                No se encontraron contenidos con esta etiqueta.
            </div>
        </div>
    </AppLayout>
</template>
