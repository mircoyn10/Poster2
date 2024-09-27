<template>
    <AppLayout>
      <Head title="Create Article" />
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Create a New Article</h2>
            <form @submit.prevent="submitArticle" enctype="multipart/form-data">
              <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input
                  v-model="article.title"
                  id="title"
                  type="text"
                  required
                  class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                />
              </div>
  
              <div class="mb-4">
                <label for="body" class="block text-sm font-medium text-gray-700">Body</label>
                <textarea
                  v-model="article.body"
                  id="body"
                  rows="5"
                  required
                  class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                ></textarea>
              </div>
  
              <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <input
                  @change="handleImage"
                  id="image"
                  type="file"
                  accept="image/*"
                  class="mt-1"
                />
              </div>
  
              <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-md"
                :disabled="isSubmitting"
              >
                {{ isSubmitting ? 'Submitting...' : 'Submit Article' }}
              </button>
            </form>
  
            <!-- Messaggio di errore -->
            <div v-if="errorMessage" class="mt-4 text-red-600">
              {{ errorMessage }}
            </div>
  
            <!-- Messaggio di successo -->
            <div v-if="successMessage" class="mt-4 text-green-600">
              {{ successMessage }}
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import { Head } from '@inertiajs/vue3';
  import AppLayout from '@/Layouts/AppLayout.vue';
  
  // Stato per il nuovo articolo
  const article = ref({
    title: '',
    body: '',
    image: null,
  });
  
  // Stato per il caricamento
  const isSubmitting = ref(false);
  
  // Stato per i messaggi
  const errorMessage = ref('');
  const successMessage = ref('');
  
  // Gestisci il file dell'immagine
  const handleImage = (event) => {
    article.value.image = event.target.files[0];
  };
  
  // Invia il nuovo articolo
  const submitArticle = async () => {
    console.log('Submitting article:', article.value);
    isSubmitting.value = true;
    errorMessage.value = '';
    successMessage.value = '';
  
    const formData = new FormData();
    formData.append('title', article.value.title);
    formData.append('body', article.value.body);
    if (article.value.image) {
      formData.append('image', article.value.image);
    }
  
    try {
      console.log('Sending request to server...');
      const response = await axios.post('/articles', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });
      console.log('Server response:', response);
  
      article.value = { title: '', body: '', image: null }; // Resetta il form
      successMessage.value = 'Article submitted successfully!';
    } catch (error) {
      console.error('Error submitting article:', error);
      if (error.response) {
        console.error('Server response:', error.response.data);
        errorMessage.value = error.response.data.message || 'An error occurred while submitting the article.';
      } else if (error.request) {
        console.error('No response received:', error.request);
        errorMessage.value = 'No response received from the server. Please try again.';
      } else {
        console.error('Error setting up request:', error.message);
        errorMessage.value = 'An error occurred while setting up the request. Please try again.';
      }
    } finally {
      isSubmitting.value = false;
    }
  };
  </script>
  
  <style scoped>
  /* Stili per il layout e il modulo */
  </style>