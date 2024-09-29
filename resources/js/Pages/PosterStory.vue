<template>
    <AppLayout>
      <Head title="Poster Story" />
  
      <nav class="bg-white shadow-md w-full px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
          <h1 class="text-2xl font-bold text-gray-800">Poster Story</h1>
        </div>
      </nav>
  
      <div class="bg-white text-gray-900 min-h-screen relative flex flex-col items-center justify-center transition-all duration-500">
        <img
          id="background"
          class="absolute inset-0 object-cover w-full h-full transition-opacity duration-500"
          src="http://127.0.0.1:8000/storage/img/minimalist_back_3.jpg"
          alt="background"
        />
  
        <div class="relative z-10 max-w-5xl w-full mx-auto p-10 rounded-lg shadow-2xl bg-white bg-opacity-30 transition-all duration-500 backdrop-filter mt-8">
          <h2 class="text-3xl font-bold mb-6 text-[#00A3E0]">The Journey of Poster</h2>
          <p class="text-gray-700 mb-4">
            **The Beginning: A Vision of Simplicity**
          </p>
          <p class="text-gray-700 mb-4">
            Poster was born out of a simple idea: to empower content creators with a user-friendly tool that streamlined the process of generating engaging social media content. Initially conceived as a single-page web application, the goal was straightforward—help users quickly craft posts without the complexities that often bog down creativity.
          </p>
  
          <p class="text-gray-700 mb-4">
            **Recognizing the Potential**
          </p>
          <p class="text-gray-700 mb-4">
            As development progressed, our team began to recognize the immense potential that lay within this simple application. We listened to our users, gathering feedback and insights, and it became clear that we were on the verge of something much bigger. Content creators across various platforms were looking for an all-in-one solution that not only simplified the content creation process but also provided them with the tools and resources necessary to thrive in a competitive landscape.
          </p>
  
          <p class="text-gray-700 mb-4">
            **Evolution into a Comprehensive Suite**
          </p>
          <p class="text-gray-700 mb-4">
            With this newfound understanding, we embarked on a journey of transformation. Our single-page app evolved into a complete suite designed specifically for content creators. We meticulously refined our features, focusing on key areas such as content generation, scheduling, SEO optimization, and more. Each tool we added was thoughtfully crafted to address the real needs of our users, ensuring that every aspect of Poster was intuitive and effective.
          </p>
  
          <p class="text-gray-700 mb-4">
            **User-Centric Design**
          </p>
          <p class="text-gray-700 mb-4">
            Our commitment to user experience became the cornerstone of Poster. We implemented a user-centric design philosophy, making the interface not only visually appealing but also incredibly easy to navigate. We understood that content creation should be a seamless and enjoyable process, allowing creators to focus on their craft rather than getting lost in complicated settings.
          </p>
  
          <p class="text-gray-700 mb-4">
            **Empowering Creators Worldwide**
          </p>
          <p class="text-gray-700 mb-4">
            Today, Poster stands as a testament to our dedication and hard work. We have built a platform that empowers content creators from all walks of life, providing them with the tools to bring their ideas to life. Whether you're a seasoned professional or just starting, Poster is designed to meet your needs and help you succeed.
          </p>
  
          <p class="text-gray-700 mb-4">
            **Open to Partnerships and Collaboration**
          </p>
          <p class="text-gray-700 mb-4">
            As we continue to grow and innovate, we remain open to partnerships and collaborations. We believe in the power of community and the importance of working together to create something truly remarkable. If you share our vision of empowering creators, we invite you to reach out and explore the possibilities.
          </p>
  
          <p class="text-gray-700 mb-4">
            **The Journey Continues**
          </p>
          <p class="text-gray-700 mb-4">
            The journey of Poster is far from over. With each update and new feature, we strive to enhance the experience for our users and expand the capabilities of our platform. Join us as we continue to shape the future of content creation—together, we can achieve greatness.
          </p>
        </div>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { Head } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  </script>
  
  <style scoped>
  body {
    transition: background-color 0.5s ease;
  }
  
  h1, h2, p {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }
  </style>
  