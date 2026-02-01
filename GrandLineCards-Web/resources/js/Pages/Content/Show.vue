<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    post: Object,
});
</script>

<template>
    <AppLayout>
        <Head>
            <title>{{ post.translation?.title }}</title>
            <meta name="description" :content="post.translation?.excerpt || post.translation?.title" />
        </Head>

        <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="flex justify-center gap-2 mb-4" v-if="post.category">
                    <span class="bg-grand-gold/10 text-grand-gold border border-grand-gold/20 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                         {{ post.category.name?.es || post.category.name }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl font-display font-bold text-white mb-6 leading-tight">
                    {{ post.translation?.title }}
                </h1>
                <div class="flex items-center justify-center text-sm text-slate-400">
                    <span class="mr-2">Publicado el</span>
                    <time :datetime="post.published_at">{{ new Date(post.published_at).toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</time>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl shadow-black/50 mb-12 aspect-[21/9]">
                 <img 
                    :src="post.image_path || '/images/default-blog.jpg'" 
                    :alt="post.translation?.title" 
                    class="object-cover w-full h-full"
                >
            </div>

            <!-- Content -->
            <div class="prose prose-invert prose-lg max-w-none prose-headings:font-display prose-headings:text-grand-100 prose-a:text-grand-gold hover:prose-a:text-grand-300">
                <!-- Using v-html for simplicity, assume sanitized markdown backend or trusted admin content -->
                <!-- Ideally use a Markdown renderer component here -->
                <div class="whitespace-pre-wrap">{{ post.translation?.content }}</div> 
            </div>

            <!-- Tags -->
            <div class="mt-12 pt-8 border-t border-slate-800" v-if="post.tags && post.tags.length">
                <div class="flex flex-wrap gap-2">
                    <Link 
                        v-for="tag in post.tags" 
                        :key="tag.id"
                        :href="route('tags.show', tag.slug)"
                        class="text-sm px-3 py-1 rounded-full bg-slate-800 text-slate-300 hover:bg-grand-gold hover:text-grand-900 transition-colors"
                    >
                        #{{ tag.name?.es || tag.name }}
                    </Link>
                </div>
            </div>
            
            <div class="mt-12">
                 <Link :href="route('content.index')" class="text-grand-gold hover:text-white transition-colors">&larr; Volver a noticias</Link>
            </div>
        </article>
    </AppLayout>
</template>
