<script setup>
import { ref } from 'vue';

const props = defineProps({
    keyword: String,
    definition: String
});

const isVisible = ref(false);
</script>

<template>
    <span class="relative inline-block border-b border-dashed border-grand-gold/50 cursor-help group"
          @mouseenter="isVisible = true"
          @mouseleave="isVisible = false">
        <span class="text-grand-gold font-bold">{{ keyword }}</span>
        
        <!-- Tooltip -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-2"
        >
            <div v-if="isVisible" 
                 class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 w-64 bg-grand-900 border border-grand-gold/30 rounded shadow-xl z-50 p-3 text-left">
                 <div class="text-xs font-bold text-grand-gold mb-1 uppercase tracking-wider">{{ keyword }}</div>
                 <div class="text-xs text-grand-bone font-sans leading-snug">
                    {{ definition }}
                 </div>
                 <!-- Arrow -->
                 <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-grand-gold/30"></div>
            </div>
        </transition>
    </span>
</template>
