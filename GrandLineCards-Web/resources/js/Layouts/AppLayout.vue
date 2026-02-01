<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';

// Mobile menu state
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

                    <!-- Desktop Navigation -->
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-8">
                            <Link href="/cartas" class="font-display font-medium hover:text-grand-gold transition-colors">Enciclopedia</Link>
                            <Link href="/meta" class="font-display font-medium hover:text-grand-gold transition-colors">Meta</Link>
                            <Link href="/academia" class="font-display font-medium hover:text-grand-gold transition-colors">Academia</Link>
                            <Link href="/noticias" class="font-display font-medium hover:text-grand-gold transition-colors">Noticias</Link>
                            <Link href="/tienda" class="font-display font-medium hover:text-grand-gold transition-colors">Bazar</Link>
                        </div>
                    </div>

                    <!-- Search & Actions (Desktop) -->
                    <div class="hidden md:flex items-center gap-4">
                        <!-- Search Bar -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-grand-500 group-focus-within:text-grand-gold transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   class="block w-full pl-10 pr-3 py-2 border border-grand-700 rounded-md leading-5 bg-grand-800 text-grand-bone placeholder-grand-500 focus:outline-none focus:bg-grand-900 focus:border-grand-gold focus:ring-1 focus:ring-grand-gold sm:text-sm transition-all duration-300 w-48 lg:w-64" 
                                   placeholder="Buscar..." />
                        </div>

                        <!-- Login/Profile Button -->
                         <div v-if="$page.props.auth.user" class="relative">
                            <Link :href="route('profile.show')" class="flex items-center gap-2 text-grand-gold font-bold hover:text-white transition-colors">
                                <span>{{ $page.props.auth.user.name }}</span>
                            </Link>
                            <!-- Assuming a dropdown for authenticated user, adding the requested link here -->
                            <DropdownLink :href="route('profile.collection.index')">
                                Mi Colección
                            </DropdownLink>
                        </div>
                        <template v-else>
                            <Link :href="route('login')" class="relative inline-flex items-center justify-center px-6 py-2 overflow-hidden font-display font-bold tracking-tighter text-grand-900 bg-grand-gold rounded-sm group hover:bg-grand-accent transition-colors clip-path-seal">
                                <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                                <span class="relative">Ingresar</span>
                            </Link>
                        </template>
                    </div>

                    <!-- Mobile Hamburger -->
                    <div class="-mr-2 flex md:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-grand-400 hover:text-grand-gold hover:bg-grand-800 focus:outline-none focus:bg-grand-800 focus:text-grand-gold transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="md:hidden bg-grand-900 border-b border-grand-700">
                <div class="pt-2 pb-3 space-y-1">
                    <Link href="/cartas" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-grand-300 hover:text-grand-bone hover:bg-grand-800 hover:border-grand-gold transition duration-150 ease-in-out">Enciclopedia</Link>
                    <Link href="/meta" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-grand-300 hover:text-grand-bone hover:bg-grand-800 hover:border-grand-gold transition duration-150 ease-in-out">Meta</Link>
                    <Link href="/academia" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-grand-300 hover:text-grand-bone hover:bg-grand-800 hover:border-grand-gold transition duration-150 ease-in-out">Academia</Link>
                    <Link href="/tienda" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-grand-300 hover:text-grand-bone hover:bg-grand-800 hover:border-grand-gold transition duration-150 ease-in-out">Bazar</Link>
                    
                    <!-- Mobile Search -->
                    <div class="px-4 py-2">
                        <input type="text" class="block w-full border-grand-700 rounded bg-grand-800 text-grand-bone placeholder-grand-500 focus:border-grand-gold focus:ring-grand-gold text-sm" placeholder="Buscar..." />
                    </div>
                </div>
                
                <!-- Mobile Auth -->
                <div class="pt-4 pb-4 border-t border-grand-700">
                    <div v-if="$page.props.auth.user" class="px-4 flex items-center">
                        <div class="ml-3">
                            <div class="font-medium text-base text-grand-gold">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-grand-500">{{ $page.props.auth.user.email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1" v-if="$page.props.auth.user">
                        <Link href="/profile" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-grand-300 hover:text-grand-bone hover:bg-grand-800 hover:border-grand-gold transition duration-150 ease-in-out">Mi Perfil</Link>
                        <Link href="/logout" method="post" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-red-400 hover:text-red-300 hover:bg-grand-800 hover:border-red-400 transition duration-150 ease-in-out">Cerrar Sesión</Link>
                    </div>
                     <div class="mt-3 space-y-1 px-4" v-else>
                         <Link href="/login" class="block w-full text-center py-2 bg-grand-gold text-grand-900 font-bold rounded">Ingresar</Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow relative">
             <FlashMessage />
             
             <!-- Background Texture -->
             <div class="absolute inset-0 z-0 opacity-5 pointer-events-none bg-paper-texture mix-blend-overlay"></div>
             
             <div class="relative z-10 max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <slot />
             </div>
        </main>

        <!-- Footer -->
        <footer class="bg-grand-900 border-t border-grand-700 pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                    <!-- Brand -->
                    <div class="space-y-4">
                         <div class="flex items-center gap-2">
                             <div class="w-8 h-8 rounded bg-gradient-to-br from-grand-gold to-grand-accent flex items-center justify-center font-display font-bold text-grand-900">
                                 G
                             </div>
                             <span class="font-display font-bold text-xl tracking-wider text-grand-bone">GRAND LINE</span>
                         </div>
                         <p class="text-grand-400 text-sm leading-relaxed">
                             Tu base de datos y mercado de confianza para One Piece Card Game. 
                             Gestiona tu colección, domina el meta y completa tu mazo.
                         </p>
                    </div>

                    <!-- Links: Encyclopedia -->
                    <div>
                        <h3 class="font-display text-grand-gold font-bold uppercase tracking-wider mb-4">Enciclopedia</h3>
                        <ul class="space-y-2 text-sm text-grand-300">
                            <li><Link href="/cartas" class="hover:text-white transition-colors">Catálogo Completo</Link></li>
                            <li><Link href="/cartas?rarity=Leader" class="hover:text-white transition-colors">Líderes</Link></li>
                            <li><Link href="/cartas?rarity=SEC" class="hover:text-white transition-colors">Secret Rares</Link></li>
                            <li><Link href="/meta" class="hover:text-white transition-colors">Meta Tier List</Link></li>
                        </ul>
                    </div>

                    <!-- Links: Community -->
                    <div>
                        <h3 class="font-display text-grand-gold font-bold uppercase tracking-wider mb-4">Comunidad</h3>
                        <ul class="space-y-2 text-sm text-grand-300">
                            <li><Link href="/academia" class="hover:text-white transition-colors">Academia Pirata</Link></li>
                            <li><Link href="/profile/decks" class="hover:text-white transition-colors">Constructor de Mazos</Link></li>
                            <li><Link href="/noticias" class="hover:text-white transition-colors">Noticias</Link></li>
                            <li><Link href="/eventos" class="hover:text-white transition-colors">Eventos & Torneos</Link></li>
                        </ul>
                    </div>

                    <!-- Links: Market -->
                    <div>
                        <h3 class="font-display text-grand-gold font-bold uppercase tracking-wider mb-4">Mercado</h3>
                        <ul class="space-y-2 text-sm text-grand-300">
                            <li><Link href="/tienda" class="hover:text-white transition-colors">Comprar Cartas</Link></li>
                            <li><Link href="/profile/sales" class="hover:text-white transition-colors">Vender Cartas</Link></li>
                            <li><Link href="/logistica" class="hover:text-white transition-colors">Envíos y Tarifas</Link></li>
                            <li><Link href="/nosotros" class="hover:text-white transition-colors">Sobre Nosotros</Link></li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-grand-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-grand-500 text-sm">© 2026 Grand Line Cards. Todos los derechos reservados. One Piece es marca registrada de Eiichiro Oda/Shueisha/Toei Animation.</p>
                    
                    <!-- Server Status -->
                    <div class="flex items-center gap-3 bg-black/30 px-3 py-1.5 rounded border border-grand-800">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <span class="text-[10px] font-mono text-grand-400 uppercase tracking-widest">Sistemas Operativos</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Optional: specific component styles if tailwind isn't enough */
.clip-path-seal {
    /* Simple approximation of a wax seal or rugged shape */
    clip-path: polygon(5% 0%, 95% 0%, 100% 5%, 100% 95%, 95% 100%, 5% 100%, 0% 95%, 0% 5%);
}
</style>
