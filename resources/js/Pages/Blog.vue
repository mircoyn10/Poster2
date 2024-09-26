<template>
    <AppLayout>
      <Head title="Blog" />
  
      <!-- Main Blog Container -->
      <div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <!-- Header Section -->
        <div class="text-center mb-10">
          <h1 class="text-4xl font-extrabold text-gray-800 mb-4">Welcome to Our Blog</h1>
          <p class="text-lg text-gray-600">
            Explore our latest updates, articles, and resources on SEO, digital marketing, and more.
          </p>
        </div>
  
        <!-- Featured Articles Section -->
        <section class="mb-12">
          <h2 class="text-2xl font-semibold text-gray-800 mb-4">Featured Articles</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="post in featuredPosts" :key="post.id" class="rounded-lg overflow-hidden shadow-lg">
              <img :src="post.image" :alt="post.title" class="h-48 w-full object-cover" />
              <div class="p-4">
                <h3 class="text-xl font-bold text-gray-800 mb-2 hover:text-blue-500 cursor-pointer">{{ post.title }}</h3>
                <p class="text-gray-600 mb-4">{{ post.excerpt }}</p>
                <Link :href="`/blog/${post.slug}`" class="text-blue-500 hover:underline">Read More</Link>
              </div>
            </div>
          </div>
        </section>
  
        <!-- All Articles Section -->
        <section class="mb-12">
          <h2 class="text-2xl font-semibold text-gray-800 mb-4">Latest Articles</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="post in posts" :key="post.id" class="rounded-lg overflow-hidden shadow-lg bg-gray-50">
              <img :src="post.image" :alt="post.title" class="h-40 w-full object-cover" />
              <div class="p-4">
                <h3 class="text-xl font-bold text-gray-800 mb-2 hover:text-blue-500 cursor-pointer">{{ post.title }}</h3>
                <p class="text-gray-600 mb-4">{{ post.excerpt }}</p>
                <Link :href="`/blog/${post.slug}`" class="text-blue-500 hover:underline">Read More</Link>
              </div>
            </div>
          </div>
        </section>
  
        <!-- Load More Button -->
        <div class="text-center">
          <button @click="loadMoreArticles" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            Load More Articles
          </button>
        </div>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { Head, Link } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  const posts = ref([]);
  const featuredPosts = ref([]);
  const currentPage = ref(1);
  
  // Fetch all articles
  const fetchPosts = async () => {
    try {
      const response = await axios.get('/api/blog-posts', { params: { page: currentPage.value } });
      posts.value = response.data.data; // Assuming response.data contains an array of articles
    } catch (error) {
      console.error('Error fetching posts:', error);
    }
  };
  
  // Fetch featured articles
  const fetchFeaturedPosts = async () => {
    try {
      const response = await axios.get('/api/blog-featured');
      featuredPosts.value = response.data; // Assuming response.data contains an array of featured articles
    } catch (error) {
      console.error('Error fetching featured posts:', error);
    }
  };
  
  // Load more articles
  const loadMoreArticles = async () => {
    currentPage.value++;
    await fetchPosts();
  };
  
  // Fetch articles on component mount
  onMounted(() => {
    fetchPosts();
    fetchFeaturedPosts();
  });
  </script>
  
  <style scoped>
  h1 {
    color: #1F2937; /* Darker text color for title */
  }
  
  ul {
    list-style: none;
    padding: 0;
  }
  
  img {
    transition: transform 0.3s ease;
  }
  
  img:hover {
    transform: scale(1.05);
  }
  
  button {
    transition: background-color 0.3s ease;
  }
  </style>
  
  