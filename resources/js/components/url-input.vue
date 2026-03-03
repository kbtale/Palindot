<template>
  <div class="max-w-3xl mx-auto relative group w-full">
    <div class="absolute -inset-1 bg-white/20 rounded-full blur-md group-hover:bg-white/30 transition duration-500"></div>
    <div class="relative flex flex-col items-center bg-white rounded-[2rem] p-2.5 shadow-2xl">
      <form @submit.prevent="handleGenerate" class="w-full flex items-center">
        <div class="pl-6 text-gray-400">
          <LinkIcon class="w-6 h-6" />
        </div>
          <input 
            type="url" 
            v-model="urlInput"
            placeholder="Paste your long URL here..." 
            class="flex-1 bg-transparent px-4 py-4 text-lg text-gray-800 outline-none placeholder:text-gray-400 font-light"
            required
            maxlength="2048"
          />
        <button 
          type="submit"
          :disabled="isGenerating"
          class="bg-[#1A1A1A] text-white px-8 py-4 rounded-full font-medium flex items-center gap-2 transition-all duration-300 hover:bg-black hover:scale-105 disabled:opacity-75 disabled:scale-95"
        >
          {{ isGenerating ? 'Generating...' : 'Generate' }}
          <ArrowRightIcon v-if="!isGenerating" class="w-5 h-5" />
        </button>
      </form>
      
      <!-- Result Area -->
      <div v-if="shortenedUrl" class="w-full mt-4 px-6 pb-4 border-t border-gray-100 pt-4 animate-fade-in">
        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-2xl">
          <span class="text-gray-900 font-medium truncate mr-4">{{ shortenedUrl }}</span>
          <button @click="copyToClipboard" class="text-[#62859B] text-sm font-semibold hover:text-[#456070] transition-colors whitespace-nowrap">
            {{ copied ? 'Copied!' : 'Copy Link' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { LinkIcon, ArrowRightIcon } from '@heroicons/vue/24/outline';
import axios from 'axios';

const urlInput = ref('');
const isGenerating = ref(false);
const shortenedUrl = ref('');
const copied = ref(false);

const handleGenerate = async () => {
  if (!urlInput.value) return;
  
  isGenerating.value = true;
  shortenedUrl.value = '';
  
  try {
    const response = await axios.post('/api/v1/urls/create', {
      base_url: urlInput.value
    });
    
    shortenedUrl.value = response.data.to_url;
  } catch (error) {
    console.error('Error generating URL:', error);
  } finally {
    isGenerating.value = false;
  }
};

const copyToClipboard = () => {
  navigator.clipboard.writeText(shortenedUrl.value);
  copied.value = true;
  setTimeout(() => {
    copied.value = false;
  }, 2000);
};
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.5s ease-out forwards;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>