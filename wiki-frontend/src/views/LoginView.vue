<template>
    <!-- {{ user }} -->
    <Nav />

    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-6 mx-auto mt-16 bg-white rounded-md shadow ms:px-16">
            <h1 class="mb-6 text-3xl font-bold">Login</h1>
            <form @submit.prevent="login">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" id="email" v-model="form.email" required
                        class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" id="password" v-model="form.password" required
                        class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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

axios.defaults.withCredentials = true;

const form = ref({
    email: null,
    password: null,
});

const user = ref();

async function login() {
    try {
        await axios.get('http://localhost:8000/sanctum/csrf-cookie');
        await axios.post('http://localhost:8000/login', {
            email: form.value.email,
            password: form.value.password,
        });
        console.log('logged in successfully');

        // Clear form fields
        email.value = '';
        password.value = '';
        let { data } = await axios.get('http://localhost:8000/api/user');
        user.value = data;
    } catch (error) {
        console.error('login failed:', error);
    }
}

</script>

<style lang="scss" scoped></style>