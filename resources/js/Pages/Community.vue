<template>
    <app-layout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Community</h2>
      </template>
      <div class="bg-white text-gray-900 min-h-screen relative flex flex-col items-center justify-center transition-all duration-500 ">
        <!-- Background with light opacity -->
        <img
          id="background"
          class="absolute inset-0 object-cover w-full h-full  duration-500"
          src="http://127.0.0.1:8000/storage/img/minimalist_back_3.jpg"
          alt="background"
        />
    
        <div class="py-12 relative z-10"> <!-- Add z-10 to keep this above the background -->
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-purple-800 to-purple-500 overflow-hidden shadow-xl sm:rounded-lg p-8">

              <h1 class="text-4xl font-extrabold text-white text-center mb-8">Join Our Community</h1>
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <!-- Instagram Bubble -->
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-3xl p-8 flex flex-col items-center justify-center transform transition duration-500 hover:scale-105">
                  <img :src="asset('images/instagram-logo.png')" alt="Instagram" class="w-16 h-16 mb-4" />
                  <h2 class="text-2xl font-bold text-white mb-2">Instagram</h2>
                  <p class="text-white text-center mb-4">Follow us for behind-the-scenes content and community highlights!</p>
                  <a :href="instagramUrl" target="_blank" rel="noopener noreferrer" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    Follow @{{ instagramHandle }}
                  </a>
                </div>
    
                <!-- TikTok Bubble -->
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-3xl p-8 flex flex-col items-center justify-center transform transition duration-500 hover:scale-105">
                  <img :src="asset('images/tiktok-logo.png')" alt="TikTok" class="w-16 h-16 mb-4" />
                  <h2 class="text-2xl font-bold text-white mb-2">TikTok</h2>
                  <p class="text-white text-center mb-4">Watch our latest videos and join the fun on TikTok!</p>
                  <a :href="tiktokUrl" target="_blank" rel="noopener noreferrer" class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    Follow @{{ tiktokHandle }}
                  </a>
                </div>
    
                <!-- Blog Bubble -->
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-3xl p-8 flex flex-col items-center justify-center transform transition duration-500 hover:scale-105">
                  <img :src="asset('images/blog-icon.png')" alt="Blog" class="w-16 h-16 mb-4" />
                  <h2 class="text-2xl font-bold text-white mb-2">Our Blog</h2>
                  <p class="text-white text-center mb-4">Stay updated with the latest news and features about our app!</p>
                  <a :href="blogUrl" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                    Read Our Blog
                  </a>
                </div>
              </div>
    
              <div class="text-center">
                <p class="text-xl text-white mb-4">Join our vibrant community and stay connected!</p>
                <p class="text-white">Follow us on social media and our blog to get the latest updates, tips, and news about our app. Engage with fellow users, share your experiences, and be part of our growing family!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </app-layout>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  const props = defineProps({
    instagramUrl: {
      type: String,
      default: 'https://instagram.com/yourapp'
    },
    instagramHandle: {
      type: String,
      default: 'YourApp'
    },
    tiktokUrl: {
      type: String,
      default: 'https://tiktok.com/@yourapp'
    },
    tiktokHandle: {
      type: String,
      default: 'YourApp'
    },
    blogUrl: {
      type: String,
      required: true
    }
  });
  
  const asset = (path) => {
    return `/storage/${path}`;
  }
  </script>