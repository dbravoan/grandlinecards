<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    session_id: String,
});
</script>

<template>
    <AppLayout title="Payment Successful">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-8 text-center">
                    <div class="mb-6">
                        <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-white mb-4">Payment Successful!</h2>
                    <p class="text-gray-300 mb-8">Thank you for your purchase. We have received your payment.</p>
                    
                    <div class="space-x-4">
                        <Link :href="route('profile.shipments.index')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            View My Shipments
                        </Link>
                        <Link :href="route('shop.index')" class="text-gray-400 hover:text-white underline">
                            Continue Shopping
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
