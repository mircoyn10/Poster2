<template>
    <AppLayout>
      <Head title="Trending Searches" />
  
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Google Trends
        </h2>
      </template>
  
      <!-- Page Content -->
      <main>
        <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Select Country to View Trends</h3>
                <p class="mt-1 text-sm text-gray-500">
                  Explore the top trending searches for different countries and stay updated with the latest popular topics.
                </p>
              </div>
  
              <div class="border-t border-gray-200">
                <div class="p-6">
                  <!-- Country Selection Dropdown -->
                  <div class="mb-4">
                    <label for="country-select" class="block text-sm font-medium text-gray-700">Select Country:</label>
                    <select
                      id="country-select"
                      v-model="selectedCountry"
                      @change="fetchTrends"
                      class="mt-1 block w-full p-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                      <option value="US">United States</option>
                      <option value="IT">Italy</option>
                      <option value="JP">Japan</option>
                      <!-- Aggiungi altre opzioni qui -->
                    </select>
                  </div>
  
                  <!-- Display Trends -->
                  <div v-if="error" class="text-red-500">
                    <p>{{ error }}</p>
                  </div>
  
                  <div v-else>
                    <ul v-if="trends.length > 0" class="space-y-4">
                      <li
                        v-for="(trend, index) in trends"
                        :key="index"
                        class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow"
                      >
                        <span class="font-semibold text-lg text-gray-700">{{ index + 1 }}. {{ trend }}</span>
                      </li>
                    </ul>
                    <p v-else class="text-sm text-gray-500">No trends available for the selected country.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </AppLayout>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { Head } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  // Setup state for trends, error, and country selection
  const trends = ref([]);
  const error = ref(null);
  const selectedCountry = ref('US'); // Default country set to 'US'
  
  // Function to fetch trends from the server
  const fetchTrends = async () => {
    try {
      const response = await axios.get(`/trends/${selectedCountry.value}`);
      trends.value = response.data.trends;
      error.value = null;
    } catch (err) {
      error.value = 'Unable to retrieve trends at the moment.';
    }
  };
  
  // Fetch trends when the component is mounted
  onMounted(() => {
    fetchTrends();
  });
  </script>
  
  <style scoped>
  h3 {
    margin-bottom: 1rem;
  }
  
  ul {
    list-style-type: none;
    padding: 0;
  }
  
  li {
    margin-bottom: 0.75rem;
  }
  </style>
  

