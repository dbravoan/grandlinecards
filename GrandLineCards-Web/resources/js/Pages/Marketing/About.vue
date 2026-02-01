<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import paperTexture from '../../../images/paper-texture.png';
</script>

<template>
    <AppLayout>
        <Head title="Nosotros - Grand Line Cards" />

        <!-- Hero Section -->
        <div class="relative bg-grand-900 border-b border-grand-gold/20 overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none" :style="{ backgroundImage: `url(${paperTexture})` }"></div>
            <div class="max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <h1 class="font-display text-5xl md:text-6xl text-grand-gold mb-6 tracking-wide drop-shadow-lg">
                    Tu Puerto Seguro en el Grand Line
                </h1>
                <p class="text-xl text-grand-bone max-w-3xl mx-auto leading-relaxed">
                    La plataforma definitiva para coleccionistas de One Piece TCG. 
                    Compra, vende, gestiona tu colección y domina el juego con nuestras herramientas de inteligencia.
                </p>
                <div class="mt-10 flex justify-center gap-4">
                    <Link href="/register" class="px-8 py-3 bg-grand-gold text-grand-900 font-bold uppercase tracking-wider rounded shadow hover:bg-white transition-colors">
                        Unirse a la Tripulación
                    </Link>
                    <Link href="/cartas" class="px-8 py-3 bg-transparent border border-grand-gold text-grand-gold font-bold uppercase tracking-wider rounded hover:bg-grand-gold/10 transition-colors">
                        Explorar Cartas
                    </Link>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="max-w-7xl mx-auto py-20 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Feature 1: The Vault -->
                <div class="text-center group p-6 rounded-lg bg-grand-800/50 hover:bg-grand-800 transition-colors border border-transparent hover:border-grand-gold/30">
                    <div class="text-6xl mb-6 transform group-hover:scale-110 transition-transform duration-300">🏰</div>
                    <h3 class="font-display text-2xl text-grand-gold mb-4">El Vault</h3>
                    <p class="text-grand-300">
                        Olvídate de pagar 20 envíos distintos. Compra cartas de múltiples vendedores y envíalas a nuestro <strong class="text-white">Almacén Central (The Vault)</strong>. 
                        Nosotros las consolidamos y te enviamos un único paquete a casa.
                    </p>
                </div>

                <!-- Feature 2: Marketplace P2P -->
                <div class="text-center group p-6 rounded-lg bg-grand-800/50 hover:bg-grand-800 transition-colors border border-transparent hover:border-grand-gold/30">
                    <div class="text-6xl mb-6 transform group-hover:scale-110 transition-transform duration-300">⚖️</div>
                    <h3 class="font-display text-2xl text-grand-gold mb-4">Bazar P2P</h3>
                    <p class="text-grand-300">
                        Compra y vende singles con total seguridad. Nuestro sistema anti-fraude y verificación de estado garantiza que lo que compras es lo que recibes.
                        <br>
                        <span class="text-sm text-grand-400 mt-2 block">(Próximamente Subastas)</span>
                    </p>
                </div>

                <!-- Feature 3: Data & Academy -->
                <div class="text-center group p-6 rounded-lg bg-grand-800/50 hover:bg-grand-800 transition-colors border border-transparent hover:border-grand-gold/30">
                    <div class="text-6xl mb-6 transform group-hover:scale-110 transition-transform duration-300">🧭</div>
                    <h3 class="font-display text-2xl text-grand-gold mb-4">La Brújula</h3>
                    <p class="text-grand-300">
                        Accede a nuestra <strong class="text-white">Enciclopedia</strong> completa, analiza el <strong class="text-white">Meta</strong> actual y aprende estrategias en la <strong class="text-white">Academia Pirata</strong>.
                        Datos en tiempo real para tomar las mejores decisiones.
                    </p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-grand-800 border-y border-grand-700">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <h2 class="font-display text-3xl text-white mb-2">¿Listo para zarpar?</h2>
                    <p class="text-grand-400">Crea tu cuenta hoy y recibe acceso completo a todas las herramientas.</p>
                </div>
                <Link href="/register" class="flex-shrink-0 px-8 py-4 bg-gradient-to-r from-grand-gold to-orange-500 text-grand-900 font-bold uppercase tracking-widest rounded shadow-lg hover:shadow-orange-500/20 transition-all transform hover:-translate-y-1">
                    Crear Cuenta Gratis
                </Link>
            </div>
        </div>

    </AppLayout>
</template>
