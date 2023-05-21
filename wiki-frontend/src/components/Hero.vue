<template>
  <section :class="{ 'dark': isDarkMode }" class="flex items-center h-screen pt-10 bg-gray-200 dark:bg-gray-800">
    <div class="container px-4 py-12 m-auto sm:py-16 lg:py-20">
      <div class="max-w-3xl mx-auto text-center">
        <h1 class="text-4xl font-extrabold text-gray-800 dark:text-white sm:text-5xl lg:text-6xl">Bienvenue sur notre site
        </h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 sm:text-xl lg:text-2xl">Lorem ipsum dolor sit amet,
          consectetur adipiscing
          elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <div class="mt-8">
          <RouterLink :to="{ name: 'login' }"
            class="px-6 py-3 font-medium text-white transition duration-300 ease-in-out bg-gray-800 rounded-md dark:bg-white hover:bg-gray-600 dark:hover:bg-gray-200">
            Commencer
          </RouterLink>
        </div>
      </div>
    </div>
  </section>
</template>
  
<script setup>
import { RouterLink } from 'vue-router';
</script>
  
<style></style>