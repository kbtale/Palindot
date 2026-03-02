<template>
    <div class="min-h-screen bg-[#F2F4F6] font-sans text-gray-900 selection:bg-[#62859B] selection:text-white">
        <!-- Navigation -->
        <nav :class="['fixed w-full z-50 transition-all duration-300', isScrolled ? 'glass-nav border-b border-white/40 py-0' : 'bg-transparent border-transparent py-2']">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <router-link to="/" class="flex items-center gap-2 group">
                    <div :class="['w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110', isScrolled ? 'bg-[#1A1A1A] text-white' : 'bg-white text-[#1A1A1A] shadow-sm']">
                        <LinkIcon class="w-4 h-4" stroke-width="2.5" />
                    </div>
                    <span :class="['text-xl font-semibold tracking-tight transition-colors duration-300', isScrolled ? 'text-gray-900 group-hover:text-[#62859B]' : 'text-white']">Palindot</span>
                </router-link>
                <div :class="['hidden md:flex items-center gap-8 text-sm font-medium transition-colors duration-300', isScrolled ? 'text-gray-600' : 'text-white/90']">
                    <a href="#features" :class="['transition-colors', isScrolled ? 'hover:text-[#62859B]' : 'hover:text-white']">Features</a>
                    <a href="#dashboard" :class="['transition-colors', isScrolled ? 'hover:text-[#62859B]' : 'hover:text-white']">Dashboard</a>
                    <a href="#api" :class="['transition-colors', isScrolled ? 'hover:text-[#62859B]' : 'hover:text-white']">API Docs</a>
                    <router-link to="/auth/login" :class="['px-6 py-2.5 rounded-full transition-all transform hover:scale-105', isScrolled ? 'bg-[#1A1A1A] text-white hover:bg-black' : 'bg-white text-[#1A1A1A] hover:bg-gray-100 shadow-lg']">
                        Get Started
                    </router-link>
                </div>
            </div>
        </nav>

        <!-- Main Content (Hero and sections) -->
        <router-view></router-view>

        <!-- Footer -->
        <footer class="bg-white py-12 border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-[#1A1A1A] flex items-center justify-center text-white">
                        <LinkIcon class="w-3 h-3" stroke-width="3" />
                    </div>
                    <span class="text-lg font-semibold tracking-tight text-gray-900">Palindot</span>
                </div>
                <div class="flex gap-8 text-sm text-gray-500 font-light">
                    <a href="#" class="hover:text-gray-900 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-900 transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-gray-900 transition-colors">Contact</a>
                </div>
                <div class="text-sm text-gray-400 font-light">
                    © 2026 Palindot. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { LinkIcon } from '@heroicons/vue/24/outline';

const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>
