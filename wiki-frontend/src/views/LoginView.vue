<template>
    <Nav />
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-6 mx-auto mt-16 bg-white rounded-md shadow ms:px-16">
            <!-- {{ user }} -->
            <h1 class="mb-6 text-3xl font-bold">Connexion</h1>
            <form @submit.prevent="login">
                <div class="mb-4">
                    <TextInput class="text-sm" label="Email" inputType="email" placeholder="elgountariayoub22@gmai.com"
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
    await axios.get('/sanctum/csrf-cookie');
    // console.log(email.value, '\n', password.value.length);
    await axios.post('/login', {
        email: email.value,
        password: password.value,
    }).then(async (response) => {
        console.log('user logged in success!, response:\n', response);
        if (response.data.guest === 'false') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Déjà connecté!',
            })
            return;
        }
        // after the logged in, let's check if the user has been approved
        if (response.data.user.status === 1) {
            console.log('user has been approved, Great!\n');
            // save the logged user data in the userStore
            await userStore.getUser();
            console.log('the user data saved with success:\n', userStore);

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
            Swal.fire({
                icon: 'warning',
                text: 'Votre candidature n\'a pas encore été approuvée',
                showConfirmButton: false,
                timer: 3000
            });
        }
    }).catch(error => {
        errors.value = error.response.data.errors;
        if (errors.value.email[0] == 'These credentials do not match our records.') {
            errors.value.email[0] = 'Ces identifiants ne correspondent pas à nos dossiers.'
        }
    });

}
</script>

<style lang="scss" scoped></style>