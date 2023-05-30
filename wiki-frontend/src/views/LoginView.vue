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
import { useUserStore } from '../stores/user-store';
import { useSpacesStore } from '../stores/spaces-store';
import { useManualsStore } from '../stores/manuals-store';
import { useUsersStore } from '../stores/users-store';


axios.defaults.withCredentials = true;

const userStore = useUserStore();
const spacesStore = useSpacesStore();
const manualsStore = useManualsStore();
const usersStore = useUsersStore();

const email = ref(null);
const password = ref(null);

const errors = ref([]);

const router = useRouter();

async function login() {
    try {
        errors.value = []
        // Get CSRF token from Laravel
        await axios.get('http://localhost:8000/sanctum/csrf-cookie');
        // console.log(email.value, '\n', password.value.length);
        const response = await axios.post('http://localhost:8000/login', {
            email: email.value,
            password: password.value,
        }).then(async (response) => {
            console.log('user logged in success!, response:\n', response.data.user);
            // check if the user status == 1 wich means the user has the access to our wikimyhd website
            if (response.data.user.status === 1) {
                console.log('user has been approved, Great!\n');
                // save the logged user data in the userStore
                userStore.setUserDetails(response);
                console.log('the user data saved with success:\n', response);
                try {
                    const spacesRes = await axios.get('http://localhost:8000/api/spaces');
                    console.log('the user psaces fetched with success:\n', spacesRes);

                    spacesStore.setSpacesDetails(spacesRes.data.spaces);
                    console.log('the user psaces saved with success:\n');
                } catch (err) {
                    console.log('ERROR IN FETCHIGN/SAVING SPACES\n\n', err);
                }
                try {
                    // get his manuals
                    const manualsRes = await axios.get('http://localhost:8000/api/manuals')
                    console.log('the user manuals fetched with success:\n');

                    manualsStore.setManualsDetails(manualsRes.data.manuals)
                    console.log('the user manuals saved with success:\n\n');
                } catch (err) {
                    console.log('ERROR IN FETCHIGN MANUALS\n\n', err);

                }
                // get the users data if the logged in user is an admin
                if (userStore.role === 'admin') {
                    try {
                        console.log('the users data saved with success:\n');
                        console.log('the users data saved with success:\n');
                        const usersRES = await axios.get('http://localhost:8000/api/users')
                        console.log(usersRES);
                        usersStore.setUsersDetails(usersRES.data.users)
                    } catch (err) {
                        console.log('ERROR IN FETCHIGN USERS\n\n', err);

                    }
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
                // errors.value = err.response.data.errors;
                try {
                    await axios.post('http://localhost:8000/logout');

                } catch (error) {

                }
                Swal.fire({
                    icon: 'warning',
                    text: 'Votre candidature n\'a pas encore été approuvée',
                    showConfirmButton: false,
                    timer: 3000
                })
            }
        }).catch(err => {
            console.log('ERROOOOOR', err);
        }
        );



        // check if the authnticated user has the right to visiti the website
    } catch (err) {
        console.error('login failed:', err);
        if (errors.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Quelque chose s\'est mal passé !',
            })
        } else {
            errors.value = err.response.data.errors;

        }
    }
}
</script>

<style lang="scss" scoped></style>