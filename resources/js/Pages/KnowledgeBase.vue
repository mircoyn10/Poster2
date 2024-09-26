<template>
    <AppLayout>
      <Head title="Knowledge Base" />
  
      <!-- Header Section with Submenu -->
      <nav class="bg-white shadow-md w-full px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
          <!-- Main Title -->
          <h1 class="text-2xl font-bold text-gray-800">Knowledge Base</h1>
          
          <!-- Submenu for Knowledge Base Sections -->
          <div 
            class="relative"
            @mouseenter="showSubmenu = true" 
            @mouseleave="showSubmenu = false"
          >
            <button class="text-gray-600 font-semibold focus:outline-none">
              Sections
              <svg class="ml-1 w-5 h-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
  
            <div 
              v-if="showSubmenu"
              class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 transition-all duration-300"
            >
              <Link href="#trends" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Trends</Link>
              <Link href="#post-generation" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Post Generation</Link>
              <Link href="#content-scheduling" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Content Scheduling</Link>
              <Link href="#seo-optimization" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">SEO Optimization</Link>
              <Link href="#user-guide" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">User Guide</Link>
              <Link href="#faq" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">FAQ</Link>
            </div>
          </div>
        </div>
      </nav>
  
      <div class="bg-white text-gray-900 min-h-screen relative flex flex-col items-center justify-center transition-all duration-500 ">
        <!-- Background with light opacity -->
        <img
          id="background"
          class="absolute inset-0 object-cover w-full h-full  transition-opacity duration-500"
          src="http://127.0.0.1:8000/storage/img/minimalist_back_3.jpg"
          alt="background"
        />
  
        <!-- Main container with transparency and elegance -->
        <div class="relative z-10 max-w-5xl w-full mx-auto p-10 rounded-lg shadow-2xl bg-white bg-opacity-30 transition-all duration-500 backdrop-filter mt-8">
          <!-- Knowledge Sections -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 bg-opacity-10">
            <!-- Trends Section -->
            <div id="trends" class="knowledge-card">
              <img src="https://img.icons8.com/color/48/000000/trend.png" alt="Trends" class="h-12 w-12 mb-4 mx-auto"/>
              <h2 class="text-2xl font-bold mb-2 text-[#00A3E0] shadow-md">Trends</h2>
              <p class="text-gray-700 shadow-md">
                Explore the latest trending topics and get inspired with content ideas. Stay ahead of the curve and see what's hot in your industry.
              </p>
              <Link href="/trends" class="text-blue-500 hover:underline mt-4 block text-center">Learn More</Link>
            </div>
  
            <!-- Post Generation Section -->
            <div id="post-generation" class="knowledge-card">
              <img src="https://img.icons8.com/color/48/000000/edit.png" alt="Post Generation" class="h-12 w-12 mb-4 mx-auto"/>
              <h2 class="text-2xl font-bold mb-2 text-[#00A3E0] shadow-md">Post Generation</h2>
              <p class="text-gray-700 shadow-md">
                Generate optimized social media posts for various platforms. Customize your content for maximum engagement and reach your audience effectively.
              </p>
              <Link href="/generate-posts" class="text-blue-500 hover:underline mt-4 block text-center">Learn More</Link>
            </div>
  
            <!-- Content Scheduling Section -->
            <div id="content-scheduling" class="knowledge-card">
              <img src="https://img.icons8.com/color/48/000000/calendar.png" alt="Calendar" class="h-12 w-12 mb-4 mx-auto"/>
              <h2 class="text-2xl font-bold mb-2 text-[#00A3E0] shadow-md">Content Scheduling</h2>
              <p class="text-gray-700 shadow-md">
                Plan and schedule your posts with ease. Use our integrated calendar to keep your social media strategy organized and efficient.
              </p>
              <Link href="/calendar" class="text-blue-500 hover:underline mt-4 block text-center">Learn More</Link>
            </div>
  
            <!-- SEO Optimization Section -->
            <div id="seo-optimization" class="knowledge-card">
              <img src="https://img.icons8.com/color/48/000000/seo.png" alt="SEO Optimization" class="h-12 w-12 mb-4 mx-auto"/>
              <h2 class="text-2xl font-bold mb-2 text-[#00A3E0] shadow-md">SEO Optimization</h2>
              <p class="text-gray-700 shadow-md">
                Enhance your content's visibility with our powerful SEO tools. Improve your search engine ranking and attract more organic traffic to your posts.
              </p>
              <Link href="/seo" class="text-blue-500 hover:underline mt-4 block text-center">Learn More</Link>
            </div>
  
            <!-- User Guide Section -->
            <div id="user-guide" class="knowledge-card">
              <img src="https://img.icons8.com/color/48/000000/manual.png" alt="User Guide" class="h-12 w-12 mb-4 mx-auto"/>
              <h2 class="text-2xl font-bold mb-2 text-[#00A3E0] shadow-md">User Guide</h2>
              <p class="text-gray-700 shadow-md">
                Get started with our comprehensive user guide. Learn how to use Poster IA to its full potential with step-by-step instructions and best practices.
              </p>
              <Link href="/user-guide" class="text-blue-500 hover:underline mt-4 block text-center">Learn More</Link>
            </div>
  
            <!-- FAQ Section -->
            <div id="faq" class="knowledge-card">
              <img src="https://img.icons8.com/color/48/000000/faq.png" alt="FAQ" class="h-12 w-12 mb-4 mx-auto"/>
              <h2 class="text-2xl font-bold mb-2 text-[#00A3E0] shadow-md">FAQ</h2>
              <p class="text-gray-700 shadow-md">
                Have questions? Check out our Frequently Asked Questions section to find answers to the most common inquiries about Poster IA.
              </p>
              <Link href="/faq" class="text-blue-500 hover:underline mt-4 block text-center">Learn More</Link>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { Head, Link } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  // State for showing/hiding submenu
  const showSubmenu = ref(false);
  </script>
  
  <style scoped>
  body {
    transition: background-color 0.5s ease;
  }
  
  h1, h2, p {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }
  
  .knowledge-card {
    padding: 1.5rem;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.5s ease, transform 0.3s ease;
    text-align: center;
    position: relative;
  }
  
  .knowledge-card:hover {
    background-color: rgba(255, 255, 255, 1);
    transform: translateY(-5px);
  }
  
  img {
    margin-bottom: 1rem;
  }
  
  @media (max-width: 768px) {
    .knowledge-card {
      margin-bottom: 1rem;
    }
  }
  </style>
  