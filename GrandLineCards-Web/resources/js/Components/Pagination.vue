<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in links">
                <div v-if="link.url === null" :key="key" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-grand-500 border rounded" v-html="link.label" />
                <Link v-else :key="`link-${key}`" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-grand-700 hover:text-grand-gold focus:border-grand-gold focus:text-grand-gold transition-colors" :class="{ 'bg-grand-gold text-grand-900 border-grand-gold': link.active, 'text-grand-bone border-grand-700': !link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>
