<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';

const cardData = ref([]);

// Fetch coin packages
const fetchPackages = async () => {
  try {
    const { data } = await axios.get('/api/coin-packages');
    cardData.value = data;
  } catch (error) {
    console.error('Error loading packages:', error);
  }
};

// Purchase coins
const purchaseCoins = async (packageId) => {
  try {
    const { data } = await axios.post('/payment/create', { package_id: packageId });
    if (data.status === "CREATED") {
      const approvalUrl = data.links.find(link => link.rel === 'approve').href;
      window.location.href = approvalUrl;
    } else {
      throw new Error('Order not created');
    }
  } catch (error) {
    console.error('Payment creation error:', error);
    alert(`Payment creation failed: ${error.message}`);
  }
};

// Fetch data on mount
onMounted(fetchPackages);
</script>

<template>
  <AppLayout>
    <Head title="Purchase Coins" />

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Get Coins
      </h2>
    </template>

    <!-- Main Content -->
    <main class="py-12 bg-gray-100">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-8">
          <div class="text-center mb-8">
            <h3 class="text-2xl font-bold text-[#00A3E0]">Choose Your Coin Package</h3>
            <p class="text-gray-500">Coins allow you to create engaging content. Choose the package that fits your needs and start generating content today!</p>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="(card, index) in cardData" :key="card.id" 
                 class="p-6 rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105">
              <div :class="{
                  'bg-yellow-100 border-yellow-400': index === 0,
                  'bg-gray-200 border-gray-500': index === 1,
                  'bg-yellow-300 border-yellow-600': index === 2
                }" class="p-4 rounded-lg">
                <h4 :class="{
                  'text-yellow-700': index === 0,
                  'text-gray-700': index === 1,
                  'text-yellow-800': index === 2
                }" class="text-2xl font-bold mb-2">
                  {{ index === 0 ? 'Bronze' : index === 1 ? 'Silver' : 'Gold' }} Package
                </h4>
                <p class="text-lg text-gray-700">{{ card.price }} {{ card.currency }}</p>
                <p class="mt-4 text-gray-600">Get <span class="font-semibold">{{ card.coins }}</span> coins.</p>
              </div>
              
              <button @click="purchaseCoins(card.id)"
                      class="mt-6 w-full py-3 text-white rounded-lg text-lg font-bold transition transform hover:scale-105"
                      :class="{
                        'bg-yellow-600 hover:bg-yellow-700': index === 0,
                        'bg-gray-600 hover:bg-gray-700': index === 1,
                        'bg-yellow-800 hover:bg-yellow-900': index === 2
                      }">
                Pay with PayPal
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </AppLayout>
</template>

<style scoped>
/* Animation & Card Styling */
.hover\:scale-105 {
  transition: transform 0.3s ease-in-out;
}
</style>
