<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen flex bg-gray-900 text-white relative overflow-hidden">
        <Head title="Registrarse - Grand Line Cards" />

        <!-- Background Elements -->
        <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500 rounded-full blur-[128px] transform translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-600 rounded-full blur-[128px] transform -translate-x-1/2 translate-y-1/2"></div>
        </div>

        <div class="container mx-auto px-6 py-12 relative z-10 flex flex-col md:flex-row-reverse items-center gap-12 lg:gap-24">
            
            <!-- Marketing Column -->
            <div class="w-full md:w-1/2 space-y-8 animate-fade-in-up">
                <div>
                   <h1 class="text-4xl md:text-5xl font-bold font-serif tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-blue-500 mb-4 drop-shadow-sm">
                        Comienza tu Aventura
                    </h1>
                    <p class="text-lg text-gray-300 leading-relaxed max-w-lg">
                        Crea tu perfil de capitán y accede a todas las funciones premium de Grand Line Cards.
                    </p>
                </div>

                <ul class="space-y-4 text-gray-300">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span>Gestiona múltiples mazos y versiones</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span>Participa en torneos y eventos exclusivos</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-green-500/20 text-green-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span>Seguimiento de precios en tiempo real</span>
                    </li>
                </ul>
            </div>

            <!-- Register Form -->
            <div class="w-full md:w-1/2 max-w-md">
                <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-xl overflow-hidden relative">
                    <!-- Card Decoration -->
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 via-blue-200 to-blue-500"></div>

                    <div class="p-8">
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-white">Únete a la Tripulación</h2>
                            <p class="text-gray-400 text-sm mt-1">Completa tus datos para registrarte</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Social Login -->
                            <div class="grid grid-cols-2 gap-4">
                                <a :href="route('socialite.redirect', 'google')" class="flex items-center justify-center gap-2 bg-white text-gray-700 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors font-medium text-sm">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                                    Google
                                </a>
                                <a :href="route('socialite.redirect', 'apple')" class="flex items-center justify-center gap-2 bg-black text-white py-2.5 rounded-lg hover:bg-gray-900 transition-colors font-medium text-sm">
                                    <svg class="w-5 h-5" viewBox="0 0 384 512" fill="white"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"/></svg>
                                    Apple
                                </a>
                            </div>

                            <div class="relative flex items-center">
                                <span class="w-full border-t border-gray-600"></span>
                                <span class="bg-gray-800 px-3 text-xs text-gray-400 uppercase">O completa tus datos</span>
                                <span class="w-full border-t border-gray-600"></span>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nombre de Capitán</label>
                                <input 
                                    id="name" 
                                    type="text" 
                                    class="w-full bg-gray-900/50 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-600" 
                                    v-model="form.name" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    placeholder="Monkey D. Luffy"
                                />
                                <div v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Correo Electrónico</label>
                                <input 
                                    id="email" 
                                    type="email" 
                                    class="w-full bg-gray-900/50 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-600" 
                                    v-model="form.email" 
                                    required 
                                    autocomplete="username"
                                    placeholder="capitan@ejemplo.com"
                                />
                                <div v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Contraseña</label>
                                <input 
                                    id="password" 
                                    type="password" 
                                    class="w-full bg-gray-900/50 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                    v-model="form.password" 
                                    required 
                                    autocomplete="new-password" 
                                />
                                <div v-if="form.errors.password" class="mt-1 text-sm text-red-400">{{ form.errors.password }}</div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirmar Contraseña</label>
                                <input 
                                    id="password_confirmation" 
                                    type="password" 
                                    class="w-full bg-gray-900/50 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                                    v-model="form.password_confirmation" 
                                    required 
                                    autocomplete="new-password" 
                                />
                                <div v-if="form.errors.password_confirmation" class="mt-1 text-sm text-red-400">{{ form.errors.password_confirmation }}</div>
                            </div>

                            <button 
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-400 hover:to-blue-500 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform hover:-translate-y-0.5 transition-all duration-200" 
                                :class="{ 'opacity-75 cursor-not-allowed': form.processing }" 
                                :disabled="form.processing"
                            >
                                <span v-if="!form.processing">Registrarse</span>
                                <span v-else>Procesando...</span>
                            </button>
                        </form>
                    </div>
                    
                    <div class="bg-gray-900/50 px-8 py-4 border-t border-gray-700/50 text-center">
                        <p class="text-sm text-gray-400">
                            ¿Ya tienes cuenta? 
                            <Link :href="route('login')" class="text-blue-400 font-semibold hover:text-blue-300 transition-colors ml-1">
                                Iniciar Sesión
                            </Link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
