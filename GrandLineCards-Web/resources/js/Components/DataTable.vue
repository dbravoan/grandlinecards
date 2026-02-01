<script setup>
import { computed } from 'vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    items: Object, // Paginated object
    columns: Array, // [{ label: 'Name', key: 'name', slot: 'name' }]
});
</script>

<template>
    <div class="bg-grand-900 rounded-lg shadow overflow-hidden border border-grand-700">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-grand-800 border-b border-grand-700 text-grand-gold uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th v-for="col in columns" :key="col.key || col.label" class="px-6 py-4">
                            {{ col.label }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-grand-700">
                    <tr v-for="item in items.data" :key="item.id" class="hover:bg-grand-800 transition-colors text-grand-bone text-sm">
                        <td v-for="col in columns" :key="col.key || col.label" class="px-6 py-4">
                            <slot v-if="col.slot" :name="col.slot" :item="item" />
                            <span v-else>{{ item[col.key] }}</span>
                        </td>
                    </tr>
                    <tr v-if="items.data.length === 0">
                        <td :colspan="columns.length" class="px-6 py-8 text-center text-grand-500 italic">
                            No records found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-grand-700 bg-grand-900">
            <Pagination :links="items.links" />
        </div>
    </div>
</template>
