<template>
    <div class="max-w-3xl mx-auto space-y-6">
        
        <div class="flex items-center space-x-4">
            <router-link to="/user/urls" class="text-slate-400 hover:text-slate-600 transition-colors bg-white border border-slate-200 p-2 rounded-lg shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </router-link>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit URL</h1>
                <p class="text-sm text-slate-500 mt-1">Update the original destination or change subset assignment.</p>
            </div>
        </div>

        <div v-if="initialLoading" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 flex flex-col items-center justify-center min-h-[300px]">
            <svg class="animate-spin h-8 w-8 text-[#62859B] mb-4" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-slate-500 font-medium">Loading details...</p>
        </div>

        <div v-else class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <form @submit.prevent="submitForm" class="space-y-6">
                
                <div v-if="errors.length" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm mb-6">
                    <ul class="list-disc list-inside">
                        <li v-for="err in errors" :key="err">{{ err }}</li>
                    </ul>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl mb-6">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Shortened Link</p>
                    <a :href="fullShortUrl" target="_blank" class="text-[#62859B] font-medium hover:underline break-all">
                        {{ fullShortUrl }}
                    </a>
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
                        :disabled="submitting"
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
                            :disabled="submitting || fetchingSubsets"
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
                    <router-link to="/user/urls" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors" :class="{'pointer-events-none opacity-50': submitting}">
                        Cancel
                    </router-link>
                    
                    <button
                        type="submit"
                        :disabled="submitting || !form.base_url.trim()"
                        class="px-6 py-2.5 bg-[#62859B] text-white font-semibold rounded-xl shadow-sm hover:bg-[#527286] transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[120px]"
                    >
                        <span v-if="!submitting">Save Changes</span>
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
import { ref, reactive, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const urlId = route.params.id;

const initialLoading = ref(true);
const submitting = ref(false);
const fetchingSubsets = ref(true);
const errors = ref([]);
const subsets = ref([]);

const originalToUrl = ref('');

const form = reactive({
    base_url: '',
    subset_id: null
});

const fullShortUrl = computed(() => {
    return `${window.location.origin}/${originalToUrl.value}`;
});

onMounted(async () => {
    try {
        const [subsetsResponse, urlResponse] = await Promise.all([
            axios.get('/api/v1/subsets', { params: { perPage: 100 } }),
            axios.get(`/api/v1/urls/${urlId}`)
        ]);

        subsets.value = subsetsResponse.data.items || [];
        
        const urlData = urlResponse.data;
        form.base_url = urlData.base_url;
        form.subset_id = urlData.subset_id;
        originalToUrl.value = urlData.to_url;

    } catch (error) {
        console.error("Failed to load data:", error);
        alert('Data not found or you do not have permission.');
        router.push('/user/urls');
    } finally {
        initialLoading.value = false;
        fetchingSubsets.value = false;
    }
});

const submitForm = async () => {
    submitting.value = true;
    errors.value = [];

    try {
        await axios.put(`/api/v1/urls/${urlId}`, form);
        router.push('/user/urls');
    } catch (error) {
        console.error("Failed to update url:", error);
        if (error.response?.data?.errors) {
            errors.value = Object.values(error.response.data.errors).flat();
        } else {
            errors.value = [error.response?.data?.message || 'Failed to update URL.'];
        }
    } finally {
        submitting.value = false;
    }
};
</script>