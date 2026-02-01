<template>
    <div class="bg-grand-900 rounded-xl overflow-hidden shadow-2xl border border-grand-700">
        <!-- Tabs Navigation -->
        <div class="flex overflow-x-auto border-b border-grand-700 hide-scrollbar scroll-smooth">
            <button 
                v-for="tab in tabs" 
                :key="tab.id"
                @click="currentTab = tab.id"
                class="flex-shrink-0 px-6 py-4 text-sm font-bold uppercase tracking-wider transition-all relative group whitespace-nowrap"
                :class="currentTab === tab.id ? 'text-grand-gold bg-grand-800' : 'text-grand-bone/60 hover:text-grand-bone hover:bg-grand-800/50'"
            >
                <div class="flex items-center gap-2">
                    <component :is="tab.icon" class="w-5 h-5" />
                    {{ tab.label }}
                </div>
                <!-- Active Indicator -->
                <div 
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-grand-gold transform transition-transform duration-300 origin-left"
                    :class="currentTab === tab.id ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100'"
                ></div>
            </button>
        </div>

        <!-- Content Area -->
        <div class="p-6 md:p-8 min-h-[500px] relative">
            <Transition 
                mode="out-in"
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="transform translate-x-4 opacity-0"
                enter-to-class="transform translate-x-0 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform translate-x-0 opacity-100"
                leave-to-class="transform -translate-x-4 opacity-0"
            >
                <component :is="activeComponent" class="h-full" />
            </Transition>
        </div>

        <!-- Footer / Navigation -->
        <div class="bg-grand-800 p-4 border-t border-grand-700 flex justify-between items-center">
            <button 
                @click="prevTab"
                :disabled="isFirstTab"
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-grand-bone font-bold text-sm transition-colors disabled:opacity-30 disabled:cursor-not-allowed hover:bg-grand-700"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Anterior
            </button>

            <div class="flex gap-1">
                <div 
                    v-for="tab in tabs" 
                    :key="tab.id"
                    class="w-2 h-2 rounded-full transition-colors"
                    :class="currentTab === tab.id ? 'bg-grand-gold' : 'bg-grand-700'"
                ></div>
            </div>

            <button 
                @click="nextTab"
                :disabled="isLastTab"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-grand-gold text-grand-900 font-bold text-sm shadow-md transition-all hover:bg-grand-accent hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none disabled:bg-grand-700 disabled:text-grand-bone"
            >
                {{ isLastTab ? 'Finalizar' : 'Siguiente' }}
                <svg v-if="!isLastTab" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, defineAsyncComponent, markRaw } from 'vue';
import { 
    ClipboardDocumentListIcon, 
    ArrowPathIcon, 
    HandRaisedIcon, 
    FireIcon, 
    BookOpenIcon 
} from '@heroicons/vue/24/outline';

const SetupPhase = defineAsyncComponent(() => import('./SetupPhase.vue'));
const TurnTimeline = defineAsyncComponent(() => import('./TurnTimeline.vue'));
const ActionPhase = defineAsyncComponent(() => import('./ActionPhase.vue'));
const CombatFlow = defineAsyncComponent(() => import('./CombatFlow.vue'));
const KeywordGrid = defineAsyncComponent(() => import('./KeywordGrid.vue'));

const tabs = [
    { id: 'setup', label: 'Preparación', icon: markRaw(ClipboardDocumentListIcon), component: SetupPhase },
    { id: 'turn', label: 'Flujo del Turno', icon: markRaw(ArrowPathIcon), component: TurnTimeline },
    { id: 'actions', label: 'Acciones Main', icon: markRaw(HandRaisedIcon), component: ActionPhase },
    { id: 'combat', label: 'Combate', icon: markRaw(FireIcon), component: CombatFlow },
    { id: 'keywords', label: 'Glosario', icon: markRaw(BookOpenIcon), component: KeywordGrid },
];

const currentTab = ref('setup');

const activeComponent = computed(() => {
    return tabs.find(t => t.id === currentTab.value)?.component;
});

const currentIndex = computed(() => tabs.findIndex(t => t.id === currentTab.value));
const isFirstTab = computed(() => currentIndex.value === 0);
const isLastTab = computed(() => currentIndex.value === tabs.length - 1);

const nextTab = () => {
    if (!isLastTab.value) {
        currentTab.value = tabs[currentIndex.value + 1].id;
    }
};

const prevTab = () => {
    if (!isFirstTab.value) {
        currentTab.value = tabs[currentIndex.value - 1].id;
    }
};
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
