<template>
  <div>
    <Layout>
      <Hero/>
    </Layout>
  </div>
</template>

<script setup>
import Layout from '../components/layouts/Layout.vue'
import Nav from '../components/Nav.vue';
import Hero from '../components/Hero.vue';


import { ref } from 'vue';
import axios from 'axios';

axios.defaults.withCredentials = true;
const isAuthenticated = ref(false);

async function checkAuthStatus() {
  try {
    const response = await axios.get('http://localhost:8000/api/auth-status');
    isAuthenticated.value = response.data.authenticated;
  } catch (error) {
    console.error('Failed to fetch authentication status:', error);
  }
}

// Call the function when the component is mounted
checkAuthStatus();
</script>
<style lang="scss" scoped>

</style>