<script setup>
import { ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DataTable from '@/Components/DataTable.vue';
import { Head, Link } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';

const props = defineProps({
    cards: Object,
    filters: Object,
});

const search = ref(props.filters.q || '');

// Debounce search
watch(search, debounce((value) => {
    router.get('/dbadmin/cards', { q: value }, { preserveState: true, preserveScroll: true });
}, 300));

const columns = [
    { label: 'ID', key: 'card_id' },
    { label: 'Type', key: 'type' },
    { label: 'Color', key: 'color' },
    { label: 'Cost', key: 'cost' },
    { label: 'Rarity', key: 'rarity' },
    { label: 'Actions', slot: 'actions' },
];
</script>

<template>
    <Head title="Catalog Explorer" />

    <AdminLayout>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-grand-gold">Card Catalog</h2>
            <div class="flex gap-4">
                 <input v-model="search" type="text" placeholder="Search by ID..." class="bg-grand-900 border-grand-700 rounded text-grand-bone focus:border-grand-gold focus:ring-grand-gold placeholder-grand-600" />
                 <Link href="/dbadmin/translations/burst" class="bg-grand-gold text-grand-900 font-bold px-4 py-2 rounded hover:bg-white transition-colors">
                    Start Translation
                 </Link>
            </div>
        </div>

        <DataTable :items="cards" :columns="columns">
            <template #actions="{ item }">
                <div class="flex gap-2">
                    <Link :href="`/dbadmin/translations/${item.id}/edit`" class="text-grand-400 hover:text-grand-gold">
                        Translate
                    </Link>
                </div>
            </template>
        </DataTable>
    </AdminLayout>
</template>
