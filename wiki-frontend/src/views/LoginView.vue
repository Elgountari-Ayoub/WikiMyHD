<template>
    <!-- {{ user }} -->
    <Nav />
    
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-6 mx-auto mt-16 bg-white rounded-md shadow ms:px-16">
            {{ user }}
            <h1 class="mb-6 text-3xl font-bold">Login</h1>
            <form @submit.prevent="login">
                <div class="mb-4">
                    <TextInput label="Email" placeholder="elgountariayoub22@gmai.com" v-model:input="email"
                        inputType="email" error="This is email error" />
                </div>
                <div class="mb-4">
                    <TextInput label="Mot de passe" v-model:input="password" inputType="password"
                        error="This is mot de passe error " />
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
import { useRoute } from 'vue-router';


axios.defaults.withCredentials = true;

const email = ref(null);
const password = ref(null);
const user = ref();

const router = useRoute();

async function login() {
    try {
        // Get CSRF token from Laravel
        await axios.get('http://localhost:8000/sanctum/csrf-cookie');

        const response = await axios.post('http://localhost:8000/login', {
            email: email.value,
            password: password.value,
        });

        console.log(response.request);


        console.log('logged in successfully');

        // Clear form fields
        email.value = '';
        password.value = '';

        // get the authenticated user data
        let { data } = await axios.get('http://localhost:8000/api/user');
        user.value = data;

        // save it in a store
        router.push('profileSection')
    } catch (error) {
        console.error('login failed:', error);
    }
}
</script>

<style lang="scss" scoped></style>