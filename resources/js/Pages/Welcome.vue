<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img id="background" class="absolute inset-0 object-cover w-full h-full opacity-30" src="http://127.0.0.1:8000/storage/img/background_welcome.jpg" />
        <div class="relative min-h-screen flex items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative bg-gray-100 dark:bg-gray-700 p-8 rounded-lg shadow-lg w-full max-w-md text-center">
                <h1 class="text-3xl font-bold text-[#00A3E0] dark:text-[#00A3E0] mb-4">Welcome to Poster IA</h1>
                <img src="http://127.0.0.1:8000/storage/img/PosterLogo2.png" alt="Poster Logo" class="mx-auto mb-4" style="max-height: 5rem;" />
                <p class="text-gray-800 dark:text-gray-200 mb-4">
                    Create captivating social media content effortlessly with Poster IA. Our AI-powered tool helps you generate engaging posts and boost your social media presence like never before!
                </p>
                <p class="text-gray-800 dark:text-gray-200 mb-6">
                    Ready to supercharge your social media strategy? Join us now!
                </p>
                <div class="flex space-x-4 justify-center">
                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="block bg-white text-black py-2 px-4 rounded-md shadow-sm transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FF2D20]"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="block bg-white text-black py-2 px-4 rounded-md shadow-sm transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#FF2D20]"
                    >
                        Register
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
