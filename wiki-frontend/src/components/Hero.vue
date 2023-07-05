<template>
  <section class="flex items-center h-full pt-10 bg-gray-200 dark:bg-gray-800">
    <div class="container px-4 py-12 m-auto sm:py-16 lg:py-20">
      <div class=" mx-auto text-center mt-24">
        <h1
          class="font-extrabold text-gray-800 transition duration-500 ease-in-out hover:text-blue-600 text-3xl sm:text-4xl md:text-5xl lg:text-6xl">
          <p>Bienvenue sur notre site!</p>
          Explorez et découvrez le monde de la connaissance.
        </h1>
        <p
          class="mt-10  text-gray-600 transition duration-500 ease-in-out hover:text-blue-700 text-base sm:text-sm md:text-md lg:text-lg">
        <p><strong>Wiki My Hd</strong> est une application de
          wiki puissante créée par HavetDigital.</p>

        <p><strong>Wiki My Hd</strong>est un type de logiciel qui permet aux utilisateurs de créer, d'organiser et de
          modifier en collaboration du
          contenu en ligne.</p>
        <p>C'est un outil qui permet aux entreprises, aux équipes et aux communautés de créer un
          centre de
          connaissances partagé pour les sujets qui les concernent.</p>
        </p>
        <div class="mt-8">
          <RouterLink :to="{ name: 'login' }"
            class="px-6 py-3 font-medium text-white transition duration-300 ease-in-out bg-gray-800 rounded-md dark:bg-white hover:bg-gray-600 hover:text-white dark:hover:bg-gray-200">
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