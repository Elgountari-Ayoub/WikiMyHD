<template>
    <nav class="fixed w-full bg-white shadow ">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <RouterLink :to="{ name: 'home' }" class="flex-shrink-0 text-lg">
                    <!-- <img class="w-8 h-8" src="logo.png" alt="Logo"> -->
                    <!-- <p>WikiMyHD</p> -->
                    WikiMyHD
                </RouterLink>
                <div class="hidden md:block">
                    <div class="flex items-baseline ml-10 space-x-4">
                        <a href="#"
                            class="px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover:text-gray-900">Home</a>
                        <a href="#"
                            class="px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover:text-gray-900">About</a>
                        <a href="#"
                            class="px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover:text-gray-900">Contact</a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="flex items-center ml-4 md:ml-6">
                        <!-- Replace the following with your register and login buttons -->
                        <RouterLink :to="{ name: 'register' }">
                            <button
                                class="px-3 py-2 ml-3 text-sm font-medium text-gray-700 bg-white rounded-md">Register</button>
                        </RouterLink>
                        <RouterLink :to="{ name: 'login' }">
                            <button
                                class="px-3 py-2 ml-3 text-sm font-medium text-gray-700 bg-white rounded-md">Login</button>
                        </RouterLink>
                    </div>
                </div>
                <div class="flex -mr-2 md:hidden">
                    <button
                        class="inline-flex items-center justify-center p-2 text-gray-600 bg-white rounded-md hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white"
                        @click="isMenuOpen = !isMenuOpen">
                        <span class="sr-only">Open main menu</span>
                        <svg :class="{ 'hidden': isMenuOpen, 'block': !isMenuOpen }" class="w-6 h-6"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg :class="{ 'hidden': !isMenuOpen, 'block': isMenuOpen }" class="w-6 h-6"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div :class="{ 'block': isMenuOpen, 'hidden': !isMenuOpen }" class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#"
                    class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 hover:text-gray-900">Home</a>
                <a href="#"
                    class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 hover:text-gray-900">About</a>
                <a href="#"
                    class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 hover:text-gray-900">Contact</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <!-- <img class="w-10 h-10" src="logo.png" alt="Logo"> -->
                        user photo
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-700">Your Name</div>
                        <div class="text-sm font-medium text-gray-500">youremail@example.com</div>
                    </div>
                </div>
                <div class="px-2 mt-3 space-y-1">
                    <a href="#"
                        class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-gray-900 hover:bg-gray-100">Profile</a>
                    <a href="#"
                        class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-gray-900 hover:bg-gray-100">Settings</a>
                    <a href="#"
                        class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:text-gray-900 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</template>
  
<script setup>
import { ref } from 'vue';
import { RouterLink } from 'vue-router';

const isMenuOpen = ref(false);
</script>
  
<style>
/* Add your custom styles here if needed */
</style>
  