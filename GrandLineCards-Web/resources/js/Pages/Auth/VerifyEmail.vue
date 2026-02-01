<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900 text-white relative overflow-hidden">
        <Head title="Verificación de Correo" />
        
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500 rounded-full blur-[128px]"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-600 rounded-full blur-[128px]"></div>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-gray-800 shadow-xl overflow-hidden sm:rounded-lg border border-gray-700 relative z-10">
            <div class="mb-4 text-center">
                <h1 class="text-2xl font-bold text-amber-400 mb-2">Verifica tu Identidad</h1>
                <p class="text-sm text-gray-400">
                    Gracias por registrarte. Antes de comenzar tu aventura, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar?
                </p>
            </div>

            <div class="p-4 bg-gray-900/50 rounded-lg border border-gray-700/50 mb-6 text-sm text-gray-300">
                Si no recibiste el correo, con gusto te enviaremos otro.
            </div>

            <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-400 p-3 bg-green-900/20 border border-green-800 rounded">
                Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste durante el registro.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-4 flex items-center justify-between">
                    <button 
                        class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold py-2 px-4 rounded shadow focus:outline-none focus:shadow-outline transition duration-150 ease-in-out" 
                        :class="{ 'opacity-25': form.processing }" 
                        :disabled="form.processing"
                    >
                        Reenviar Correo de Verificación
                    </button>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-400 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    >
                        Cerrar Sesión
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>
