<template>
    <div class="h-full flex flex-col">
        <div class="mb-6">
            <h3 class="font-display font-bold text-xl text-grand-gold uppercase tracking-wide mb-2">Acciones Disponibles</h3>
            <p class="text-grand-bone/70 text-sm">Durante tu <span class="text-grand-bone font-bold">Main Phase</span>, puedes realizar estas acciones en cualquier orden y tantas veces como puedas.</p>
        </div>

        <div class="grid grid-cols-2 gap-4 auto-rows-fr">
            <div v-for="(action, index) in actions" :key="index" 
                 class="group bg-grand-800 border-b-2 border-grand-700 p-5 rounded-t-lg hover:bg-grand-700/50 hover:border-grand-gold transition-all duration-300 relative overflow-hidden cursor-help"
            >
                <!-- Bg Icon -->
                <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-grand-900 group-hover:text-grand-900/50 transition-colors pointer-events-none" fill="currentColor" viewBox="0 0 24 24">
                    <path :d="action.icon" />
                </svg>

                <div class="relative z-10 flex flex-col h-full gap-3">
                    <div class="w-10 h-10 rounded-full bg-grand-900 border border-grand-700 flex items-center justify-center text-grand-gold group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="action.icon" />
                        </svg>
                    </div>

                    <h4 class="font-bold text-grand-bone text-base uppercase">{{ action.title }}</h4>
                    <p class="text-xs text-grand-bone/70 leading-relaxed">{{ action.desc }}</p>
                    
                    <div class="mt-auto pt-3 border-t border-grand-700/50">
                        <span class="text-[10px] font-bold text-grand-gold uppercase tracking-wider flex items-center gap-1">
                            {{ action.detail }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const actions = [
    {
        title: 'Jugar Carta (Play Card)',
        desc: 'Baja Personajes o Escenarios desde tu mano.',
        detail: 'Paga el coste girando DON!!',
        icon: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'
    },
    {
        title: 'Equipar DON!! (Attach DON!!)',
        desc: 'Potencia a tus Personajes o activa efectos especiales.',
        detail: '+1000 de Poder por carta',
        icon: 'M13 10V3L4 14h7v7l9-11h-7z'
    },
    {
        title: 'Atacar (Attack)',
        desc: 'Usa tu Líder o Personajes para dañar al oponente.',
        detail: 'Gira la carta (Rest) para atacar',
        icon: 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    {
        title: 'Activar Efecto (Activate Effect)',
        desc: 'Usa habilidades de cartas que ya están en el campo.',
        detail: '[Activate:Main] requiere acción',
        icon: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'
    }
];
</script>
