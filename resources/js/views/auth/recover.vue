<template>
    <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>

        <div class="relative z-10 block">
            <div class="mb-6">
                <router-link to="/auth/login" class="text-slate-400 hover:text-slate-600 transition-colors inline-block bg-white border border-slate-200 p-2 rounded-lg shadow-sm mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </router-link>
            </div>

            <h1 class="text-3xl font-bold text-[#1A1A1A] mb-2 tracking-tight">Reset Password</h1>
            <p class="text-[#62859B] mb-8">Enter your email address to receive a secure password reset link.</p>

            <form @submit.prevent="handleRecover" class="space-y-6">
                
                <div v-if="success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    If this email format is valid, a link has been sent. Please check your inbox.
                </div>

                <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm">
                    {{ error }}
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-sm font-semibold text-[#1A1A1A] ml-1">Email Address</label>
                    <div class="relative group">
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            required
                            maxlength="255"
                            placeholder="name@example.com"
                            class="w-full px-4 py-3 bg-[#F2F4F6]/50 border border-[#62859B]/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all bg-white"
                            :disabled="loading || success"
                        />
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="loading || success || !email"
                    class="w-full py-4 bg-[#62859B] text-white font-bold rounded-xl shadow-lg hover:shadow-[#62859B]/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden flex items-center justify-center"
                >
                    <span v-if="!loading">Send Reset Link</span>
                    <span v-else class="flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    </span>
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const email = ref('');
const loading = ref(false);
const error = ref(null);
const success = ref(false);

const handleRecover = async () => {
    loading.value = true;
    error.value = null;
    success.value = false;

    try {
        await axios.post('/api/v1/forgot-password', { email: email.value });
        success.value = true;
    } catch (e) {
        console.error('Password reset request failed:', e);
        if (e.response?.data?.errors) {
            error.value = Object.values(e.response.data.errors).flat().join(' ');
        } else {
            error.value = e.response?.data?.message || 'An error occurred while attempting to send the reset link.';
        }
    } finally {
        loading.value = false;
    }
};
</script>