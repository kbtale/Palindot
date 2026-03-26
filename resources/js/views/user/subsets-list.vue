<template>
    <div class="space-y-6 max-w-6xl mx-auto">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Your Subsets</h1>
                <p class="text-sm text-slate-500 mt-1">Manage your folders and categories to organize your URLs.</p>
            </div>
            
            <router-link to="/user/subsets/new" class="inline-flex items-center justify-center px-4 py-2 bg-[#62859B] text-white font-semibold rounded-xl hover:bg-[#527286] transition-colors shadow-sm text-sm">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Subset
            </router-link>
        </div>

        <!-- Initial Loading State -->
        <div v-if="loading && items.length === 0" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 flex flex-col items-center justify-center min-h-[300px]">
            <svg class="animate-spin h-8 w-8 text-[#62859B] mb-4" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-slate-500 font-medium">Loading your subsets...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="!loading && items.length === 0" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-2">No subsets found</h3>
            <p class="text-slate-500 max-w-sm mb-6">You haven't created any subsets yet. Subsets are like folders that help you organize your shortened URLs.</p>
            <router-link to="/user/subsets/new" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-700 font-semibold rounded-xl border border-slate-200 hover:bg-slate-200 transition-colors shadow-sm text-sm">
                Create your first subset
            </router-link>
        </div>

        <!-- Data Table -->
        <div v-else class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
            
            <!-- Loading overlay during pagination -->
            <div v-if="loading" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 flex items-center justify-center">
                <svg class="animate-spin h-6 w-6 text-[#62859B]" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        <tr v-for="item in items" :key="item.id" class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-slate-800">{{ item.subset_name }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-md truncate">
                                {{ item.subset_descr || '--' }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-3 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <router-link :to="`/user/subsets/${item.id}/edit`" class="text-[#62859B] hover:text-[#456275] p-1 rounded transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </router-link>
                                    <button @click="deleteSubset(item.id)" class="text-red-500 hover:text-red-700 p-1 rounded transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div v-if="pagination && pagination.total > pagination.per_page" class="border-t border-slate-200 px-6 py-4 flex items-center justify-between bg-slate-50/30">
                <p class="text-xs text-slate-500">
                    Showing <span class="font-medium text-slate-700">{{ (pagination.current_page - 1) * pagination.per_page + 1 }}</span> to <span class="font-medium text-slate-700">{{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}</span> of <span class="font-medium text-slate-700">{{ pagination.total }}</span> subsets
                </p>
                
                <div class="space-x-2">
                    <button 
                        @click="fetchSubsets(pagination.current_page - 1)" 
                        :disabled="pagination.current_page === 1"
                        class="px-3 py-1 text-sm border border-slate-200 rounded-lg bg-white text-slate-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-50 transition-colors">
                        Previous
                    </button>
                    <button 
                        @click="fetchSubsets(pagination.current_page + 1)" 
                        :disabled="pagination.current_page === pagination.last_page"
                        class="px-3 py-1 text-sm border border-slate-200 rounded-lg bg-white text-slate-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-50 transition-colors">
                        Next
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const items = ref([]);
const pagination = ref(null);
const loading = ref(true);

const fetchSubsets = async (page = 1) => {
    loading.value = true;
    try {
        const response = await axios.get('/api/v1/subsets', {
            params: {
                page: page,
                perPage: 10
            }
        });
        
        items.value = response.data.items || [];
        pagination.value = response.data.pagination || null;
    } catch (error) {
        console.error("Failed to fetch subsets:", error);
        alert('Failed to load subsets. Please try again.');
    } finally {
        loading.value = false;
    }
};

const deleteSubset = async (id) => {
    if (!confirm('Are you sure you want to delete this subset? All underlying urls will be unassigned or deleted. Proceed?')) {
        return;
    }

    try {
        await axios.delete(`/api/v1/subsets/${id}`);
        // Refresh the current page
        fetchSubsets(pagination.value?.current_page || 1);
    } catch (error) {
        console.error("Failed to delete subset:", error);
        alert(error.response?.data?.message || 'Failed to delete subset.');
    }
};

onMounted(() => {
    fetchSubsets();
});
</script>