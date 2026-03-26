<template>
    <div class="flex h-screen bg-slate-50 font-sans overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col transition-all duration-300">
            <!-- Logo area -->
            <div class="h-16 flex items-center px-6 border-b border-slate-200">
                <span class="text-xl font-bold tracking-tight text-[#1A1A1A]">Palindot.</span>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <router-link to="/user/dashboard" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors text-slate-600 hover:bg-slate-50 hover:text-[#62859B]" active-class="bg-[#62859B]/10 text-[#62859B] font-semibold">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </router-link>

                <router-link to="/user/subsets" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors text-slate-600 hover:bg-slate-50 hover:text-[#62859B]" active-class="bg-[#62859B]/10 text-[#62859B] font-semibold">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    Subsets
                </router-link>

                <router-link to="/user/urls" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors text-slate-600 hover:bg-slate-50 hover:text-[#62859B]" active-class="bg-[#62859B]/10 text-[#62859B] font-semibold">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    URLs
                </router-link>

                <div class="pt-4 mt-4 border-t border-slate-200">
                    <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Settings</p>
                    
                    <router-link to="/user/account" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors text-slate-600 hover:bg-slate-50 hover:text-[#62859B]" active-class="bg-[#62859B]/10 text-[#62859B] font-semibold">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Account Profile
                    </router-link>
                </div>
            </nav>

            <!-- User footer & Logout -->
            <div class="p-4 border-t border-slate-200">
                <button @click="handleLogout" :disabled="loggingOut" class="flex items-center w-full px-4 py-3 text-sm font-medium text-red-600 rounded-xl hover:bg-red-50 transition-colors disabled:opacity-50">
                    <svg v-if="!loggingOut" class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <svg v-else class="animate-spin w-5 h-5 mr-3" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ loggingOut ? 'Logging out...' : 'Sign Out' }}</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col overflow-hidden relative">
            
            <!-- Top Header -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 z-10 sticky top-0">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 capitalize">{{ currentRouteName }}</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-slate-600 bg-slate-100 px-3 py-1 rounded-full">{{ userName }}</span>
                </div>
            </header>

            <!-- Content Container -->
            <div class="flex-1 overflow-y-auto p-8">
                <router-view></router-view>
            </div>
            
        </main>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const loggingOut = ref(false);

const userName = ref('User');

onMounted(() => {
    // Attempt to load user name from local storage
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
        try {
            const parsed = JSON.parse(storedUser);
            if (parsed && parsed.name) {
                userName.value = parsed.name;
            }
        } catch (e) {
            console.error('Could not parse user from local storage');
        }
    }
});

// Format route name for header
const currentRouteName = computed(() => {
    const p = route.path.split('/');
    let name = p[p.length - 1] || 'Dashboard';
    return name.replace(/-/g, ' ');
});

const handleLogout = async () => {
    loggingOut.value = true;
    try {
        await axios.get('/api/v1/logout');
    } catch (e) {
        console.error('Logout request failed, proceeding to clear local state anyway', e);
    } finally {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common['Authorization'];
        
        router.push('/auth/login');
        loggingOut.value = false;
    }
};
</script>