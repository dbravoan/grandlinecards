<script setup>
import { ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const user = usePage().props.auth.admin;

const logout = () => {
    // Assuming logout route is admin.logout
    // If not, we might need to use a form submission or a custom route
    // The route list showed admin.logout as POST
    // Inertia Link method="post" works
};
</script>

<template>
    <div class="min-h-screen bg-slate-100 dark:bg-slate-900 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 border-r border-slate-700 hidden md:block">
            <div class="p-6 border-b border-slate-700">
                 <Link :href="route('admin.dashboard')" class="text-cyan-400 font-bold font-mono text-xl tracking-wider">
                    DB_ADMIN
                </Link>
            </div>
            <nav class="mt-6 px-4 space-y-2">
                <Link :href="route('admin.dashboard')" 
                      class="block px-4 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white transition"
                      :class="{ 'bg-slate-700 text-white': route().current('admin.dashboard') }">
                    Dashboard
                </Link>
                
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-mono text-slate-500 uppercase">Content</p>
                </div>

                <Link :href="route('admin.posts.index')" 
                      class="block px-4 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white transition"
                      :class="{ 'bg-slate-700 text-white': route().current('admin.posts.*') }">
                    Blog Posts
                </Link>

                <Link :href="route('admin.translations.index')" 
                      class="block px-4 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white transition"
                      :class="{ 'bg-slate-700 text-white': route().current('admin.translations.*') }">
                    Translations
                </Link>

                <Link :href="route('admin.taxonomies.index')" 
                      class="block px-4 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white transition"
                      :class="{ 'bg-slate-700 text-white': route().current('admin.taxonomies.*') }">
                    Taxonomies
                </Link>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-mono text-slate-500 uppercase">System</p>
                </div>

                <Link :href="route('admin.inventory.index')" 
                      class="block px-4 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white transition"
                      :class="{ 'bg-slate-700 text-white': route().current('admin.inventory.*') }">
                    Inventory
                </Link>

                <Link :href="route('admin.logistics.index')" 
                      class="block px-4 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white transition"
                      :class="{ 'bg-slate-700 text-white': route().current('admin.logistics.*') }">
                    Vault Logistics
                </Link>

                <Link :href="route('admin.users.index')" 
                      class="block px-4 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white transition"
                      :class="{ 'bg-slate-700 text-white': route().current('admin.users.*') }">
                    Usuarios
                </Link>
                
                <Link :href="route('admin.scraper.index')" 
                      class="block px-4 py-2 rounded-md text-slate-300 hover:bg-slate-700 hover:text-white transition"
                      :class="{ 'bg-slate-700 text-white': route().current('admin.scraper.*') }">
                    Scraper Tools
                </Link>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-slate-800 border-b border-slate-700 h-16 flex items-center justify-between px-6">
                <div class="text-slate-200 font-mono">
                     <span v-if="$slots.header"><slot name="header" /></span>
                     <span v-else>Dashboard</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-slate-400 text-sm">{{ user?.name }} (Admin)</span>
                    <Link :href="route('admin.logout')" method="post" as="button" class="text-red-400 hover:text-red-300 text-sm font-mono">
                        [LOGOUT]
                    </Link>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 text-slate-200">
                <slot />
            </main>
        </div>
    </div>
</template>
