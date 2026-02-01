<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';

const props = defineProps({
    filters: Object, // { color, type, set, ... }
    expansions: Array // [{code, name}]
});

const currentFilters = ref({
    color: props.filters.color || [],
    type: props.filters.type || [],
    cost: props.filters.cost || null,
    rarity: props.filters.rarity || [],
    expansion: props.filters.expansion || []
});

// Update URL with filters
const applyFilters = debounce(() => {
    // Check if we can use a friendly URL
    const hasSingleExpansion = currentFilters.value.expansion && (Array.isArray(currentFilters.value.expansion) ? currentFilters.value.expansion.length === 1 : currentFilters.value.expansion);
    const hasSingleColor = currentFilters.value.color && (Array.isArray(currentFilters.value.color) ? currentFilters.value.color.length === 1 : currentFilters.value.color);
    
    // Prioritize Set, then Color
    if (hasSingleExpansion && !hasSingleColor && !currentFilters.value.q && !currentFilters.value.cost && (!currentFilters.value.rarity || currentFilters.value.rarity.length === 0)) {
         const code = Array.isArray(currentFilters.value.expansion) ? currentFilters.value.expansion[0] : currentFilters.value.expansion;
         router.get(route('catalog.index.set', code), {}, { preserveState: true, replace: true });
         return;
    }

    if (hasSingleColor && !hasSingleExpansion && !currentFilters.value.q && !currentFilters.value.cost && (!currentFilters.value.rarity || currentFilters.value.rarity.length === 0)) {
         const colorVal = Array.isArray(currentFilters.value.color) ? currentFilters.value.color[0] : currentFilters.value.color;
         router.get(route('catalog.index.color', colorVal), {}, { preserveState: true, replace: true });
         return;
    }

    // Default to query params
    router.get(route('catalog.index'), currentFilters.value, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
}, 300);

watch(currentFilters, applyFilters, { deep: true });

const colors = [
    { name: 'Rojo', value: 'Red', class: 'bg-red-600' },
    { name: 'Azul', value: 'Blue', class: 'bg-blue-600' },
    { name: 'Verde', value: 'Green', class: 'bg-green-600' },
    { name: 'Morado', value: 'Purple', class: 'bg-purple-600' },
    { name: 'Negro', value: 'Black', class: 'bg-gray-900' },
    { name: 'Amarillo', value: 'Yellow', class: 'bg-yellow-400' },
];

const rarities = ['L', 'SR', 'R', 'UC', 'C', 'SEC'];
</script>

<template>
    <div class="space-y-6 p-4 bg-grand-800 rounded-lg border border-grand-700/50">
        <!-- Header -->
        <h3 class="font-display font-bold text-lg text-grand-gold border-b border-grand-700 pb-2">
            Filtros
        </h3>

        <!-- Colors -->
        <div>
            <h4 class="font-bold text-sm text-grand-bone mb-3">Color</h4>
            <div class="flex flex-wrap gap-2">
                <label v-for="color in colors" :key="color.name" class="cursor-pointer relative group">
                    <input type="checkbox" v-model="currentFilters.color" :value="color.value" class="peer sr-only">
                     <div class="w-8 h-8 rounded-full border-2 border-transparent peer-checked:border-grand-bone peer-checked:scale-110 transition-all shadow-md group-hover:shadow-lg"
                          :class="color.class">
                     </div>
                     <span class="absolute inset-x-0 -bottom-5 text-[10px] text-center opacity-0 group-hover:opacity-100 transition-opacity">
                        {{ color.name }}
                     </span>
                </label>
            </div>
        </div>

        <!-- Cost -->
        <div>
             <h4 class="font-bold text-sm text-grand-bone mb-3">Coste</h4>
             <input type="range" min="0" max="10" v-model="currentFilters.cost" 
                    class="w-full accent-grand-gold bg-grand-700 rounded-lg h-2 cursor-pointer">
             <div class="flex justify-between text-xs text-grand-500 mt-1">
                <span>0</span>
                <span class="font-bold text-grand-gold text-sm">{{ currentFilters.cost || 'Todos' }}</span>
                <span>10+</span>
             </div>
        </div>

        <!-- Expansion -->
        <div v-if="expansions && expansions.length > 0">
             <h4 class="font-bold text-sm text-grand-bone mb-3">Expansión / Season</h4>
             <select v-model="currentFilters.expansion" multiple 
                     class="w-full bg-grand-900 border border-grand-700 rounded text-grand-bone text-sm p-2 focus:border-grand-gold focus:ring-grand-gold h-32">
                 <option v-for="exp in expansions" :key="exp.code" :value="exp.code">
                     {{ exp.code }} - {{ exp.name }}
                 </option>
             </select>
             <p class="text-[10px] text-grand-500 mt-1">* Usa Ctrl/Cmd para seleccionar varias</p>
        </div>

        <!-- Rarity -->
        <div>
            <h4 class="font-bold text-sm text-grand-bone mb-3">Rareza</h4>
            <div class="grid grid-cols-3 gap-2">
                <label v-for="rarity in rarities" :key="rarity" 
                       class="cursor-pointer border border-grand-700 rounded bg-grand-900 px-2 py-1 text-center text-xs font-bold hover:bg-grand-700 hover:border-grand-gold transition-colors peer-checked:bg-grand-gold peer-checked:text-grand-900">
                    <input type="checkbox" v-model="currentFilters.rarity" :value="rarity" class="peer sr-only">
                     <span :class="currentFilters.rarity.includes(rarity) ? 'text-grand-gold' : 'text-grand-500'">{{ rarity }}</span>
                </label>
            </div>
        </div>
    </div>
</template>
