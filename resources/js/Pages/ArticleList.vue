<template>
  <AppLayout>
    <Head title="Blog" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Poster Blog</h2>
    </template>

    <div class="py-12 bg-gray-100" style="background-image: url('/storage/img/minimalist_back_3.jpg'); background-repeat: repeat; background-position: top left;">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8 p-8 bg-opacity-80">
          <h2 class="text-3xl font-bold mb-8 text-indigo-700">Article Index</h2>
          <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <li v-for="article in articles" :key="article.id">
              <router-link
                :to="{ name: 'single-article', params: { id: article.id } }"
                class="text-lg text-indigo-600 hover:text-indigo-800 transition duration-300 flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                {{ article.title }}
              </router-link>
            </li>
          </ul>
        </div>
        <div class="flex justify-between">
          <button @click="prevPage" :disabled="pagination.current_page === 1" class="px-4 py-2 bg-indigo-600 text-white rounded">Previous</button>
          <button @click="nextPage" :disabled="pagination.current_page === pagination.last_page" class="px-4 py-2 bg-indigo-600 text-white rounded">Next</button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Stato per gli articoli e la paginazione
const articles = ref([]);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0
});

// Funzione per recuperare gli articoli in base alla pagina corrente
const fetchArticles = async (page = 1) => {
  try {
    const response = await axios.get('/articles', { params: { page } });
    // Popola gli articoli e la paginazione con i dati della risposta
    articles.value = response.data.data;
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      per_page: response.data.per_page,
      total: response.data.total
    };
  } catch (error) {
    console.error('Error fetching articles:', error);
  }
};

// Esegui la funzione per recuperare gli articoli quando il componente viene montato
onMounted(() => fetchArticles(pagination.value.current_page));

// Funzione per la pagina successiva
const nextPage = () => {
  if (pagination.value.current_page < pagination.value.last_page) {
    fetchArticles(++pagination.value.current_page);
  }
};

// Funzione per la pagina precedente
const prevPage = () => {
  if (pagination.value.current_page > 1) {
    fetchArticles(--pagination.value.current_page);
  }
};
</script>

<style scoped>
/* Usa gli stessi stili presenti nel tuo progetto per mantenere la coerenza */
</style>
