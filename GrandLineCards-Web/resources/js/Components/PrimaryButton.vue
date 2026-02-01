<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-grand-gold border border-transparent rounded-md font-semibold text-xs text-grand-900 uppercase tracking-widest hover:bg-white focus:bg-white active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-grand-gold focus:ring-offset-2 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
