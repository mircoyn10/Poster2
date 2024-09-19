<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'; 
import { faInstagram, faTiktok, faTwitter, faFacebook } from '@fortawesome/free-brands-svg-icons';
import { faTrash, faSpinner, faCopy } from '@fortawesome/free-solid-svg-icons';

// Add icons to the library
library.add(faInstagram, faTiktok, faTwitter, faFacebook, faTrash, faCopy, faSpinner);

// Reactive data
const userPrompt = ref('');
const userCoin = ref(0);
const selectedSocials = ref({
  instagram: false,
  tiktok: false,
  twitter: false,
  facebook: false
});
const promptHistory = ref([]);
const isAuthenticated = ref(true);
const isLoading = ref(false);
const platformLoading = ref({
  instagram: false,
  tiktok: false,
  twitter: false,
  facebook: false
});

// Axios configuration
axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Function to load saved history
const loadSavedHistory = async () => {
  try {
    const result = await axios.get('/search-history');
    promptHistory.value = result.data.map(entry => ({
      ...entry,
      timestamp: new Date().toLocaleString(),
      responses: entry.response ? Object.entries(entry.response).map(([platform, content]) => ({
        platform,
        content,
        isGenerating: false
      })) : []
    }));
  } catch (error) {
    console.error('Error loading search history:', error);
  }
};

// Load user coins
const loadUserCoins = async () => {
  try {
    const response = await axios.get('/user/coin');
    userCoin.value = response.data.coin;
  } catch (error) {
    console.error('Error fetching user coins:', error);
  }
};

// Toggle platform selection
const toggleSelection = (platform) => {
  selectedSocials.value[platform] = !selectedSocials.value[platform];
};

// Submit the prompt
const submitPrompt = async () => {
  const selectedPlatforms = Object.keys(selectedSocials.value).filter(platform => selectedSocials.value[platform]);
  
  if (selectedPlatforms.length === 0) {
    alert('Please select at least one platform.');
    return;
  }

  isLoading.value = true;
  const newEntry = {
    prompt: userPrompt.value,
    timestamp: new Date().toLocaleString(),
    responses: selectedPlatforms.map(platform => ({
      platform,
      content: '',
      isGenerating: true
    }))
  };
  promptHistory.value.unshift(newEntry);

  try {
    for (const platform of selectedPlatforms) {
      platformLoading.value[platform] = true;
      const response = await axios.post('/api/generate-content', {
        prompt: userPrompt.value,
        platforms: { [platform]: true }
      });

      if (response.data.responses) {
        userCoin.value = response.data.remaining_coins;
        const responseIndex = newEntry.responses.findIndex(r => r.platform === platform);
        if (responseIndex !== -1) {
          newEntry.responses[responseIndex].content = response.data.responses[platform];
          newEntry.responses[responseIndex].isGenerating = false;
        }
      }
    }
    userPrompt.value = '';
  } catch (error) {
    console.error('Error submitting prompt:', error);
    if (error.response && error.response.data.error === 'Not enough coins') {
      alert('Non hai abbastanza coin per generare il contenuto.');
    }
  } finally {
    isLoading.value = false;
    Object.keys(platformLoading.value).forEach(key => platformLoading.value[key] = false);
  }
};

// Clear history
const clearHistory = async () => {
  try {
    await axios.post('/clear-history');
    promptHistory.value = [];
  } catch (error) {
    console.error('Error clearing history:', error);
  }
};

// Copy to clipboard
const copyToClipboard = (text) => {
  navigator.clipboard.writeText(text).then(() => {
    alert('Copied to clipboard!');
  }).catch(err => {
    console.error('Failed to copy: ', err);
  });
};

// On component mount
onMounted(async () => {
  loadSavedHistory();
  loadUserCoins();
});
</script>

<style scoped>
.text-blue-500 { color: #3b82f6; }
.bg-gray-100 { background-color: #f3f4f6; }
.fade-enter-active, .fade-leave-active { transition: opacity 0.5s; }
.fade-enter, .fade-leave-to { opacity: 0; }
.platform-icon { font-size: 1.5rem; margin-right: 0.5rem; }
.copy-button { opacity: 0; transition: opacity 0.3s; }
.message-bubble:hover .copy-button { opacity: 1; }
</style>

<template>
  <div class="flex flex-col items-center justify-center min-h-screen p-4">
    <!-- Chatbox principale -->
    <div class="bg-white shadow-lg rounded-xl p-8 max-w-3xl w-full mb-8">
      <!-- Logo e Titolo -->
      <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
          <h1 class="text-3xl font-bold text-gray-900">Poster AI</h1>
          <img src="http://127.0.0.1:8000/storage/img/PosterLogo2.png" alt="Poster Logo" class="h-12" />
        </div>
        <div class="flex items-center space-x-4">
          <span class="text-3xl font-bold text-indigo-600">{{ userCoin }} Coins</span>
        </div>
      </div>

      <!-- Chatbox -->
      <div class="space-y-6">
        <textarea
          v-model="userPrompt"
          placeholder="Inserisci il tuo prompt qui..."
          :disabled="isLoading"
          class="w-full h-32 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-gray-700 text-lg transition-all duration-300 ease-in-out"
        ></textarea>

        <!-- Selezione delle piattaforme e invio -->
        <div class="flex flex-col space-y-4">
          <!-- Selezione delle piattaforme -->
          <div class="flex justify-around space-x-4">
            <button
              v-for="platform in ['instagram', 'tiktok', 'twitter', 'facebook']"
              :key="platform"
              @click="toggleSelection(platform)"
              :disabled="isLoading"
              :class="{
                'bg-indigo-600': selectedSocials[platform],
                'bg-gray-300': !selectedSocials[platform],
                'opacity-50 cursor-not-allowed': isLoading
              }"
              class="text-white font-semibold text-lg w-16 h-16 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 flex items-center justify-center"
            >
              <font-awesome-icon :icon="['fab', platform]" class="text-2xl" />
            </button>
          </div>

          <!-- Bottone di invio -->
          <button
            @click="submitPrompt"
            :disabled="isLoading"
            class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-lg py-3 px-8 rounded-full shadow-lg transition-all duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ isLoading ? 'Generazione...' : 'Invia il Prompt' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Contenitore della cronologia -->
    <div class="bg-white shadow-lg rounded-xl p-8 max-w-3xl w-full h-96 overflow-y-auto mb-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Chat History</h2>
        <button
          @click="clearHistory"
          class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition-colors duration-300 flex items-center"
        >
          <font-awesome-icon :icon="['fas', 'trash']" class="mr-2" />
          Clear History
        </button>
      </div>

      <div v-if="promptHistory.length > 0" class="space-y-4">
        <div v-for="(entry, index) in promptHistory" :key="index" class="space-y-2 pb-4 border-b border-gray-200 last:border-b-0">
          <div class="text-sm text-gray-500">{{ entry.timestamp }}</div>
          <div v-if="entry.prompt">
            <div class="flex justify-end">
              <div class="bg-indigo-500 text-white p-3 rounded-2xl max-w-[80%] shadow-md">
                <p class="text-sm font-medium mb-1">Tu:</p>
                <p class="text-base leading-tight">{{ entry.prompt }}</p>
              </div>
            </div>

            <div v-if="entry.responses">
              <div v-for="response in entry.responses" :key="response.platform" class="flex justify-start mt-3">
                <div 
                  class="bg-gray-100 text-gray-800 p-3 rounded-2xl max-w-[80%] shadow-md border-l-4 relative message-bubble"
                  :class="{
                    'border-pink-500': response.platform === 'instagram',
                    'border-blue-400': response.platform === 'tiktok',
                    'border-blue-500': response.platform === 'twitter',
                    'border-blue-700': response.platform === 'facebook',
                  }"
                >
                  <div class="flex items-center mb-2">
                    <font-awesome-icon :icon="['fab', response.platform]" class="platform-icon" 
                      :class="{
                        'text-pink-500': response.platform === 'instagram',
                        'text-blue-400': response.platform === 'tiktok',
                        'text-blue-500': response.platform === 'twitter',
                        'text-blue-700': response.platform === 'facebook',
                      }"
                    />
                    <p class="text-sm font-medium">Poster AI ({{ response.platform }}):</p>
                  </div>
                  <p v-if="response.isGenerating" class="text-base leading-tight flex items-center">
                    <font-awesome-icon :icon="['fas', 'spinner']" spin class="mr-2" />
                    Generating...
                  </p>
                  <p v-else class="text-base leading-tight">{{ response.content }}</p>
                  <button 
                    v-if="!response.isGenerating"
                    @click="copyToClipboard(response.content)" 
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 copy-button"
                  >
                    <font-awesome-icon :icon="['fas', 'copy']" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="text-center text-gray-500 mt-4">
        No chat history available.
      </div>
    </div>
  </div>
</template>