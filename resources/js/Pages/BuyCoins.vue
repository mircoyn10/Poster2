<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import SectionTitle from '@/Components/SectionTitle.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
const cardData = [
  {
    price: 10,
    coins: 250,
    currency: 'Euro',
  },
  {
    price: 20,
    coins: 600,
    currency: 'Euro',
  },
  {
    price: 30,
    coins: 1000,
    currency: 'Euro',
  },
];
</script>

<template>
    <AppLayout>
        <Head title="Purchase Coins" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Get Coins
            </h2>
        </template>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Select Your Coin Package</h3>
                            <p class="mt-1 text-sm text-gray-500">Choose a package to purchase additional coins for sending prompts.</p>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <div class="sm:col-span-1">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">Available Packages</h3>
                                    </div>
                                </div>
                                <div class="bg-white">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 px-4 py-6 sm:px-6">
                                        <div v-for="card in cardData" :key="card.price" class="bg-white border border-gray-200 rounded-lg shadow-md">
                                            <div class="p-4">
                                                <h4 class="text-xl font-semibold text-gray-900">{{ card.price }} {{ card.currency }}</h4>
                                                <p class="mt-2 text-sm text-gray-600">For {{ card.coins }} coins</p>
                                            </div>
                                            <div class="border-t border-gray-200">
                                                <div class="flex justify-center p-4">
                                                    <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                                        Purchase
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </AppLayout>
</template>



