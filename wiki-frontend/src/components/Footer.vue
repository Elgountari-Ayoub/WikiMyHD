<template>
    <footer class="bg-gray-100 absolute bottom-0 w-full">
        <div class="container px-4 py-2 mx-auto sm:py-2 lg:py-8">
            <div class="text-center text-gray-600 ">
                <p>&copy; 2023 WikiMyHd. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>
  
  
  
  
  
<script setup>
import { ref } from 'vue';

const currentYear = ref(new Date().getFullYear());
</script>
  
<style>
/* Add your custom styles here if needed */
</style>
  