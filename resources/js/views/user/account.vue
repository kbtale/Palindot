<template>
    <div class="max-w-3xl mx-auto space-y-6">
        
        <div class="flex items-center space-x-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Account Profile</h1>
                <p class="text-sm text-slate-500 mt-1">Manage your personal information.</p>
            </div>
        </div>

        <div v-if="initialLoading" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 flex flex-col items-center justify-center min-h-[300px]">
            <svg class="animate-spin h-8 w-8 text-[#62859B] mb-4" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-slate-500 font-medium">Loading profile...</p>
        </div>

        <div v-else class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            
            <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-6 flex items-start">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ successMessage }}</span>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                
                <div v-if="errors.length" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm mb-6">
                    <ul class="list-disc list-inside">
                        <li v-for="err in errors" :key="err">{{ err }}</li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-semibold text-slate-800 ml-1">Full Name <span class="text-red-500">*</span></label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        maxlength="100"
                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all"
                        :disabled="saving"
                    />
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-slate-800 ml-1">Email Address <span class="text-red-500">*</span></label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        maxlength="255"
                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all"
                        :disabled="saving"
                    />
                </div>

                <div class="pt-4 flex items-center justify-end border-t border-slate-100">
                    <button
                        type="submit"
                        :disabled="saving || !isFormModified"
                        class="px-6 py-2.5 bg-[#62859B] text-white font-semibold rounded-xl shadow-sm hover:bg-[#527286] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[140px]"
                    >
                        <span v-if="!saving">Save Changes</span>
                        <svg v-else class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import axios from 'axios';

const initialLoading = ref(true);
const saving = ref(false);
const errors = ref([]);
const successMessage = ref('');
const userId = ref(null);

const originalData = reactive({
    name: '',
    email: ''
});

const form = reactive({
    name: '',
    email: ''
});

const isFormModified = computed(() => {
    return form.name !== originalData.name || form.email !== originalData.email;
});

onMounted(async () => {
    try {
        const response = await axios.get('/api/v1/user');
        const userData = response.data;
        
        userId.value = userData.id;
        
        originalData.name = userData.name || '';
        originalData.email = userData.email || '';
        form.name = originalData.name;
        form.email = originalData.email;
    } catch (error) {
        console.error("Failed to fetch user data:", error);
        errors.value = ['Failed to load profile parameters.'];
    } finally {
        initialLoading.value = false;
    }
});

const submitForm = async () => {
    saving.value = true;
    errors.value = [];
    successMessage.value = '';

    try {
        await axios.put(`/api/v1/user/${userId.value}`, form);
        
        successMessage.value = 'Profile updated successfully.';
        originalData.name = form.name;
        originalData.email = form.email;
        
        // Update local storage so headers update on reload
        const cachedUser = JSON.parse(localStorage.getItem('user') || '{}');
        cachedUser.name = form.name;
        cachedUser.email = form.email;
        localStorage.setItem('user', JSON.stringify(cachedUser));
        
    } catch (error) {
        console.error("Failed to update user:", error);
        if (error.response?.data?.errors) {
            errors.value = Object.values(error.response.data.errors).flat();
        } else {
            errors.value = [error.response?.data?.message || 'Failed to update profile.'];
        }
    } finally {
        saving.value = false;
        setTimeout(() => { successMessage.value = '' }, 5000);
    }
};
</script>