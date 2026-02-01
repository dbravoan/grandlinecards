<script setup>
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('admin.login.store'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-[#1A1A1A] flex flex-col justify-center items-center font-mono text-white relative overflow-hidden">
        <Head title="Admin Login" />

        <!-- Background Geometry -->
        <div class="absolute inset-0 pointer-events-none opacity-20">
             <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0,100 L100,0 L100,100 Z" fill="#00FF99" />
             </svg>
        </div>

        <!-- Terminal Card -->
        <div class="z-10 w-full max-w-md p-8 bg-[#1A1A1A] border border-[#00FF99] shadow-[0_0_15px_rgba(0,255,153,0.1)] relative">
            
            <!-- Terminal Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold tracking-tighter">
                    <span class="text-white">&gt;_</span>
                    <span class="text-[#00FF99]">db</span>
                    <span class="text-white">admin</span>
                    <span class="animate-pulse text-[#00FF99]">_</span>
                </h1>
                <p class="text-xs text-[#00FF99] mt-2 tracking-widest uppercase">System Access Restricted</p>
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-[#00FF99]">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Email -->
                <div class="group relative">
                    <label for="email" class="block text-xs uppercase tracking-widest text-[#00FF99] mb-1">Identifier</label>
                    <input 
                        id="email" 
                        type="email" 
                        v-model="form.email"
                        required 
                        autofocus
                        class="w-full bg-transparent border-b border-gray-600 text-white py-2 px-1 focus:outline-none focus:border-[#00FF99] transition-colors font-mono placeholder-gray-700"
                        placeholder="admin@grandline.com"
                    />
                    <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                </div>

                <!-- Password -->
                <div class="group relative">
                    <label for="password" class="block text-xs uppercase tracking-widest text-[#00FF99] mb-1">Passcode</label>
                    <input 
                        id="password" 
                        type="password" 
                        v-model="form.password"
                        required 
                        class="w-full bg-transparent border-b border-gray-600 text-white py-2 px-1 focus:outline-none focus:border-[#00FF99] transition-colors font-mono"
                    />
                    <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full py-3 border border-[#00FF99] text-[#00FF99] font-bold uppercase tracking-widest hover:bg-[#00FF99] hover:text-[#1A1A1A] hover:shadow-[0_0_20px_rgba(0,255,153,0.5)] transition-all duration-300 flex justify-center items-center"
                    >
                        <span v-if="!form.processing">Authenticate</span>
                        <span v-else class="animate-pulse">Processing...</span>
                    </button>
                    
                    <div class="absolute -bottom-2 -right-2 w-4 h-4 border-r border-b border-[#00FF99]"></div>
                    <div class="absolute -top-2 -left-2 w-4 h-4 border-l border-t border-[#00FF99]"></div>
                </div>
            </form>
        </div>
        
        <!-- Footer -->
        <div class="absolute bottom-4 text-[10px] text-gray-500 font-mono">
            SESSION_ID: {{ Math.random().toString(36).substr(2, 9).toUpperCase() }}
        </div>
    </div>
</template>

<style scoped>
/* Ensure font-mono is applied properly, might need to import Google Sans Code in Layout or CSS. */
/* For now, utilizing standard monospace stack or Tailwind's font-mono */
</style>
