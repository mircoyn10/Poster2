<template>
  <AppLayout>
    <Head title="Trending Searches" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Popular Trends</h2>
    </template>
    
    <div class="bg-white text-gray-900 min-h-screen relative flex flex-col items-center justify-center transition-all duration-500">
      <!-- Background with light opacity -->
      <img
        id="background"
        class="absolute inset-0 object-cover w-full h-full transition-opacity duration-500"
        src="http://127.0.0.1:8000/storage/img/minimalist_back_3.jpg"
        alt="background"
      />

      <div class="relative z-10 max-w-5xl w-full mx-auto p-10 rounded-lg shadow-2xl bg-white bg-opacity-70 transition-all duration-500 backdrop-filter mt-8">
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-[#00A3E0] shadow-md">Trending Searches</h1>
          <p class="text-gray-700 shadow-md">Explore top trending searches by country.</p>
        </div>
        
        <div class="flex justify-center mb-6">
          <select 
            id="country-select" 
            v-model="selectedCountry" 
            @change="fetchTrends"
            class="block w-full max-w-sm p-2 border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="US">United States</option>
            <option value="IT">Italy</option>
            <option value="JP">Japan</option>
          </select>
        </div>
        
        <div v-if="error" class="text-red-500 text-center">
          <p>{{ error }}</p>
        </div>
        
        <div v-else>
          <ul v-if="trends.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <li 
              v-for="(trend, index) in trends" 
              :key="index"
              class="bg-white bg-opacity-50 border border-gray-200 p-4 rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105"
            >
              <span class="text-lg font-semibold text-gray-700">{{ index + 1 }}. {{ trend }}</span>
            </li>
          </ul>
          <p v-else class="text-center text-gray-700 shadow-md">No trends available for the selected country.</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const trends = ref([]);
const error = ref(null);
const selectedCountry = ref('US');

// Fetch trends based on selected country
const fetchTrends = async () => {
  try {
    const response = await axios.get(`/trends/${selectedCountry.value}`);
    trends.value = response.data.trends;
    error.value = null;
  } catch (err) {
    error.value = 'Unable to retrieve trends at the moment.';
  }
};

// Fetch trends on mount
onMounted(fetchTrends);
</script>

<style scoped>
body {
  transition: background-color 0.5s ease;
}

h1, p {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.hover\:scale-105 {
  transition: transform 0.3s ease-in-out;
}

select {
  background-color: rgba(255, 255, 255, 0.8);
}
</style>