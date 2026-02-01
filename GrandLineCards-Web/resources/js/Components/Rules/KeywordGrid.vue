<template>
    <div class="h-full flex flex-col">
        <div class="mb-6">
            <h3 class="font-display font-bold text-xl text-grand-gold uppercase tracking-wide mb-2">Palabras Clave</h3>
            <p class="text-grand-bone/70 text-sm">Habilidades especiales comunes que encontrarás en las cartas.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 auto-rows-fr overflow-y-auto pr-2 custom-scrollbar">
            <div v-for="(keyword, index) in keywords" :key="index" 
                 class="group bg-grand-800 p-4 rounded-lg border border-grand-700 hover:border-grand-gold hover:bg-grand-800/80 transition-all duration-300 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                        <path :d="keyword.icon" />
                    </svg>
                </div>

                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-grand-gold bg-grand-900/50 p-1.5 rounded-md border border-grand-700 group-hover:border-grand-gold/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="keyword.icon" />
                            </svg>
                        </span>
                        <h4 class="font-bold text-grand-bone text-sm uppercase tracking-wide group-hover:text-white transition-colors">{{ keyword.name }}</h4>
                    </div>
                    
                    <p class="text-xs text-grand-bone/70 leading-relaxed">{{ keyword.desc }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const keywords = [
    {
        name: 'Rush',
        desc: 'Este personaje puede atacar en el mismo turno en el que es jugado. Ignora el mareo de invocación.',
        icon: 'M13 10V3L4 14h7v7l9-11h-7z'
    },
    {
        name: 'Blocker',
        desc: 'Cuando el oponente ataca, puedes girar esta carta (Rest) para convertirla en el nuevo objetivo del ataque.',
        icon: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
    },
    {
        name: 'Banish',
        desc: 'Cuando inflige daño al Líder, la carta de Vida va directo al Cementerio (Trash) en lugar de a la mano. No activa Triggers.',
        icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'
    },
    {
        name: 'Double Attack',
        desc: 'Si inflige daño al Líder, el oponente toma 2 cartas de Vida en lugar de 1.',
        icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
    },
    {
        name: 'Trigger',
        desc: 'Efecto que se activa gratis cuando la carta se toma del área de Vida tras recibir daño.',
        icon: 'M13 10V3L4 14h7v7l9-11h-7z'
    },
    {
        name: 'Counter',
        desc: 'Carta que puedes descartar durante el ataque oponente para sumar su poder (+1000/+2000) a tu defensa.',
        icon: 'M12 6v6m0 0v6m0-6h6m-6 0H6'
    }
];
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(180, 150, 50, 0.3);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(180, 150, 50, 0.5);
}
</style>
