<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faInstagram, faTiktok, faTwitter, faFacebook } from '@fortawesome/free-brands-svg-icons';

// Add icons to the library
library.add(faInstagram, faTiktok, faTwitter, faFacebook);

// Reactive data
const userPrompt = ref('');
const userCoin = ref(0); // Reactive coin count
const selectedSocials = ref({
  instagram: false,
  tiktok: false,
  twitter: false,
  facebook: false
});
const promptHistory = ref([]);
const isAuthenticated = ref(true);

// Axios configuration
axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Function to load saved history
const loadSavedHistory = async () => {
  try {
    const result = await axios.get('/api/search-history');
    promptHistory.value = result.data;
  } catch (error) {
    console.error('Error loading search history:', error);
  }
};

// Recuperiamo i coin quando il componente viene montato
const loadUserCoins = async () => {
  try {
    const response = await axios.get('/user/coin');
    userCoin.value = response.data.coin; // Impostiamo il valore dei coin
  } catch (error) {
    console.error('Errore nel recupero dei coin:', error.response ? error.response.data : error.message);
  }
};

// Function to toggle platform selection
const toggleSelection = (platform) => {
  selectedSocials.value[platform] = !selectedSocials.value[platform];
};

// Function to submit the prompt
const submitPrompt = async () => {
  const selectedPlatforms = Object.keys(selectedSocials.value).filter(platform => selectedSocials.value[platform]);
  
  if (selectedPlatforms.length === 0) {
    alert('Please select at least one platform.');
    return;
  }

  try {
    const response = await axios.post('/api/generate-content', {
      prompt: userPrompt.value,
      platforms: selectedSocials.value
    });

    if (response.data.responses) {
      // Update the total coins in real time
      userCoin.value = response.data.remaining_coins;

      // Update the chat history
      promptHistory.value.unshift({
        prompt: userPrompt.value,
        response: response.data.responses
      });

      // Clear the prompt field
      userPrompt.value = '';
    } else {
      console.error('Invalid response structure from backend', response.data);
    }
  } catch (error) {
    console.error('Error submitting prompt:', error);
    if (error.response && error.response.data.error === 'Not enough coins') {
      alert('Non hai abbastanza coin per generare il contenuto.');
    }
  }
};

// On component mount
onMounted(async () => {
  loadSavedHistory();
  loadUserCoins();
});
</script>


<style scoped>
.text-blue-500 {
  color: #3b82f6;
}
.bg-gray-100 {
  background-color: #f3f4f6;
}
</style>

<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <!-- Chatbox principale -->
    <div class="bg-white shadow-lg rounded-xl p-8 max-w-3xl w-full">
      <!-- Logo e Titolo -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Poster AI</h1>
        <div class="flex items-center space-x-4">
          
          <span class="text-3xl font-bold text-gray-900">Coin : {{ userCoin }}</span>
        </div>
      </div>

      <!-- Chatbox -->
      <div class="space-y-6">
        <textarea
          v-model="userPrompt"
          placeholder="Inserisci il tuo prompt qui..."
          class="w-full h-32 p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-gray-700 text-lg"
        ></textarea>

        <!-- Selezione delle piattaforme e invio -->
        <div class="flex flex-col space-y-4">
          <!-- Selezione delle piattaforme -->
          <div class="flex justify-around space-x-4">
            <!-- Instagram -->
            <button
              @click="toggleSelection('instagram')"
              :class="{
                'bg-indigo-600': selectedSocials.instagram,
                'bg-gray-300': !selectedSocials.instagram
              }"
              class="text-white font-semibold text-lg py-2 px-6 rounded-lg transition-transform transform hover:scale-105 flex items-center"
            >
              <font-awesome-icon :icon="['fab', 'instagram']" class="text-xl mr-2" />
              
            </button>
            <!-- TikTok -->
            <button
              @click="toggleSelection('tiktok')"
              :class="{
                'bg-pink-600': selectedSocials.tiktok,
                'bg-gray-300': !selectedSocials.tiktok
              }"
              class="text-white font-semibold text-lg py-2 px-6 rounded-lg transition-transform transform hover:scale-105 flex items-center"
            >
              <font-awesome-icon :icon="['fab', 'tiktok']" class="text-xl mr-2" />
              
            </button>
            <!-- Twitter -->
            <button
              @click="toggleSelection('twitter')"
              :class="{
                'bg-blue-600': selectedSocials.twitter,
                'bg-gray-300': !selectedSocials.twitter
              }"
              class="text-white font-semibold text-lg py-2 px-6 rounded-lg transition-transform transform hover:scale-105 flex items-center"
            >
              <font-awesome-icon :icon="['fab', 'twitter']" class="text-xl mr-2" />
              
            </button>
            <!-- Facebook -->
            <button
              @click="toggleSelection('facebook')"
              :class="{
                'bg-blue-700': selectedSocials.facebook,
                'bg-gray-300': !selectedSocials.facebook
              }"
              class="text-white font-semibold text-lg py-2 px-6 rounded-lg transition-transform transform hover:scale-105 flex items-center"
            >
              <font-awesome-icon :icon="['fab', 'facebook']" class="text-xl mr-2" />
              
            </button>
          </div>

          <!-- Bottone di invio -->
          <button
            @click="submitPrompt"
            class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-lg py-3 px-8 rounded-full shadow-lg transition-all transform hover:scale-110"
          >
            Invia il Prompt
          </button>
        </div>
      </div>
    </div>

    <!-- Contenitore della cronologia -->
    <div class="bg-white shadow-lg rounded-xl mt-8 p-8 max-w-3xl w-full h-96 overflow-y-auto mb-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Chat History:</h1>
      </div>

      <div v-if="promptHistory.length > 0" class="space-y-4">
        <div v-for="(entry, index) in promptHistory" :key="index" class="space-y-2">
          <!-- Bolla del prompt (utente) -->
          <div class="flex justify-end">
            <div class="bg-blue-500 text-white p-2 rounded-2xl max-w-full shadow-md">
              <p class="text-sm font-medium">Tu:</p>
              <p class="mt-1 text-base leading-tight">{{ entry.prompt }}</p>
            </div>
          </div>

          <!-- Risposte per social media (AI) -->
          <div v-if="entry.response">
            <!-- Instagram -->
            <div v-if="entry.response.instagram" class="flex justify-start">
              <div class="bg-indigo-100 text-gray-800 p-2 rounded-2xl max-w-full shadow-md border-l-4 border-indigo-500">
                <p class="text-sm font-medium">Poster AI (Instagram):</p>
                <p class="mt-1 text-base leading-tight">{{ entry.response.instagram }}</p>
              </div>
            </div>

            <!-- TikTok -->
            <div v-if="entry.response.tiktok" class="flex justify-start mt-2">
              <div class="bg-pink-100 text-gray-800 p-2 rounded-2xl max-w-full shadow-md border-l-4 border-pink-500">
                <p class="text-sm font-medium">Poster AI (TikTok):</p>
                <p class="mt-1 text-base leading-tight">{{ entry.response.tiktok }}</p>
              </div>
            </div>

            <!-- Twitter -->
            <div v-if="entry.response.twitter" class="flex justify-start mt-2">
              <div class="bg-blue-100 text-gray-800 p-2 rounded-2xl max-w-full shadow-md border-l-4 border-blue-500">
                <p class="text-sm font-medium">Poster AI (Twitter):</p>
                <p class="mt-1 text-base leading-tight">{{ entry.response.twitter }}</p>
              </div>
            </div>

            <!-- Facebook -->
            <div v-if="entry.response.facebook" class="flex justify-start mt-2">
              <div class="bg-blue-200 text-gray-800 p-2 rounded-2xl max-w-full shadow-md border-l-4 border-blue-700">
                <p class="text-sm font-medium">Poster AI (Facebook):</p>
                <p class="mt-1 text-base leading-tight">{{ entry.response.facebook }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


  
  