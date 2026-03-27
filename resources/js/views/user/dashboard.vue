<template>
    <div class="max-w-6xl mx-auto space-y-6">
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
                <p class="text-sm text-slate-500 mt-1">Overview of your links and subsets.</p>
            </div>
            <router-link to="/user/urls/new" class="inline-flex items-center justify-center px-4 py-2 bg-[#62859B] text-white font-semibold rounded-xl hover:bg-[#527286] transition-colors shadow-sm text-sm">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Shorten Link
            </router-link>
        </div>

        <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 h-40 flex items-center justify-center">
                <svg class="animate-spin h-6 w-6 text-slate-300" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 h-40 flex items-center justify-center">
                <svg class="animate-spin h-6 w-6 text-slate-300" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total URLs</p>
                        <h2 class="text-4xl font-bold text-slate-800 mt-2">{{ totalUrls }}</h2>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-500 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                </div>
                <div class="mt-6">
                    <router-link to="/user/urls" class="text-sm font-semibold text-[#62859B] hover:underline flex items-center">
                        View all URLs
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </router-link>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Subsets</p>
                        <h2 class="text-4xl font-bold text-slate-800 mt-2">{{ totalSubsets }}</h2>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-500 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-6">
                    <router-link to="/user/subsets" class="text-sm font-semibold text-[#62859B] hover:underline flex items-center">
                        Manage subsets
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </router-link>
                </div>
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 flex flex-col sm:flex-row items-center justify-between mt-6 border-l-4 border-l-[#62859B]">
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Quick Start</h3>
                <p class="text-slate-500 text-sm mt-1">If you haven't yet, group your links by creating a subset first.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <router-link to="/user/subsets/new" class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors inline-block">
                    Create Subset
                </router-link>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const loading = ref(true);
const totalUrls = ref(0);
const totalSubsets = ref(0);

onMounted(async () => {
    try {
        const [urlsRes, subsetsRes] = await Promise.all([
            axios.get('/api/v1/urls', { params: { perPage: 1 } }),
            axios.get('/api/v1/subsets', { params: { perPage: 1 } })
        ]);

        totalUrls.value = urlsRes.data.pagination?.total || 0;
        totalSubsets.value = subsetsRes.data.pagination?.total || 0;
    } catch (error) {
        console.error("Failed to fetch dashboard data:", error);
    } finally {
        loading.value = false;
    }
});
</script>