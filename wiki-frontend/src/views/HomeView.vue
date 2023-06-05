<template>
  <div>
    <Layout>
      <Hero />
    </Layout>
  </div>
</template>

<script setup>
import Layout from '../components/layouts/Layout.vue'
import Nav from '../components/Nav.vue';
import Hero from '../components/Hero.vue';


import { onMounted, ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

axios.defaults.withCredentials = true;
const isAuthenticated = ref(false);

onMounted(() => {
})


const router = useRouter();
async function checkAuthStatus() {
  try {
    const response = await axios.get('/api/auth-status');
    console.log('we have a user logged in =>', response.data.res);
    isAuthenticated.value = response.data.authenticated;
    if(response){
      // router.push({name : 'profileSection'})
    }
  } catch (error) {
    // console.error('Failed to get, authentication status:', error);
  }
}

// Call the function when the component is mounted
checkAuthStatus();
</script>
<style lang="scss" scoped></style>