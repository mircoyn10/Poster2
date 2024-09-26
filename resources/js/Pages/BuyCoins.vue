<template>
  <AppLayout>
    <Head title="Purchase Coins" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buy Coins</h2>
    </template>

    
    <div class="bg-white text-gray-900 min-h-screen relative flex flex-col items-center justify-center transition-all duration-500">
      <!-- Background with light opacity -->
      <img
        id="background"
        class="absolute inset-0 object-cover w-full h-full transition-opacity duration-500"
        src="http://127.0.0.1:8000/storage/img/minimalist_back_3.jpg"
        alt="background"
      />

      <div class="relative z-10 max-w-7xl w-full mx-auto p-10 rounded-lg shadow-2xl bg-white bg-opacity-30 transition-all duration-500 backdrop-filter mt-8">
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-[#00A3E0] shadow-md">Choose Your Coin Package</h1>
          <p class="text-gray-700 shadow-md">Coins allow you to create engaging content. Choose the package that fits your needs and start generating content today!</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="(card, index) in cardData" :key="card.id"
               class="bg-white bg-opacity-80 p-6 rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105">
            <div :class="{
                'bg-yellow-100 border-yellow-400': index === 0,
                'bg-gray-200 border-gray-500': index === 1,
                'bg-yellow-300 border-yellow-600': index === 2
              }" class="p-4 rounded-lg">
              <h4 :class="{
                'text-yellow-700': index === 0,
                'text-gray-700': index === 1,
                'text-yellow-800': index === 2
              }" class="text-2xl font-bold mb-2 shadow-md">
                {{ index === 0 ? 'Bronze' : index === 1 ? 'Silver' : 'Gold' }} Package
              </h4>
              <p class="text-lg text-gray-700">{{ card.price }} {{ card.currency }}</p>
              <p class="mt-4 text-gray-600">Get <span class="font-semibold">{{ card.coins }}</span> coins.</p>
            </div>
           
            <button @click="purchaseCoins(card.id)"
                    class="mt-6 w-full py-3 text-white rounded-lg text-lg font-bold transition transform hover:scale-105 shadow-md"
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
  </AppLayout>
</template>

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

<style scoped>
body {
  transition: background-color 0.5s ease;
}

h1, h4, p {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.hover\:scale-105 {
  transition: transform 0.3s ease-in-out;
}
</style>