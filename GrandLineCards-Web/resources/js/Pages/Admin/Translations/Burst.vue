<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    card: Object,
    original: Object,
    translation: Object,
    flash: Object, // Success messages
});

const form = useForm({
    name: props.translation?.name || '',
    effect_text: props.translation?.effect_text || '',
    trigger_text: props.translation?.trigger_text || '',
    notes: props.translation?.notes || '',
});

const submit = () => {
    form.put(`/dbadmin/translations/${props.card.id}`, {
        onSuccess: () => {
            // Toast handled by layout usually, or we can just proceed
            // The controller redirects to index -> which redirects to NEXT card.
        }
    });
};

const aiTranslate = () => {
    if (confirm('Use AI to generate translation? This will overwrite current fields.')) {
        router.put(`/dbadmin/translations/${props.card.id}`, 
            { ai: true }, 
            { 
                preserveScroll: true,
                onSuccess: () => {
                    // Reload to get new data
                }
            }
        );
    }
};
</script>

<template>
    <Head title="Translation Editor" />

    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-grand-gold">Translation Burst Mode</h2>
            <div class="text-grand-500">
                Editing: <span class="font-mono text-grand-bone">{{ card.card_id }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-[calc(100vh-200px)]">
            <!-- Left: Original (Reference) -->
            <div class="bg-grand-900 border border-grand-700 rounded-lg p-6 overflow-y-auto">
                <div class="mb-4">
                    <h3 class="text-xs uppercase tracking-wider text-grand-500">Original Name</h3>
                    <p class="text-lg font-bold text-grand-bone">{{ original?.name || '(No English Name)' }}</p>
                </div>

                <div class="mb-4">
                    <h3 class="text-xs uppercase tracking-wider text-grand-500">Effect</h3>
                    <div class="bg-black/30 p-4 rounded text-grand-200 whitespace-pre-wrap font-serif">
                        {{ original?.effect_text || '(No Effect Text)' }}
                    </div>
                </div>

                 <div class="mb-4">
                    <h3 class="text-xs uppercase tracking-wider text-grand-500">Trigger</h3>
                    <div class="bg-black/30 p-4 rounded text-grand-200 whitespace-pre-wrap font-serif">
                        {{ original?.trigger_text || '(No Trigger)' }}
                    </div>
                </div>

                <div class="mt-8 border-t border-grand-700 pt-4">
                     <img v-if="card.image_url" :src="card.image_url" class="w-48 mx-auto rounded shadow-lg" alt="Card Image" />
                </div>
            </div>

            <!-- Right: Edit (Form) -->
            <div class="bg-grand-800 border border-grand-700 rounded-lg p-6 flex flex-col">
                <div class="flex justify-end mb-4">
                     <button type="button" @click="aiTranslate" class="text-xs bg-purple-600 hover:bg-purple-500 text-white px-3 py-1 rounded flex items-center gap-2 transition-colors">
                        ✨ AI Magic
                     </button>
                </div>

                <form @submit.prevent="submit" class="flex-1 flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-medium text-grand-400 mb-1">Spanish Name</label>
                        <input v-model="form.name" type="text" class="w-full bg-grand-900 border-grand-700 rounded focus:border-grand-gold focus:ring-grand-gold text-grand-bone" />
                        <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-grand-400 mb-1">Spanish Effect</label>
                        <textarea v-model="form.effect_text" class="w-full h-32 bg-grand-900 border-grand-700 rounded focus:border-grand-gold focus:ring-grand-gold text-grand-bone font-serif"></textarea>
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-grand-400 mb-1">Spanish Trigger</label>
                        <textarea v-model="form.trigger_text" class="w-full h-24 bg-grand-900 border-grand-700 rounded focus:border-grand-gold focus:ring-grand-gold text-grand-bone font-serif"></textarea>
                    </div>

                     <div>
                        <label class="block text-sm font-medium text-grand-400 mb-1">Notes</label>
                        <input v-model="form.notes" type="text" class="w-full bg-grand-900 border-grand-700 rounded focus:border-grand-gold focus:ring-grand-gold text-grand-bone" />
                    </div>

                    <div class="pt-4 border-t border-grand-700 flex justify-end">
                        <button type="submit" :disabled="form.processing" class="bg-grand-gold text-grand-900 font-bold px-6 py-3 rounded hover:bg-white transition-colors flex items-center gap-2">
                             <span v-if="form.processing">Saving...</span>
                             <span v-else>Save & Next Card -&gt;</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
