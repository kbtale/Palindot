<template>
    <div class="max-w-3xl mx-auto space-y-6">
        
        <!-- Header Section -->
        <div class="flex items-center space-x-4">
            <router-link to="/user/subsets" class="text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 p-2 rounded-lg shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </router-link>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Create New Subset</h1>
                <p class="text-sm text-slate-500 mt-1">Organize your URLs effectively by creating a new category folder.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <form @submit.prevent="submitForm" class="space-y-6">
                
                <!-- Errors -->
                <div v-if="errors.length" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm mb-6">
                    <ul class="list-disc list-inside">
                        <li v-for="err in errors" :key="err">{{ err }}</li>
                    </ul>
                </div>

                <!-- Subset Name -->
                <div class="space-y-2">
                    <label for="subset_name" class="block text-sm font-semibold text-slate-800 ml-1">Subset Name <span class="text-red-500">*</span></label>
                    <input
                        id="subset_name"
                        v-model="form.subset_name"
                        type="text"
                        required
                        maxlength="100"
                        placeholder="e.g. Work Links, Social Media, Marketing Campaign"
                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all"
                        :disabled="loading"
                    />
                </div>

                <!-- Subset Description -->
                <div class="space-y-2">
                    <label for="subset_descr" class="block text-sm font-semibold text-slate-800 ml-1 flex justify-between">
                        <span>Description</span>
                        <span class="text-slate-400 font-normal text-xs">Optional</span>
                    </label>
                    <textarea
                        id="subset_descr"
                        v-model="form.subset_descr"
                        rows="4"
                        placeholder="Describe this subset's purpose."
                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all resize-y"
                        :disabled="loading"
                    ></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                    <router-link to="/user/subsets" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors" :class="{'pointer-events-none opacity-50': loading}">
                        Cancel
                    </router-link>
                    
                    <button
                        type="submit"
                        :disabled="loading || !form.subset_name.trim()"
                        class="px-6 py-2.5 bg-[#62859B] text-white font-semibold rounded-xl shadow-sm hover:bg-[#527286] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[120px]"
                    >
                        <span v-if="!loading">Create Subset</span>
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
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const loading = ref(false);
const errors = ref([]);

const form = reactive({
    subset_name: '',
    subset_descr: ''
});

const submitForm = async () => {
    loading.value = true;
    errors.value = [];

    try {
        await axios.post('/api/v1/subsets', form);
        router.push('/user/subsets');
    } catch (error) {
        console.error("Failed to create subset:", error);
        if (error.response?.data?.errors) {
            errors.value = Object.values(error.response.data.errors).flat();
        } else {
            errors.value = [error.response?.data?.message || 'Failed to create subset. Please try again.'];
        }
    } finally {
        loading.value = false;
    }
};
</script>