<template>
    <div class="max-w-3xl mx-auto space-y-6">
        
        <div class="flex items-center space-x-4">
            <router-link to="/user/urls" class="text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 p-2 rounded-lg shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </router-link>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Shorten New URL</h1>
                <p class="text-sm text-slate-500 mt-1">Create a new short link and organize it into a subset.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <form @submit.prevent="submitForm" class="space-y-6">
                
                <div v-if="errors.length" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm mb-6">
                    <ul class="list-disc list-inside">
                        <li v-for="err in errors" :key="err">{{ err }}</li>
                    </ul>
                </div>

                <div class="space-y-2">
                    <label for="base_url" class="block text-sm font-semibold text-slate-800 ml-1">Original URL <span class="text-red-500">*</span></label>
                    <input
                        id="base_url"
                        v-model="form.base_url"
                        type="url"
                        required
                        placeholder="https://example.com/very/long/path/to/page"
                        class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all"
                        :disabled="loading"
                    />
                </div>

                <div class="space-y-2 relative">
                    <label for="subset_id" class="block text-sm font-semibold text-slate-800 ml-1 flex justify-between">
                        <span>Subset Assignment</span>
                        <span class="text-slate-400 font-normal text-xs">Optional</span>
                    </label>
                    <div class="relative">
                        <select
                            id="subset_id"
                            v-model="form.subset_id"
                            class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#62859B]/20 focus:border-[#62859B] transition-all appearance-none text-slate-700"
                            :disabled="loading || fetchingSubsets"
                        >
                            <option :value="null">-- No Subset --</option>
                            <option v-for="subset in subsets" :key="subset.id" :value="subset.id">
                                {{ subset.subset_name }}
                            </option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg v-if="fetchingSubsets" class="animate-spin h-4 w-4 text-slate-400" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                    <router-link to="/user/urls" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors" :class="{'pointer-events-none opacity-50': loading}">
                        Cancel
                    </router-link>
                    
                    <button
                        type="submit"
                        :disabled="loading || !form.base_url.trim()"
                        class="px-6 py-2.5 bg-[#62859B] text-white font-semibold rounded-xl shadow-sm hover:bg-[#527286] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[120px]"
                    >
                        <span v-if="!loading">Shorten Link</span>
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
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const loading = ref(false);
const fetchingSubsets = ref(true);
const errors = ref([]);
const subsets = ref([]);

const form = reactive({
    base_url: '',
    subset_id: null
});

onMounted(async () => {
    try {
        const response = await axios.get('/api/v1/subsets', {
            params: { perPage: 100 }
        });
        subsets.value = response.data.items || [];
    } catch (error) {
        console.error("Failed to fetch subsets for dropdown", error);
    } finally {
        fetchingSubsets.value = false;
    }
});

const submitForm = async () => {
    loading.value = true;
    errors.value = [];

    try {
        await axios.post('/api/v1/urls/create', form);
        router.push('/user/urls');
    } catch (error) {
        console.error("Failed to create url:", error);
        if (error.response?.data?.errors) {
            errors.value = Object.values(error.response.data.errors).flat();
        } else {
            errors.value = [error.response?.data?.message || 'Failed to shorten URL. Check the format and try again.'];
        }
    } finally {
        loading.value = false;
    }
};
</script>