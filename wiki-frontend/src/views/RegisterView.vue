<template>
  <Nav />
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-6 mx-auto mt-16 bg-white rounded-md shadow">
      <h1 class="mb-6 text-3xl font-bold">Register</h1>
      <form @submit.prevent="register">
        <div class="mb-4">
          <label for="name" class="block text-gray-700">Name:</label>
          <input type="text" id="name" v-model="form.name" required
            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div class="mb-4">
          <label for="email" class="block text-gray-700">Email:</label>
          <input type="email" id="email" v-model="form.email" required
            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div class="mb-6">
          <label for="password" class="block text-gray-700">Password:</label>
          <input type="password" id="password" v-model="form.password" required
            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div class="mb-6">
          <label for="password_confirmation" class="block text-gray-700">Password Confirmation:</label>
          <input type="password" id="password_confirmation" v-model="form.password_confirmation" required
            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div class="mb-6">
          <label for="photo" class="block text-gray-700">Photo:</label>
          <input type="file" id="photo" @change="handlePhotoChange" ref="photoInput"
            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div class="mb-6">
          <label for="post" class="block text-gray-700">Post:</label>
          <input type="text" id="post" v-model="form.post" required
            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
            Register
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
  
<script setup>
import Nav from '../components/Nav.vue';

import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';


axios.defaults.withCredentials = true;

const form = ref({
  name: null,
  email: null,
  password: null,
  password_confirmation: null,
  photo: null,
  post: null,
});


const router = useRouter()

async function register() {
  try {
    // this.$swal('Hello Vue world!!!');



    // Get CSRF token from Laravel
    const csrf = await axios.get('http://localhost:8000/sanctum/csrf-cookie');

    const response = await axios.post('http://localhost:8000/register', {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
      photo: form.value.photo,
      post: form.value.post,
    }, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    console.log('Registration successful:', response.data);

    // Clear form fields
    form.value.name = '';
    form.value.email = '';
    form.value.password = '';
    form.value.photo = null;
    form.value.post = null;

    Swal.fire({
      // position: 'top-end',
      icon: 'success',
      title: 'Votre demande à été envoyé',
      showConfirmButton: false,
      timer: 1500
    })

    // router.push('/dashboard');
  } catch (error) {
    console.error('Registration failed:', error);
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Quelque chose s\'est mal passé !',
      // footer: '<a href="">Why do I have this issue?</a>'
    })
  }
};

async function handlePhotoChange(event) {
  const file = event.target.files[0];
  form.value.photo = file;
}
</script>
  
<style>
/* You can add additional styles here if needed */
</style>
  