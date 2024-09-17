<template>
    <div class="flex items-center">
      <!-- Logo con percorso assoluto -->
      <img src="http://127.0.0.1:8000/storage/img/PosterLogo2.png" alt="Poster Logo"  />
    </div>
  </template>
  
  
  
  <script>
  export default {
    computed: {
      logoUrl() {
        // Usa l'helper asset() per generare correttamente l'URL
        return '/storage/app/public/img/PosterLogo.png';
      }
    }
  }
  </script>
  
  <style scoped>
  /* Puoi personalizzare lo stile per l'immagine, se necessario */
  img {
    max-height: 5rem; /* Limita l'altezza a 3rem */
  }
  </style>
  
