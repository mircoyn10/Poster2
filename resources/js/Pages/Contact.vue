<template>
    <AppLayout>
      <Head title="Contact Us" />
  
      <!-- Header Section -->
      <nav class="bg-white shadow-md w-full px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
          <h1 class="text-2xl font-bold text-gray-800">Contact Us</h1>
        </div>
      </nav>
  
      <div class="bg-white text-gray-900 min-h-screen relative flex flex-col items-center justify-center transition-all duration-500">
        <!-- Background with light opacity -->
        <img
          id="background"
          class="absolute inset-0 object-cover w-full h-full transition-opacity duration-500"
          src="http://127.0.0.1:8000/storage/img/minimalist_back_3.jpg"
          alt="background"
        />
  
        <!-- Main container with transparency and elegance -->
        <div class="relative z-10 max-w-5xl w-full mx-auto p-10 rounded-lg shadow-2xl bg-white bg-opacity-30 transition-all duration-500 backdrop-filter mt-8">
          <h2 class="text-3xl font-bold text-center mb-8 text-[#00A3E0]">Get in Touch</h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Bubble for General Inquiries -->
            <div class="contact-bubble">
              <h3 class="text-lg font-semibold">General Inquiries</h3>
              <p class="text-gray-700">For general questions, feel free to reach out to us.</p>
              <Link href="mailto:info@yourcompany.com" class="text-blue-500 hover:underline">info@yourcompany.com</Link>
            </div>
  
            <!-- Bubble for Partnership Offers -->
            <div class="contact-bubble">
              <h3 class="text-lg font-semibold">Partnership Offers</h3>
              <p class="text-gray-700">We are open to partnership opportunities. Let's collaborate!</p>
              <Link href="mailto:partnerships@yourcompany.com" class="text-blue-500 hover:underline">partnerships@yourcompany.com</Link>
            </div>
  
            <!-- Bubble for Feedback -->
            <div class="contact-bubble">
              <h3 class="text-lg font-semibold">Feedback</h3>
              <p class="text-gray-700">Your feedback is important to us. Share your thoughts!</p>
              <Link href="mailto:feedback@yourcompany.com" class="text-blue-500 hover:underline">feedback@yourcompany.com</Link>
            </div>
          </div>
  
          <!-- Final Bubble for Contact Email -->
          <div class="mt-10 contact-bubble-large">
            <h3 class="text-xl font-semibold">For Any Information</h3>
            <p class="text-gray-700">We are open to all kinds of partnership offers and collaborations.</p>
            <p class="text-lg font-bold mt-4">Reach out to us at:</p>
            <Link href="mailto:contact@yourcompany.com" class="text-blue-500 hover:underline text-lg">contact@yourcompany.com</Link>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { Head, Link } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  </script>
  
  <style scoped>
  .contact-bubble {
    padding: 1.5rem;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.5s ease, transform 0.3s ease;
    text-align: center;
    position: relative;
  }
  
  .contact-bubble:hover {
    background-color: rgba(255, 255, 255, 1);
    transform: translateY(-5px);
  }
  
  .contact-bubble-large {
    padding: 2rem;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.5s ease, transform 0.3s ease;
    text-align: center;
    position: relative;
    margin-top: 2rem;
  }
  
  img {
    margin-bottom: 1rem;
  }
  </style>
  