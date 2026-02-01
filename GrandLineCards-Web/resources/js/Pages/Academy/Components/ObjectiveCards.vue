<script setup>
import { TrophyIcon, DocumentMinusIcon } from '@heroicons/vue/24/outline';

const objectives = [
    {
        title: "Atacar a la Vida",
        description: "Reduce las cartas de Vida de tu oponente a 0 y asesta un ataque directo.",
        icon: TrophyIcon,
        color: "text-red-500",
        bg: "bg-red-500/10",
        border: "border-red-500/20"
    },
    {
        title: "Sin Mazo (Deck Out)",
        description: "Si tu oponente no puede robar una carta cuando es requerido, ganas instantáneamente.",
        icon: DocumentMinusIcon,
        color: "text-blue-500",
        bg: "bg-blue-500/10",
        border: "border-blue-500/20"
    }
];
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div 
            v-for="obj in objectives" 
            :key="obj.title"
            class="relative overflow-hidden rounded-xl border p-6 transition-all hover:scale-[1.02]"
            :class="[obj.bg, obj.border]"
        >
            <div class="flex items-center gap-4 mb-3">
                <div class="p-3 rounded-lg bg-white/10 backdrop-blur-sm">
                    <component :is="obj.icon" class="w-8 h-8" :class="obj.color" />
                </div>
                <h3 class="text-xl font-bold font-display tracking-wider text-grand-bone">{{ obj.title }}</h3>
            </div>
            <p class="text-gray-300 leading-relaxed pl-[4.5rem]">{{ obj.description }}</p>
        </div>
    </div>
</template>
