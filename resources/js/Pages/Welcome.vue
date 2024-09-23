<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://127.0.0.1:8000';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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
</script>

<template>
    <Head title="Welcome to Poster IA" />
    <div class="bg-white text-gray-900 min-h-screen  relative flex items-center justify-center transition-all duration-500">
        <!-- Background with light opacity -->
        <img
            id="background"
            class="absolute inset-0 object-cover w-full h-full opacity-70 transition-opacity duration-500"
            src="http://127.0.0.1:8000/storage/img/background_welcome.jpg"
            alt="background"
        />
        
        <!-- Main container with transparency and elegance -->
        <div class=" relative z-10 max-w-4xl w-full mx-auto p-10 rounded-lg shadow-2xl bg-white bg-opacity-10 transition-all duration-500 backdrop-filter backdrop-blur-lg">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold text-[#00A3E0] mb-4 shadow-md">Welcome to Poster IA</h1>
                <p class="text-lg text-gray-700 leading-relaxed shadow-md">
                    Generate ideas for stunning social media content with ease. Poster AI simplifies content creation and scheduling, keeping you up to date with the latest features.
                </p>
            </div>

            <!-- Detailed Bubbles -->
            <div class="flex flex-col md:flex-row justify-center gap-6">
                <div class="bubble">
                    <h2 class="text-2xl font-bold mb-2 text-[#00A3E0] shadow-md">Streamlined Content Creation</h2>
                    <p class="text-gray-700 shadow-md">
                        Poster AI assists you in creating fresh content ideas for social media, making content creation fast and intuitive.
                    </p>
                </div>
                <div class="bubble">
                    <h2 class="text-2xl font-bold mb-2 text-[#00A3E0] shadow-md">Scheduling Made Simple</h2>
                    <p class="text-gray-700 shadow-md">
                        Schedule your content effortlessly. Plan and organize your social media calendar with a few clicks.
                    </p>
                </div>
                <div class="bubble">
                    <h2 class="text-2xl font-bold mb-2 text-[#00A3E0] shadow-md">Continuous Updates</h2>
                    <p class="text-gray-700 shadow-md">
                        Stay ahead with regular feature updates designed to make life easier for content creators. New tools and AI-powered improvements are always around the corner.
                    </p>
                </div>
            </div>

            <!-- Social login buttons in a coherent layout -->
            <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4 justify-center mt-8">
                <Link
                    href="/auth/google"
                    class="flex items-center justify-center bg-white border border-[#00A3E0] text-[#00A3E0] py-3 px-6 rounded-lg shadow-lg hover:bg-[#00A3E0] hover:text-white transition-all duration-300 w-full md:w-auto"
                >
                    <img
                        src="https://img.icons8.com/color/24/000000/google-logo.png"
                        alt="Google"
                        class="mr-2"
                    />
                    Continue with Google
                </Link>

                <Link
                    href="/auth/facebook"
                    class="flex items-center justify-center bg-white border border-[#00A3E0] text-[#00A3E0] py-3 px-6 rounded-lg shadow-lg hover:bg-[#00A3E0] hover:text-white transition-all duration-300 w-full md:w-auto"
                >
                    <img
                        src="https://img.icons8.com/color/24/000000/facebook-new.png"
                        alt="Facebook"
                        class="mr-2"
                    />
                    Continue with Facebook
                </Link>
            </div>

            <!-- Login and Register buttons -->
            <div class="flex space-x-4 justify-center mt-6">
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="block bg-[#00A3E0] text-white py-2 px-6 rounded-lg shadow-lg hover:bg-[#0079B2] transition-all duration-300"
                >
                    Log in
                </Link>
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="block bg-[#00A3E0] text-white py-2 px-6 rounded-lg shadow-lg hover:bg-[#0079B2] transition-all duration-300"
                >
                    Register
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
body {
    transition: background-color 0.5s ease;
}
.bubble {
    padding: 1.5rem;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.5s ease;
}
.bubble:hover {
    background-color: rgba(255, 255, 255, 1);
}
h1, h2, p {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}
@media (max-width: 768px) {
    .bubble {
        margin-bottom: 1rem;
    }
}
</style>
