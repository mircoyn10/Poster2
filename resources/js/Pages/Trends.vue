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

<template>
  <AppLayout>
    <Head title="Trending Searches" />

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Google Trends
      </h2>
    </template>

    <main class="py-12 bg-gray-100">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-8">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-[#00A3E0]">Trending Searches</h3>
            <p class="text-gray-500">Explore top trending searches by country.</p>
          </div>
          
          <div class="flex justify-center mb-6">
            <select id="country-select" v-model="selectedCountry" @change="fetchTrends"
                    class="block w-full max-w-sm p-2 border-gray-300 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
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
              <li v-for="(trend, index) in trends" :key="index"
                  class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105">
                <span class="text-lg font-semibold text-gray-700">{{ index + 1 }}. {{ trend }}</span>
              </li>
            </ul>
            <p v-else class="text-center text-gray-500">No trends available for the selected country.</p>
          </div>
        </div>
      </div>
    </main>
  </AppLayout>
</template>

<style scoped>
/* Animation & Styling */
.hover\:scale-105 {
  transition: transform 0.3s ease-in-out;
}
</style>
