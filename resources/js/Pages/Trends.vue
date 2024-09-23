<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';

const trends = ref([]);
const error = ref(null);
const country = 'JP'; // Puoi cambiare questo valore dinamicamente se vuoi

// Funzione per recuperare i trend
const fetchTrends = async () => {
    try {
        const response = await axios.get(`/trends/${country}`);
        trends.value = response.data.trends;
    } catch (err) {
        error.value = 'Non è possibile ottenere i trend al momento.';
    }
};

// Carica i trend quando il componente viene montato
onMounted(() => {
    fetchTrends();
});
</script>

<template>
    <div>
        <Head title="Google Trends" />
        <h1 class="text-3xl font-bold">Trending Searches in {{ country }}</h1>

        <div v-if="error">
            <p class="text-red-500">{{ error }}</p>
        </div>

        <div v-else>
            <ul v-if="trends.length > 0">
                <li v-for="(trend, index) in trends" :key="index">
                    {{ trend }}
                </li>
            </ul>
            <p v-else>Nessun trend disponibile per il paese selezionato.</p>
        </div>
    </div>
</template>

<style scoped>
h1 {
  margin-bottom: 20px;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  margin-bottom: 10px;
}
</style>
