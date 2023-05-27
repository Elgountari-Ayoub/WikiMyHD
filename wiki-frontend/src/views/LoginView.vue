<template>
    <Nav />

    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-6 mx-auto mt-16 bg-white rounded-md shadow ms:px-16">
            <!-- {{ user }} -->
            <h1 class="mb-6 text-3xl font-bold">Login</h1>
            <form @submit.prevent="login">
                <div class="mb-4">
                    <TextInput label="Email" inputType="email" placeholder="elgountariayoub22@gmai.com"
                        v-model:input="email" :error="errors.email ? errors.email[0] : ''" />
                </div>
                <div class="mb-4">
                    <TextInput label="Mot de passe" v-model:input="password" inputType="password"
                        :error="errors.password ? errors.password[0] : ''" />
                </div>
                <div>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Login</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Nav from '../components/Nav.vue';
import TextInput from '../components/global/TextInput.vue';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';
import { useUserStore } from '../stores/user-store'


axios.defaults.withCredentials = true;

const userStore = useUserStore();

const email = ref(null);
const password = ref(null);
const user = ref();


const errors = ref([]);

const router = useRouter();

async function login() {
    try {
        errors.value = []
        // Get CSRF token from Laravel
        await axios.get('http://localhost:8000/sanctum/csrf-cookie');

        const response = await axios.post('http://localhost:8000/login', {
            email: email.value,
            password: password.value,
        });

        if (response.data.status === 1) {
            // save it in a store
            {
                userStore.setUserDetails(response);
            }
            
            // Show Success Message
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'connexion réussie',
                showConfirmButton: false,
                timer: 1000
            })
            router.push({ name: 'profileSection' }) 
        }
        else {
            Swal.fire({
                icon: 'warning',
                // title: 'connexion réussie',
                text: 'Votre candidature n\'a pas encore été approuvée',
                showConfirmButton: false,
                timer: 3000
            })
        }

        // check if the authnticated user has the right to visiti the website
    } catch (err) {
        errors.value = err.response.data.errors;
        console.error('login failed:', errors.value);
        if (errors.lenght === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Quelque chose s\'est mal passé !',
            })
        }
    }
}
</script>

<style lang="scss" scoped></style>