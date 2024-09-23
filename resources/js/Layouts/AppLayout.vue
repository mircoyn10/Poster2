<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCoins } from '@fortawesome/free-solid-svg-icons';

const showingNavigationDropdown = ref(false);
const isDashboardMenuOpen = ref(false); // Gestione del sottomenu per "Dashboard"

// Funzione per cambiare il team
const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

// Funzione di logout
const logout = () => {
    router.post(route('logout'));
};

// Funzione per gestire l'apertura del sottomenu Dashboard
const openDashboardMenu = () => {
    isDashboardMenuOpen.value = true;
};

// Funzione per chiudere il sottomenu Dashboard
const closeDashboardMenu = () => {
    isDashboardMenuOpen.value = false;
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <Link :href="route('dashboard')" class="flex items-center">
                            <ApplicationMark class="h-9 w-auto" />
                        </Link>

                        <!-- Navigation Links -->
                        <div class="hidden sm:flex space-x-8 ml-10">
                            <!-- Dashboard with submenu -->
                            <div 
                                @mouseover="toggleDashboardMenu" 
                                @mouseleave="toggleDashboardMenu" 
                                class="relative"
                            >
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>

                                <div v-if="isDashboardMenuOpen" 
                                    class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
            >
                                    <Link href="/calendar" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Calendar</Link>
                                    <Link href="/trends" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Trends</Link>
                                </div>
                            </div>

                            <!-- Buy Coins without submenu -->
                            <NavLink :href="route('buy-coins')" :active="route().current('buy-coins')">
                                <FontAwesomeIcon :icon="faCoins" class="mr-2" />
                                Buy Coins
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center">
                        <!-- Settings Dropdown -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none">
                                    <img class="h-8 w-8 rounded-full" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                </button>
                            </template>

                            <template #content>
                                <div class="block px-4 py-2 text-xs text-gray-400">Manage Account</div>
                                <DropdownLink :href="route('profile.show')">Profile</DropdownLink>
                                <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">API Tokens</DropdownLink>
                                <div class="border-t border-gray-200"></div>
                                <form @submit.prevent="logout">
                                    <DropdownLink as="button">Log Out</DropdownLink>
                                </form>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Hamburger -->
                    <div class="sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path v-if="!showingNavigationDropdown" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                <path v-else d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div v-if="showingNavigationDropdown" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('buy-coins')" :active="route().current('buy-coins')">
                            <FontAwesomeIcon :icon="faCoins" class="mr-2" />
                            Buy Coins
                        </ResponsiveNavLink>
                    </div>

                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <img class="h-10 w-10 rounded-full" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                            <div class="ml-3">
                                <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')">Profile</ResponsiveNavLink>
                            <form @submit.prevent="logout">
                                <ResponsiveNavLink as="button">Log Out</ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <header v-if="$slots.header" class="bg-white shadow">
                <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 1024px;
    margin: 0 auto;
}
</style>
