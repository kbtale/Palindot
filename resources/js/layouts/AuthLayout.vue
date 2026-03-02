<template>
    <div class="min-h-screen bg-[#F2F4F6] flex flex-col items-center justify-center p-6 mesh-bg overflow-hidden relative">
        <!-- Logo/Home Link -->
        <router-link to="/" class="mb-8 flex items-center gap-2 group z-10">
            <div class="w-9 h-9 rounded-full bg-[#1A1A1A] flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-all duration-300">
                <LinkIcon class="w-5 h-5" stroke-width="2.5" />
            </div>
            <span class="text-2xl font-bold text-white tracking-tight">Palindot</span>
        </router-link>

        <!-- Main Content -->
        <div class="w-full max-w-md z-10">
            <router-view v-slot="{ Component }">
                <transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                    mode="out-in"
                >
                    <component :is="Component" />
                </transition>
            </router-view>
        </div>

        <!-- Decorative elements -->
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#62859B]/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-[#62859B]/5 rounded-full blur-3xl"></div>
    </div>
</template>

<script setup>
import { LinkIcon } from '@heroicons/vue/24/outline';
</script>