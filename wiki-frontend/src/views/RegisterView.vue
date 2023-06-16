<template>
  <Nav />
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-6 mx-auto mt-16 bg-white rounded-md shadow">
      <h1 class="mb-6 text-3xl font-bold">Enregistrer</h1>
      <form @submit.prevent="register">

        <div class="mb-4">
          <TextInput label="Nom" placeholder="Elgountari Ayoub" v-model:input="name" inputType="text"
            :error="errors.name ? errors.name[0] : ''" />
        </div>

        <div class="mb-4">
          <TextInput label="Email" placeholder="elgountariayoub22@gmai.com" v-model:input="email" inputType="email"
            :error="errors.email ? errors.email[0] : ''" />
        </div>
        <div class="mb-4">
          <TextInput label="Mot de passe" v-model:input="password" inputType="password"
            :error="errors.password ? errors.password[0] : ''" />
        </div>
        <div class="mb-4">
          <TextInput label="Confiramtion de mot de passe" v-model:input="password_confirmation" inputType="password"
            :error="errors.password ? errors.password[0] : ''" />

        </div>
        <div class="mb-4">
          <TextInput label="Post" placeholder="Web Developer" v-model:input="post" inputType="text"
            :error="errors.post ? errors.post[0] : ''" />
        </div>
        <div>
          <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
            Enregistrer
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
import { useUserStore } from '../stores/user-store'


axios.defaults.withCredentials = true;

const name = ref(null)
const email = ref(null)
const password = ref(null)
const password_confirmation = ref(null)
const post = ref(null)

const errors = ref([]);
const userStore = useUserStore();


const router = useRouter()
async function register() {
  try {
    // Get CSRF token from Laravel
    const csrf = await axios.get('/sanctum/csrf-cookie');

    errors.value = [];
    console.log("this is the empty errors array\n", errors.value);
    const res = await axios.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
      post: post.value,
    });
    // Clear form fields
    name.value = null;
    email.value = null;
    password.value = null;
    password_confirmation.value = null;
    post.value = null;

    // Show Success Message
    console.log('Registration successful:', res.data);
    Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: 'Votre demande à été envoyé',
      showConfirmButton: false,
      timer: 1500
    })

    // empty the errors array
    errors.value = [];

    // send a notification to the admin to approve this new user
    // code ...

  } catch (err) {
    errors.value = err.response.data.errors;
    console.error('Registration failed:', err);
    if (errors.lenght === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Quelque chose s\'est mal passé !',
      })
    }

  }
};

// async function handlePhotoChange(event) {
//   const file = event.target.files[0];
//   form.value.photo = file;
// }
</script>
  
<style></style>
  