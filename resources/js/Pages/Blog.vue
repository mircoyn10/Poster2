<template>
  <AppLayout>
    <Head title="Blog" />
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Poster Blog</h2>
    </template>
    
    <div class="py-12 bg-gray-100" style="background-image: url('http://127.0.0.1:8000/storage/img/minimalist_back_3.jpg'); background-repeat: repeat; background-position: top left;">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Index Section -->
        <!-- Index Section -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8 p-8 bg-opacity-80">
          <h2 class="text-3xl font-bold mb-8 text-indigo-700">Article Index</h2>
          <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <li v-for="article in allArticles" :key="article.id">
              <!-- Usa router-link per collegare gli articoli alla pagina singola -->
              <router-link
                :to="{ name: 'single-article', params: { id: article.id } }"
                @click.prevent="navigateToArticle(article.id)"
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


        <!-- Latest Two Articles Section -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 mb-8 bg-opacity-80">
          <h2 class="text-3xl font-bold mb-8 text-indigo-700">Featured Articles</h2>
          <div v-if="latestArticles.length === 0" class="text-gray-500 text-center py-10">
            <p class="text-xl">No articles yet.</p>
            <p class="mt-2">Be the first to share your thoughts!</p>
          </div>
          <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <article
              v-for="article in latestArticles"
              :key="article.id"
              class="bg-white p-6 rounded-lg shadow-md transition duration-300 hover:shadow-xl border border-gray-200"
            >
              <h3 class="text-2xl font-semibold mb-3 text-gray-800 hover:text-indigo-600 transition duration-300">
                <!-- Link all'articolo dinamico con router-link -->
                <router-link :to="{ name: 'single-article', params: { id: article.id } }">
                  {{ article.title }}
                </router-link>
              </h3>

              <div class="relative">
                <img
                  v-if="article.image"
                  :src="`/storage/${article.image}`"
                  :alt="article.title"
                  class="w-full h-64 object-cover rounded-md mb-4"
                />
                <div 
                  class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-50 rounded-md"
                  v-if="article.image"
                ></div>
              </div>

              <div 
                v-html="getArticleBody(article)" 
                class="text-gray-600 mb-4 leading-relaxed prose prose-sm max-w-none"
              ></div>

              <router-link 
                v-if="article.body.length > 150" 
                :to="{ name: 'single-article', params: { id: article.id } }"
                class="text-indigo-600 hover:text-indigo-800 transition duration-300 mb-4 flex items-center"
              >
                Read more
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </router-link>

              <div class="flex items-center justify-between text-sm text-gray-500 mt-4 pt-4 border-t border-gray-200">
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

        <!-- All Articles Section with Pagination -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8 bg-opacity-80">
          <h2 class="text-3xl font-bold mb-8 text-indigo-700">All Articles</h2>
          <div v-if="paginatedArticles.length === 0" class="text-gray-500 text-center py-10">
            <p class="text-xl">No articles available.</p>
          </div>
          <div v-else class="space-y-8">
            <article
              v-for="article in paginatedArticles"
              :key="article.id"
              class="bg-white p-6 rounded-lg shadow-md transition duration-300 hover:shadow-xl border border-gray-200"
            >
              <h3 class="text-2xl font-semibold mb-3 text-gray-800 hover:text-indigo-600 transition duration-300">
                {{ article.title }}
              </h3>

              <div v-html="getArticleBody(article)" class="text-gray-600 mb-4 leading-relaxed prose prose-sm max-w-none"></div>

              <button 
                v-if="article.body.length > 150" 
                @click="toggleArticle(article)" 
                class="text-indigo-600 hover:text-indigo-800 transition duration-300 mb-4 flex items-center"
              >
                {{ article.expanded ? 'Read less' : 'Read more' }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>

              <div class="flex items-center justify-between text-sm text-gray-500 mt-4 pt-4 border-t border-gray-200">
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
          <pagination-links :links="paginationLinks" />
        </div>
      </div>
    </div>
    
  </AppLayout>
</template>
<script setup>
import Footer from '@/Components/Footer.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const allArticles = ref([]);
const latestArticles = ref([]);
const currentPage = ref(1);
const articlesPerPage = 10;

// Aggiungi una variabile per la paginazione
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: articlesPerPage,
  total: 0
});

// Toggle article body visibility
const toggleArticle = (article) => {
  article.expanded = !article.expanded;
};
const navigateToArticle = (id) => {
  router.push({ name: 'single-article', params: { id: id } });
};
// Fetch articles from the server
const fetchArticles = async () => {
  try {
    const response = await axios.get('/articles', {
      params: { page: currentPage.value }
    });

    if (response.data && response.data.data) {
      allArticles.value = response.data.data.map(article => ({
        ...article,
        expanded: false
      }));

      latestArticles.value = allArticles.value.slice(0, 2);

      pagination.value = {
        current_page: response.data.current_page,
        last_page: response.data.last_page,
        per_page: response.data.per_page,
        total: response.data.total
      };
    }
  } catch (error) {
    console.error('Error fetching articles:', error);
  }
};

onMounted(fetchArticles);



const paginationLinks = computed(() => {
  const links = [];
  for (let i = 1; i <= pagination.value.last_page; i++) {
    links.push({ page: i, isActive: i === pagination.value.current_page });
  }
  return links;
});


onMounted(fetchArticles);

// Computed property for paginated articles
const paginatedArticles = computed(() => {
  const start = (currentPage.value - 1) * articlesPerPage;
  const end = start + articlesPerPage;
  return allArticles.value.slice(start, end);
});

// Computed property for total pages
const totalPages = computed(() => pagination.value.last_page);

// Pagination methods
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    fetchArticles();
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchArticles();
  }
};

// Truncate text and show a read more button if needed
const getArticleBody = (article) => {
  return article.expanded ? article.body : article.body.length > 150 ? article.body.substring(0, 150) + '...' : article.body;
};

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

// Toggle the expanded state of an article


// Scroll to article function (you may need to implement this based on your layout)
const scrollToArticle = (articleId) => {
  console.log(`Scrolling to article ${articleId}`);
};
</script>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

.bg-gray-100 {
  background-color: #f7fafc;
}

h1, h2, h3, h4, h5, h6 {
  font-family: 'Poppins', sans-serif;
}

p {
  margin-top: 0.5em;
  margin-bottom: 1em;
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

/* Add more custom styles as needed */
</style>