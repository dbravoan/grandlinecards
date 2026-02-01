<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    addresses: Array,
});

const isAdding = ref(false);
const form = useForm({
    full_name: '',
    line_1: '',
    line_2: '',
    city: '',
    postal_code: '',
    province: '',
    country: 'España',
    phone: '',
    is_default: false,
});

const submit = () => {
    form.post(route('profile.addresses.store'), {
        onSuccess: () => {
            isAdding.value = false;
            form.reset();
        },
    });
};

const deleteAddress = (id) => {
    if (confirm('¿Seguro que quieres borrar esta dirección?')) {
        useForm({}).delete(route('profile.addresses.destroy', id));
    }
};

const makeDefault = (address) => {
    useForm({ ...address, is_default: true }).put(route('profile.addresses.update', address.id));
}
</script>

<template>
    <AppLayout>
        <Head title="Mis Direcciones" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                 <div class="md:grid md:grid-cols-3 md:gap-6">
                    <!-- Sidebar Navigation (Reusable Component needed ideally) -->
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-grand-bone">Libreta de Direcciones</h3>
                            <p class="mt-1 text-sm text-grand-400">
                                Gestiona dónde quieres recibir tus envíos del Vault.
                            </p>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        
                        <!-- List -->
                        <div class="shadow sm:rounded-md sm:overflow-hidden bg-grand-800 border border-grand-700">
                             <div class="px-4 py-5 space-y-6 sm:p-6">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-xl font-display text-grand-gold">Direcciones Guardadas</h2>
                                    <button @click="isAdding = !isAdding" class="bg-grand-gold text-grand-900 px-4 py-2 rounded font-bold uppercase text-xs hover:bg-white transition-colors">
                                        {{ isAdding ? 'Cancelar' : 'Nueva Dirección' }}
                                    </button>
                                </div>

                                <!-- Add Form -->
                                <div v-if="isAdding" class="bg-grand-900 p-4 rounded border border-grand-600">
                                    <form @submit.prevent="submit" class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                                        <div class="col-span-6 sm:col-span-6">
                                            <label class="block text-sm font-medium text-grand-300">Nombre Completo</label>
                                            <input v-model="form.full_name" type="text" class="mt-1 block w-full rounded border-grand-600 bg-grand-800 text-white shadow-sm focus:border-grand-gold focus:ring-grand-gold sm:text-sm">
                                        </div>

                                        <div class="col-span-6">
                                            <label class="block text-sm font-medium text-grand-300">Dirección</label>
                                            <input v-model="form.line_1" type="text" placeholder="Calle, Número, Piso..." class="mt-1 block w-full rounded border-grand-600 bg-grand-800 text-white shadow-sm focus:border-grand-gold focus:ring-grand-gold sm:text-sm">
                                        </div>

                                        <div class="col-span-6">
                                            <label class="block text-sm font-medium text-grand-300">Dirección 2 (Opcional)</label>
                                            <input v-model="form.line_2" type="text" class="mt-1 block w-full rounded border-grand-600 bg-grand-800 text-white shadow-sm focus:border-grand-gold focus:ring-grand-gold sm:text-sm">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-grand-300">Ciudad</label>
                                            <input v-model="form.city" type="text" class="mt-1 block w-full rounded border-grand-600 bg-grand-800 text-white shadow-sm focus:border-grand-gold focus:ring-grand-gold sm:text-sm">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-grand-300">Código Postal</label>
                                            <input v-model="form.postal_code" type="text" class="mt-1 block w-full rounded border-grand-600 bg-grand-800 text-white shadow-sm focus:border-grand-gold focus:ring-grand-gold sm:text-sm">
                                        </div>
                                        
                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-grand-300">Provincia</label>
                                            <input v-model="form.province" type="text" class="mt-1 block w-full rounded border-grand-600 bg-grand-800 text-white shadow-sm focus:border-grand-gold focus:ring-grand-gold sm:text-sm">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-grand-300">Teléfono</label>
                                            <input v-model="form.phone" type="text" class="mt-1 block w-full rounded border-grand-600 bg-grand-800 text-white shadow-sm focus:border-grand-gold focus:ring-grand-gold sm:text-sm">
                                        </div>
                                        
                                        <div class="col-span-6">
                                             <label class="flex items-center">
                                                <input v-model="form.is_default" type="checkbox" class="rounded border-grand-600 bg-grand-800 text-grand-gold focus:ring-grand-gold">
                                                <span class="ml-2 text-sm text-grand-300">Marcar como dirección predeterminada</span>
                                            </label>
                                        </div>

                                        <div class="col-span-6 text-right">
                                            <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-grand-900 bg-grand-gold hover:bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-grand-gold">
                                                Guardar
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Saved List -->
                                <div class="space-y-4">
                                    <div v-if="addresses.length === 0" class="text-center text-grand-500 py-4 italic">
                                        No tienes direcciones guardadas.
                                    </div>
                                    <div v-else v-for="address in addresses" :key="address.id" class="flex justify-between items-start bg-grand-900/50 p-4 rounded border border-grand-700">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-white">{{ address.full_name }}</span>
                                                <span v-if="address.is_default" class="bg-grand-gold text-grand-900 text-xs px-2 py-0.5 rounded font-bold">Por Defecto</span>
                                            </div>
                                            <div class="text-grand-300 text-sm mt-1">
                                                {{ address.line_1 }}<br>
                                                <span v-if="address.line_2">{{ address.line_2 }}<br></span>
                                                {{ address.postal_code }} {{ address.city }} ({{ address.province }})<br>
                                                {{ address.country }} - {{ address.phone }}
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <button v-if="!address.is_default" @click="makeDefault(address)" class="text-xs text-grand-gold hover:underline text-right">Hacer Default</button>
                                            <button @click="deleteAddress(address.id)" class="text-xs text-red-500 hover:text-red-400 hover:underline text-right">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
