<script setup>
defineProps({
    original: String,
    translated: String,
    title: {
        type: String,
        default: 'Effect'
    }
});
</script>

<template>
    <div class="bg-grand-800 rounded-lg border border-grand-700/50 overflow-hidden">
        <div class="flex border-b border-grand-700">
             <div class="w-1/2 p-2 px-4 bg-grand-900/50 font-display font-bold text-grand-500 text-xs uppercase tracking-wider">
                Original (EN)
             </div>
             <div class="w-1/2 p-2 px-4 bg-grand-900/30 font-display font-bold text-grand-gold text-xs uppercase tracking-wider border-l border-grand-700">
                Español (ES)
             </div>
        </div>
        <div class="flex">
             <div class="w-1/2 p-4 text-sm text-grand-300 font-mono leading-relaxed">
                {{ original || 'No text available.' }}
             </div>
             <div class="w-1/2 p-4 text-sm text-grand-bone font-sans leading-relaxed border-l border-grand-700 bg-grand-gold/5">
                <span v-if="translated">{{ translated }}</span>
                <span v-else class="text-grand-500 italic">Pendiente de traducción...</span>
             </div>
        </div>
    </div>
</template>
