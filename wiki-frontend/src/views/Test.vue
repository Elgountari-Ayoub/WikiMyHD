<template>
    <button @click="toggleSwitch" :class="{'bg-green-500': isOn, 'bg-red-500': !isOn}" class="relative w-16 h-8  px-1 rounded-full transition-colors duration-300">
      <span :class="{'translate-x-8': isOn, 'translate-x-0': !isOn}" class="block w-6 h-6 rounded-full bg-white shadow-md transform transition-transform duration-300"></span>
      <span class="absolute inset-0 flex items-center justify-center font-semibold text-white z-10">
        <span v-if="isOn" class="mr-auto ml-1">On</span>
        <span v-else class="ml-auto mr-1">Off</span>
      </span>
    </button>

    
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const isOn = ref(false);
  
  const toggleSwitch = () => {
    isOn.value = !isOn.value;
  }
  </script>
  
  <style scoped>
  button:focus {
    outline: none;
  }
  </style>
  