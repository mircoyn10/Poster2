<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';

const cardData = ref([]);

// Recupera i pacchetti dal backend all'avvio del componente
const fetchPackages = async () => {
  try {
    const { data } = await axios.get('/api/coin-packages');
    cardData.value = data; // Imposta i pacchetti di monete recuperati
  } catch (error) {
    console.error('Errore nel caricamento dei pacchetti:', error);
  }
};

// Funzione per l'acquisto di coin tramite pacchetto selezionato
const purchaseCoins = async (packageId) => {
  try {
    const { data } = await axios.post('/payment/create', { package_id: packageId });
    const orderID = data.id;

    // Redirige a PayPal per completare il pagamento
    window.open(data.links[1].href, '_blank');
    
    // Gestisci l'ordine dopo il pagamento
    const result = await axios.post('/payment/execute', { orderID, package_id: packageId });
    alert(result.data.message);
  } catch (error) {
    alert('Payment failed');
  }
};

// Monta il componente e carica i pacchetti
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

    <!-- Page Content -->
    <main>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
              <h3 class="text-lg font-medium leading-6 text-gray-900">Select Your Coin Package</h3>
              <p class="mt-1 text-sm text-gray-500">
                Coins allow you to create engaging content on multiple social platforms. Choose the package that fits your needs and start generating content today!
              </p>
            </div>
            <div class="border-t border-gray-200">
              <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <div class="sm:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Available Packages</h3>
                  </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <!-- Cicla sui pacchetti caricati dal database -->
                  <div v-for="(card, index) in cardData" :key="card.id" class="border p-6 m-2.5 rounded-lg shadow-lg text-center transition transform hover:scale-105 hover:shadow-xl"
                        :class="{
                        'bg-yellow-100 border-yellow-400': index === 0, // Bronze package
                        'bg-gray-200 border-gray-500': index === 1,     // Silver package
                        'bg-yellow-300 border-yellow-600': index === 2  // Gold package
                        }">
                    <!-- Tier Title -->
                    <h4 class="text-2xl font-bold" 
                        :class="{
                            'text-yellow-700': index === 0, // Bronze
                            'text-gray-700': index === 1,    // Silver
                            'text-yellow-800': index === 2   // Gold
                        }">
                        {{ index === 0 ? 'Bronze' : index === 1 ? 'Silver' : 'Gold' }} Package
                    </h4>

                    <!-- Package Price -->
                    <p class="text-lg font-medium mt-2">{{ card.price }} {{ card.currency }}</p>

                    <!-- Coin Amount -->
                    <p class="text-md mt-4">
                        Get <span class="font-semibold">{{ card.coins }}</span> coins to boost your content creation.
                    </p>

                    <!-- Call to Action Button -->
                    <button @click="purchaseCoins(card.id)"
                            class="mt-6 px-6 py-3 rounded-full text-white font-bold text-lg transition transform hover:scale-105"
                            :class="{
                                'bg-yellow-600 hover:bg-yellow-700': index === 0,  // Bronze
                                'bg-gray-600 hover:bg-gray-700': index === 1,      // Silver
                                'bg-yellow-800 hover:bg-yellow-900': index === 2   // Gold
                            }">
                        Purchase Now
                    </button>
                  </div>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </main>
  </AppLayout>
</template>

<style scoped>
/* Additional Styles */
.hover\:scale-105 {
  transition: transform 0.3s ease-in-out;
}
</style>
