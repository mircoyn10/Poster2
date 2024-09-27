<template>
  <AppLayout>
    <Head title="Blog" />
    <div class="py-12 bg-gray-100">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
          <h2 class="text-3xl font-bold mb-8 text-indigo-700">Latest Articles</h2>
          <div v-if="articles.length === 0" class="text-gray-500 text-center py-10">
            <p class="text-xl">No articles yet.</p>
            <p class="mt-2">Be the first to share your thoughts!</p>
          </div>
          <div v-else class="space-y-10">
            <article
              v-for="article in articles"
              :key="article.id"
              class="bg-white p-6 rounded-lg shadow-md transition duration-300 hover:shadow-xl"
            >
              <h3 class="text-2xl font-semibold mb-3 text-gray-800 hover:text-indigo-600 transition duration-300">
                {{ article.title }}
              </h3>
              <p v-if="!article.expanded" class="text-gray-600 mb-4 leading-relaxed">
                {{ truncateText(article.body, 150) }}
              </p>
              <p v-else class="text-gray-600 mb-4 leading-relaxed">{{ article.body }}</p>
              <button 
                @click="toggleArticle(article)" 
                class="text-indigo-600 hover:text-indigo-800 transition duration-300 mb-4"
              >
                {{ article.expanded ? 'Leggi meno' : 'Leggi di più' }}
              </button>
              <img
                v-if="article.image"
                :src="`/storage/${article.image}`"
                :alt="article.title"
                class="w-full h-64 object-cover rounded-md mb-4"
              />
              <div class="flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                  </svg>
                  <span>{{ article.user.name }}</span>
                </div>
                <div class="flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                  </svg>
                  <span>{{ formatDate(article.created_at) }}</span>
                </div>
              </div>
            </article>
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

const articles = ref([]);

const fetchArticles = async () => {
  try {
    const response = await axios.get('/articles');
    articles.value = response.data.map(article => ({
      ...article,
      expanded: false
    }));
  } catch (error) {
    console.error('Error fetching articles:', error);
  }
};

onMounted(fetchArticles);

const truncateText = (text, length) => {
  return text.length > length ? text.substring(0, length) + '...' : text;
};

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const toggleArticle = (article) => {
  article.expanded = !article.expanded;
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

.bg-gray-100 {
  background-color: #f7fafc;
}

h2, h3 {
  font-family: 'Poppins', sans-serif;
}

article {
  border-left: 4px solid #00A3E0;
}

article:hover {
  border-left-color: #00A3E0;
}

.text-indigo-600 {
  color: #00A3E0;
}

.text-indigo-700 {
  color: #00A3E0;
}
</style>