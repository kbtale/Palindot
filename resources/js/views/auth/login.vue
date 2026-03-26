<template>
    <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl relative overflow-hidden group">
        <!-- Shine effect -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>

        <div class="relative z-10">
            <h1 class="text-3xl font-bold text-[#1A1A1A] mb-2 tracking-tight">Welcome Back</h1>
            <p class="text-[#62859B] mb-8">Enter your credentials to access your palindromic links.</p>

            <form @submit.prevent="handleLogin" class="space-y-6">
                <!-- Error Message -->
                <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm animate-shake">
                    {{ error }}
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-semibold text-[#1A1A1A] ml-1">Email Address</label>
                    <div class="relative group">
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            maxlength="255"
                            placeholder="name@example.com"
                            class="w-full px-4 py-3 bg-[#F2F4F6]/50 border border-[#62859B]/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all bg-white"
                        />
                    </div>
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label for="password" class="text-sm font-semibold text-[#1A1A1A]">Password</label>
                        <router-link to="/auth/recover" class="text-xs font-semibold text-[#62859B] hover:text-[#1A1A1A] transition-colors">
                            Forgot password?
                        </router-link>
                    </div>
                    <div class="relative">
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            minlength="8"
                            maxlength="32"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-[#F2F4F6]/50 border border-[#62859B]/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all bg-white"
                        />
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center space-x-2 px-1">
                    <input
                        id="remember"
                        v-model="form.remember"
                        type="checkbox"
                        class="w-4 h-4 text-[#62859B] border-[#62859B]/20 rounded focus:ring-[#62859B]/20"
                    />
                    <label for="remember" class="text-xs text-[#62859B]">Remember me</label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-4 bg-[#62859B] text-white font-bold rounded-xl shadow-lg hover:shadow-[#62859B]/30 hover:-translate-y-0.5 active:translate-y-0 transition-all disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden group"
                >
                    <span v-if="!loading" class="flex items-center justify-center">
                        Sign In
                        <ArrowRightIcon class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" />
                    </span>
                    <span v-else class="flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Authenticating...
                    </span>
                </button>

                <p class="text-center text-sm text-[#62859B]">
                    Don't have an account?
                    <router-link to="/auth/register" class="font-bold text-[#1A1A1A] hover:underline underline-offset-4">
                        Create one free
                    </router-link>
                </p>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { ArrowRightIcon } from '@heroicons/vue/24/outline';

const router = useRouter();
const loading = ref(false);
const error = ref(null);

const form = reactive({
    email: '',
    password: '',
    remember: false
});

const handleLogin = async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await axios.post('/api/v1/login', form);
        
        // Save token
        localStorage.setItem('auth_token', response.data.access_token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        
        // Set axios default header for future requests
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;

        router.push({ name: 'UserHome' });
    } catch (e) {
        console.error('Login failed:', e);
        error.value = e.response?.data?.message || 'Login failed. Please check your credentials.';
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
.animate-shake {
    animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
}

@keyframes shake {
    10%, 90% { transform: translate3d(-1px, 0, 0); }
    20%, 80% { transform: translate3d(2px, 0, 0); }
    30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
    40%, 60% { transform: translate3d(4px, 0, 0); }
}
</style>