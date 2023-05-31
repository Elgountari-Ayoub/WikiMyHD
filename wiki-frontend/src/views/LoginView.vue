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
    errors.value = []
    // Get CSRF token from Laravel
    await axios.get('http://localhost:8000/sanctum/csrf-cookie');
    // console.log(email.value, '\n', password.value.length);
    await axios.post('http://localhost:8000/login', {
        email: email.value,
        password: password.value,
    }).then(async (response) => {
        console.log('user logged in success!, response:\n', response.data.user ?? 'the user not approved');
        // after the logged in, let's check if the user has been approved
        if (response.data.user) {
            console.log('user has been approved, Great!\n');
            // save the logged user data in the userStore
            userStore.setUserDetails(response);
            console.log('the user data saved with success:\n', userStore);

            // get spaces
            try {
                const spacesRes = await axios.get('http://localhost:8000/api/spaces');
                console.log('the user spaces fetched with success!\n');

                spacesStore.setSpacesDetails(spacesRes);
                console.log('the user spaces saved with success!\n\n', spacesStore.spaces);
            } catch (err) {
                console.log('ERROR IN FETCHIGN/SAVING SPACES\n\n', err);
            }

            // GET THE LOGGED SPACES
            await axios.get('http://localhost:8000/api/spaces').then(response => {
                console.log('the user spaces fetched with success!\n');
                spacesStore.setSpacesDetails(response);
                console.log('the user spaces saved with success!\n\n', spacesStore.spaces);
            }).catch(error => {
                console.log('ERROR IN FETCHIGN/SAVING SPACES\n\n', error);
            });


            // GET THE LOGGED USER MANUALS
            await axios.get('http://localhost:8000/api/manuals').then(response => {
                console.log('the user manuals fetched with success:\n',);
                manualsStore.setManualsDetails(response)
                console.log('the user manuals saved with success!\n\n', response);
            }).catch(error => {
                console.log('ERROR IN FETCHIGN MANUALS\n\n', error);
            })

            // GET THE USERS DATA IF THE LOGGED IN USER IS AN ADMIN
            if (userStore.isAdmin) {
                await axios.get('http://localhost:8000/api/users').then(response => {
                    console.log('the users data fetched with success:\n');
                    usersStore.setUsersDetails(response.data.users)
                    console.log('the users data saved with success:\n', usersStore);
                }).catch(error => {
                    console.log('ERROR IN FETCHIGN USERS\n\n', error);
                })
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
        // if not approved
        else {
            await axios.post('http://localhost:8000/logout').then(response => {
                Swal.fire({
                    icon: 'warning',
                    text: 'Votre candidature n\'a pas encore été approuvée',
                    showConfirmButton: false,
                    timer: 3000
                })
            }).catch(error => {
                console.log('ERROR IN LOGOUT\n\n', error);
            });
        }
    }).catch(error => {
        console.log('error in login', error);
        if (errors.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Quelque chose s\'est mal passé!',
            })
        } else {
            errors.value = error.response.data.errors;
        }

    }
    );

}
</script>

<style lang="scss" scoped></style>