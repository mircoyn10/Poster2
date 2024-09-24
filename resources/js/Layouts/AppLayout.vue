<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCoins } from '@fortawesome/free-solid-svg-icons';
let timeout;

const closeDashboardMenu = () => {
    timeout = setTimeout(() => {
        isDashboardMenuOpen.value = false;
    }, 200); // Aggiungi un delay di 200 ms
};

const openDashboardMenu = () => {
    clearTimeout(timeout);
    isDashboardMenuOpen.value = true;
};


const showingNavigationDropdown = ref(false);
const isDashboardMenuOpen = ref(false); // Gestione del sottomenu Dashboard

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    });
};

const logout = () => {
    router.post(route('logout'));
};

// Funzione per gestire l'apertura del sottomenu Dashboard


// Funzione per alternare lo stato dell'hamburger
const toggleNavigationDropdown = () => {
    showingNavigationDropdown.value = !showingNavigationDropdown.value;
};
</script>

<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-gray-100">
            <!-- Navbar -->
            <nav class="bg-white shadow-md border-b border-gray-200">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <Link :href="route('dashboard')" class="flex items-center">
                            <ApplicationMark class="h-9 w-auto" />
                        </Link>

                        <!-- Primary Navigation -->
                        <div class="hidden sm:flex space-x-8 ml-10">
                            <!-- Dashboard con sottomenu -->
                           <!-- Dashboard con sottomenu -->
                        <div 
                            @mouseenter="openDashboardMenu" 
                            @mouseleave="closeDashboardMenu" 
                            class="relative"
                        >
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </NavLink>

                            <!-- Sottomenu per la dashboard -->
                            <div 
                                v-if="isDashboardMenuOpen" 
                                class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 transition-opacity duration-300"
                                @mouseenter="openDashboardMenu" 
                                @mouseleave="closeDashboardMenu"
                            >
                                
                                <Link href="/trends" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Trends</Link>
                            </div>
                        </div>


                            <!-- Buy Coins -->
                            <NavLink :href="route('buy-coins')" :active="route().current('buy-coins')">
                                <FontAwesomeIcon :icon="faCoins" class="mr-2" />
                                Buy Coins
                            </NavLink>
                        </div>
                    </div>

                    <!-- Settings Dropdown e Profilo Utente -->
                    <div class="hidden sm:flex items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none">
                                    <img class="h-8 w-8 rounded-full" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.show')">Profile</DropdownLink>
                                <form @submit.prevent="logout">
                                    <DropdownLink as="button">Log Out</DropdownLink>
                                </form>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Hamburger per il mobile -->
                    <div class="sm:hidden">
                        <button @click="toggleNavigationDropdown" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path v-if="!showingNavigationDropdown" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                <path v-else d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Menu responsive -->
                <div v-if="showingNavigationDropdown" class="sm:hidden transition-opacity duration-300 ease-in-out">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('buy-coins')" :active="route().current('buy-coins')" class="hover:bg-yellow-100 text-yellow-700">
                            <FontAwesomeIcon :icon="faCoins" class="mr-2" />
                            Buy Coins
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                            </div>

                            <div>
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                Profile
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                API Tokens
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Header -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Main Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.navbar {
    background-color: #fff;
}

nav {
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

button {
    transition: background-color 0.2s ease, color 0.2s ease;
}

button:hover {
    background-color: #f3f4f6;
    color: #374151;
}
</style>


<style scoped>
.container {
    max-width: 1024px;
    margin: 0 auto;
}

nav {
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

button {
    transition: background-color 0.2s ease, color 0.2s ease;
}

button:hover {
    background-color: #f3f4f6;
    color: #374151;
}
</style>


<style scoped>
.container {
    max-width: 1024px;
    margin: 0 auto;
}

nav {
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

button {
    transition: background-color 0.2s ease, color 0.2s ease;
}

button:hover {
    background-color: #f3f4f6;
    color: #374151;
}
</style>


<style scoped>
.container {
    max-width: 1024px;
    margin: 0 auto;
}

nav {
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

button {
    transition: background-color 0.2s ease, color 0.2s ease;
}

button:hover {
    background-color: #f3f4f6;
    color: #374151;
}
</style>


<style scoped>
.container {
    max-width: 1024px;
    margin: 0 auto;
}

nav {
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

button {
    transition: background-color 0.2s ease, color 0.2s ease;
}

button:hover {
    background-color: #f3f4f6;
    color: #374151;
}
</style>
