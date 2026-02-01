<script setup>
import { ref, watch } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { debounce } from 'lodash';

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
    router.get(route('admin.users.index'), { search: value }, { preserveState: true, replace: true });
}, 300));

const updateRole = (user, role) => {
    if (confirm(`¿Cambiar rol de ${user.name} a ${role}?`)) {
        router.put(route('admin.users.role', user.id), { role });
    }
};

const toggleBan = (user) => {
    const action = user.banned_at ? 'Desbanear' : 'Banear';
    if (confirm(`¿${action} a ${user.name}?`)) {
        router.post(route('admin.users.ban', user.id));
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Gestión de Usuarios" />

        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-display font-bold text-grand-gold">Usuarios</h1>
            <input 
                v-model="search"
                type="text" 
                placeholder="Buscar por nombre o email..." 
                class="bg-grand-800 text-white border border-grand-600 rounded px-4 py-2 focus:border-grand-gold focus:ring-1 focus:ring-grand-gold w-64"
            />
        </div>

        <div class="bg-grand-800 shadow overflow-hidden sm:rounded-lg border border-grand-700">
            <table class="min-w-full divide-y divide-grand-700">
                <thead class="bg-grand-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-grand-400 uppercase tracking-wider">Usuario</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-grand-400 uppercase tracking-wider">Rol</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-grand-400 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-grand-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-grand-700">
                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-grand-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full object-cover border border-grand-600" :src="user.avatar || 'https://ui-avatars.com/api/?name='+user.name+'&background=0D8ABC&color=fff'" alt="" />
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-grand-bone">{{ user.name }}</div>
                                    <div class="text-sm text-grand-400">{{ user.email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            <span 
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                :class="{
                                    'bg-red-100 text-red-800': user.role === 'admin' || user.role === 'super_admin',
                                    'bg-green-100 text-green-800': user.role === 'user',
                                    'bg-yellow-100 text-yellow-800': user.role === 'moderator'
                                }"
                            >
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span v-if="user.banned_at" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900 text-red-200">
                                BANEADO
                            </span>
                            <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900 text-green-200">
                                Activo
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-3">
                            <!-- Role Dropdown (Simplified) -->
                            <select 
                                @change="updateRole(user, $event.target.value)" 
                                :value="user.role"
                                class="bg-grand-900 text-xs border border-grand-600 rounded text-grand-200 p-1"
                            >
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="moderator">Moderator</option>
                            </select>

                            <button 
                                @click="toggleBan(user)" 
                                class="text-xs font-bold uppercase hover:underline"
                                :class="user.banned_at ? 'text-green-400' : 'text-red-400'"
                            >
                                {{ user.banned_at ? 'Desbanear' : 'Banear' }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            <Pagination :links="users.links" />
        </div>
    </AdminLayout>
</template>
