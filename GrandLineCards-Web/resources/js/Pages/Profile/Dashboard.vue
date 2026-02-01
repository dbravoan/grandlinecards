<script setup>
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

defineProps({
    auth: Object, // Breeze typically passes auth
});
</script>

<template>
    <Head title="Sala de Capitanes" />

    <UserLayout>
        <template #header>
            Tablero de Mando
        </template>

        <div class="bg-grand-800 overflow-hidden shadow-xl sm:rounded-lg border border-grand-700">
            <div class="p-6 text-grand-bone">
                <h3 class="text-lg font-display font-bold text-grand-gold mb-4">¡Bienvenido a bordo, Capitán!</h3>
                <p class="mb-4 text-grand-300">
                    Aquí podrás gestionar tu colección de cartas, revisar tus pedidos del Bazar y afinar tus estrategias en el constructor de mazos.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                     <!-- Card 1 -->
                     <div class="bg-grand-900 p-4 rounded border border-grand-700/50 hover:border-grand-gold/50 transition-colors cursor-pointer group">
                        <div class="text-grand-gold mb-2 group-hover:scale-110 transition-transform origin-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h4 class="font-display font-bold text-lg mb-1">Mis Mazos</h4>
                        <p class="text-sm text-grand-500">Gestiona tus estrategias de combate.</p>
                     </div>

                     <!-- Card 2 -->
                     <div class="bg-grand-900 p-4 rounded border border-grand-700/50 hover:border-grand-gold/50 transition-colors cursor-pointer group">
                        <div class="text-grand-gold mb-2 group-hover:scale-110 transition-transform origin-left">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                              </svg>
                        </div>
                        <h4 class="font-display font-bold text-lg mb-1">Pedidos</h4>
                        <p class="text-sm text-grand-500">Rastrea tus envíos del Bazar.</p>
                     </div>

                     <!-- Card 3 -->
                     <div class="bg-grand-900 p-4 rounded border border-grand-700/50 hover:border-grand-gold/50 transition-colors cursor-pointer group">
                         <div class="text-grand-gold mb-2 group-hover:scale-110 transition-transform origin-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h4 class="font-display font-bold text-lg mb-1">Perfil</h4>
                        <p class="text-sm text-grand-500">Actualiza tus datos de Nakama.</p>
                     </div>
                </div>
                <!-- Navigation Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                     <Link href="/profile/mazos" class="bg-grand-800 p-6 rounded-lg border border-grand-700 hover:border-grand-gold transition-colors group">
                        <div class="text-3xl mb-4 group-hover:scale-110 transition-transform">⚔️</div>
                        <h3 class="font-bold text-xl text-grand-bone mb-2">Mis Mazos</h3>
                        <p class="text-grand-400 text-sm">Gestiona tus barajas para la batalla.</p>
                     </Link>

                     <Link href="/profile/ventas" class="bg-grand-800 p-6 rounded-lg border border-grand-700 hover:border-grand-gold transition-colors group">
                        <div class="text-3xl mb-4 group-hover:scale-110 transition-transform">💰</div>
                        <h3 class="font-bold text-xl text-grand-bone mb-2">Mis Ventas</h3>
                        <p class="text-grand-400 text-sm">Gestiona tus listados y artículos vendidos.</p>
                     </Link>

                     <Link href="/profile/envios" class="bg-grand-800 p-6 rounded-lg border border-grand-700 hover:border-grand-gold transition-colors group">
                        <div class="text-3xl mb-4 group-hover:scale-110 transition-transform">📦</div>
                        <h3 class="font-bold text-xl text-grand-bone mb-2">Mis Envíos (Vault)</h3>
                        <p class="text-grand-400 text-sm">Envía tus ventas a nuestro almacén.</p>
                     </Link>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
