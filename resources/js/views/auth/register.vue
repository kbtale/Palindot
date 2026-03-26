<template>
    <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        <!-- Shine effect -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>

        <div class="relative z-10">
            <h1 class="text-3xl font-bold text-[#1A1A1A] mb-2 tracking-tight">Join Palindot</h1>
            <p class="text-[#62859B] mb-8">Start organizing your links with perfect symmetry today.</p>

            <form @submit.prevent="handleRegister" class="space-y-5">
                <!-- Errors -->
                <div v-if="errors.length" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm animate-shake">
                    <ul class="list-disc list-inside">
                        <li v-for="err in errors" :key="err">{{ err }}</li>
                    </ul>
                </div>

                <!-- Full Name -->
                <div class="space-y-2">
                    <label for="name" class="text-sm font-semibold text-[#1A1A1A] ml-1">Full Name</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        maxlength="100"
                        placeholder="John Doe"
                        class="w-full px-4 py-3 bg-white border border-[#62859B]/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all"
                    />
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-semibold text-[#1A1A1A] ml-1">Email Address</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        maxlength="255"
                        placeholder="name@example.com"
                        class="w-full px-4 py-3 bg-white border border-[#62859B]/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all"
                    />
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-semibold text-[#1A1A1A] ml-1">Password</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            minlength="8"
                            maxlength="32"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-white border border-[#62859B]/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all"
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-semibold text-[#1A1A1A] ml-1">Confirm</label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            minlength="8"
                            maxlength="32"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-white border border-[#62859B]/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all"
                        />
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start space-x-2 px-1">
                    <input
                        id="terms"
                        v-model="form.terms"
                        type="checkbox"
                        required
                        class="mt-1 w-4 h-4 text-[#62859B] border-[#62859B]/20 rounded focus:ring-[#62859B]/20"
                    />
                    <label for="terms" class="text-xs text-[#62859B] leading-relaxed">
                        I agree to the <a href="#" class="underline font-semibold text-[#1A1A1A]">Terms of Service</a> and 
                        <a href="#" class="underline font-semibold text-[#1A1A1A]">Privacy Policy</a>.
                    </label>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-4 bg-[#62859B] text-white font-bold rounded-xl shadow-lg hover:shadow-[#62859B]/30 hover:-translate-y-0.5 active:translate-y-0 transition-all disabled:opacity-50 disabled:cursor-not-allowed group"
                >
                    <span v-if="!loading" class="flex items-center justify-center">
                        Create Account
                    </span>
                    <span v-else class="flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creating account...
                    </span>
                </button>

                <p class="text-center text-sm text-[#62859B]">
                    Already have an account?
                    <router-link to="/auth/login" class="font-bold text-[#1A1A1A] hover:underline underline-offset-4">
                        Sign In instead
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

const router = useRouter();
const loading = ref(false);
const errors = ref([]);

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false
});

const handleRegister = async () => {
    if (form.password !== form.password_confirmation) {
        errors.value = ["Passwords don't match"];
        return;
    }

    loading.value = true;
    errors.value = [];

    try {
        const response = await axios.post('/api/v1/register', form);
        
        // Save token
        localStorage.setItem('auth_token', response.data.access_token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        
        // Set axios default header
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;

        router.push({ name: 'UserHome' });
    } catch (e) {
        console.error('Registration failed:', e);
        if (e.response?.data?.errors) {
            errors.value = Object.values(e.response.data.errors).flat();
        } else {
            errors.value = [e.response?.data?.message || 'Registration failed. Please try again.'];
        }
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