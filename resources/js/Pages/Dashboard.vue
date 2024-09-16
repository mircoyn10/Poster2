<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Chatbox.vue';
import Chatbox from '@/Components/Chatbox.vue';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';






// Configura Axios per utilizzare i token e le sessioni

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Chatbox />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
