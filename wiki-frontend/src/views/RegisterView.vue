<template>
  <Nav />
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-6 mx-auto mt-16 bg-white rounded-md shadow">
      <h1 class="mb-6 text-3xl font-bold">Register</h1>
      <form @submit.prevent="register">

        <div class="mb-4">
          <TextInput label="Nom"  placeholder="Elgountari Ayoub" v-model:input="name" inputType="text"
            error="This name error" />
        </div>
        
        <div class="mb-4">
          <TextInput label="Email"  placeholder="elgountariayoub22@gmai.com" v-model:input="email"
            inputType="email" error="This is email error" />
        </div>
        <div class="mb-4">
          <TextInput label="Mot de passe"  v-model:input="password" inputType="password"
            error="This is mot de passe error " />
        </div>
        <div class="mb-4">
          <TextInput label="Confiramtion de mot de passe"  v-model:input="password_confirmation"
            inputType="text" error="This is confirmations mot de passe error" />
        </div>
        <div class="mb-4">
          <TextInput label="Post"  placeholder="Web Developer" v-model:input="post"
            inputType="text" error="This is post error" />
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
import TextInput from '../components/global/TextInput.vue';


axios.defaults.withCredentials = true;

const name = ref(null)
const email = ref(null)
const password = ref(null)
const password_confirmation = ref(null)
const post = ref(null)


const router = useRouter()
async function register() {
  try {
    // Get CSRF token from Laravel
    const csrf = await axios.get('http://localhost:8000/sanctum/csrf-cookie');

    const response = await axios.post('http://localhost:8000/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
      post: post.value,
    });

    console.log('Registration successful:', response.data);

    // Clear form fields
    name.value = null;
    email.value = null;
    password.value = null;
    password_confirmation.value = null;
    post.value = null;

    // Show Success Message
    Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: 'Votre demande à été envoyé',
      showConfirmButton: false,
      timer: 1500
    })

    // send a notification to the admin to approve this new user
    // code ...

    
  } catch (error) {
    console.error('Registration failed:', error);
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Quelque chose s\'est mal passé !',
    })
  }
};

// async function handlePhotoChange(event) {
//   const file = event.target.files[0];
//   form.value.photo = file;
// }
</script>
  
<style>
</style>
  