<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const seoPrompt = ref('');
const seoResponse = ref('');
const isGenerating = ref(false);
const userCoin = ref(0);
const error = ref(null);

// Fetch user coins on mount
const fetchUserCredits = async () => {
  try {
    const response = await axios.get('/user/coin');
    userCoin.value = response.data.coin;
  } catch (err) {
    error.value = 'Unable to retrieve user credits at the moment.';
  }
};

// Submit the SEO prompt
const submitSeoPrompt = async () => {
  if (!seoPrompt.value.trim()) {
    alert('Please enter a prompt for SEO optimization.');
    return;
  }

  isGenerating.value = true;

  try {
    const response = await axios.post('/api/generate-seo-content', {
      prompt: seoPrompt.value
    });

    seoResponse.value = response.data.content || 'No content generated.';
    userCoin.value = response.data.remaining_coins; // Update remaining coins
  } catch (error) {
    console.error('Error generating SEO content:', error);
    seoResponse.value = 'Error generating content.';
  } finally {
    isGenerating.value = false;
  }
};

// Fetch credits on mount
onMounted(fetchUserCredits);
</script>

<template>
  <AppLayout>
    <Head title="SEO Optimization" />

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        SEO Optimization Tool
      </h2>
    </template>

    <main class="py-12 bg-gray-100">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-8">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-[#00A3E0]">Generate SEO Optimized Content</h3>
            <p class="text-gray-500">Improve your website's visibility on search engines with tailored SEO content.</p>
          </div>

          <!-- Display User Credits -->
          <div class="text-center mb-6">
            <span class="text-2xl font-bold text-[#00A3E0]">Credits: {{ userCoin }}</span>
          </div>

          <!-- SEO Prompt Input -->
          <textarea
            v-model="seoPrompt"
            placeholder="Enter your SEO prompt here..."
            :disabled="isGenerating"
            class="w-full h-24 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-gray-700 text-lg transition-all duration-300 ease-in-out"
          ></textarea>

          <!-- Generate Button -->
          <div class="flex justify-center mt-4">
            <button
              @click="submitSeoPrompt"
              :disabled="isGenerating"
              class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-lg py-3 px-8 rounded-full shadow-lg transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isGenerating ? 'Generating...' : 'Generate SEO Content' }}
            </button>
          </div>

          <!-- SEO Content Response -->
          <div v-if="seoResponse" class="bg-gray-100 p-6 mt-8 rounded-lg">
            <h2 class="text-xl font-bold text-[#00A3E0] mb-4">Generated SEO Content</h2>
            <p class="whitespace-pre-wrap text-gray-800">{{ seoResponse }}</p>
          </div>

          <!-- Error Handling -->
          <div v-if="error" class="text-center text-red-500 mt-4">
            <p>{{ error }}</p>
          </div>
        </div>
      </div>
    </main>
  </AppLayout>
</template>

<style scoped>
/* Styling */
textarea {
  transition: all 0.3s ease;
}
button {
  transition: all 0.3s ease;
}
</style>
