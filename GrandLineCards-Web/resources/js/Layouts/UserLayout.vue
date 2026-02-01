<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const user = usePage().props.auth.user;
const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-grand-900 text-grand-bone font-sans selection:bg-grand-gold selection:text-grand-900 flex flex-col">
        <!-- Navbar -->
        <nav class="sticky top-0 z-50 bg-grand-900/95 backdrop-blur-md border-b border-grand-gold/20 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <Link href="/" class="flex items-center gap-3 group">
                            <div class="w-10 h-10 rounded bg-gradient-to-br from-grand-gold to-grand-accent flex items-center justify-center shadow-md group-hover:shadow-grand-gold/50 transition-all duration-300">
                                <span class="font-display font-bold text-grand-900 text-xl">G</span>
                            </div>
                            <span class="font-display font-bold text-xl tracking-wider text-grand-bone group-hover:text-grand-gold transition-colors">GRAND LINE</span>
                        </Link>
                    </div>

                    <!-- Desktop Navigation (Profile Context) -->
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-8">
                            <Link :href="route('profile.dashboard')" 
                                  class="font-display font-medium transition-colors"
                                  :class="route().current('profile.dashboard') ? 'text-grand-gold border-b-2 border-grand-gold pb-1' : 'hover:text-grand-gold text-grand-bone'">
                                Sala de Capitanes
                            </Link>
                            <Link :href="route('profile.decks.index')" 
                                  class="font-display font-medium transition-colors"
                                  :class="route().current('profile.decks.*') ? 'text-grand-gold border-b-2 border-grand-gold pb-1' : 'hover:text-grand-gold text-grand-bone'">
                                Mis Mazos
                            </Link>
                            <Link href="/tienda" class="font-display font-medium hover:text-grand-gold transition-colors text-grand-bone">
                                Ir al Bazar
                            </Link>
                        </div>
                    </div>

                    <!-- User Actions -->
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                             <div class="text-right hidden lg:block">
                                <p class="text-xs text-grand-500 uppercase tracking-widest">Capitán</p>
                                <p class="font-display font-bold text-grand-gold">{{ user.name }}</p>
                             </div>
                             <div class="h-10 w-10 rounded-full bg-grand-800 border border-grand-gold flex items-center justify-center text-grand-gold font-bold">
                                {{ user.name.charAt(0) }}
                             </div>
                        </div>

                        <div class="ml-4 pl-4 border-l border-grand-700">
                             <Link :href="route('logout')" method="post" as="button" class="text-red-400 hover:text-red-300 text-sm font-medium transition-colors uppercase tracking-wider">
                                Salir
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <header class="bg-grand-800 shadow border-b border-grand-700" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-display font-bold text-xl text-grand-gold leading-tight">
                    <slot name="header" />
                </h2>
            </div>
        </header>

        <main class="flex-grow relative">
             <!-- Background Texture -->
             <div class="absolute inset-0 z-0 opacity-5 pointer-events-none bg-paper-texture mix-blend-overlay"></div>
             
             <div class="relative z-10 max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <slot />
             </div>
        </main>

        <!-- Footer -->
        <footer class="bg-grand-800 border-t border-grand-700 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center text-grand-500 text-sm">
                © 2026 Grand Line Cards. Panel de Usuario.
            </div>
        </footer>
    </div>
</template>
