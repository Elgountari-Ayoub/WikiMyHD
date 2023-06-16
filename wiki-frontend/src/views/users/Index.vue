<template>
    <div id="AccountView">
        <RouterView />
        <DashboardLayout>
            <div>
                <!-- Modal -->
                <div v-if="showModal"
                    class="fixed left-0 z-50 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
                    <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
                        <div class="flex flex-col items-start justify-between p-6 space-y-4 w-96">
                            <div class="text-lg font-bold text-gray-900">Sélectionner les espaces et les manuel</div>

                            <!-- Spaces Multi-Select -->
                            <div class="w-full">
                                <label for="spaces" class="block text-sm font-medium text-gray-700 mb-1">Spaces</label>
                                <select multiple v-model="selectedSpaces" id="spaces" name="spaces[]"
                                    class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                                    <option v-for="space in spaces" :value="space.id" :key="space.id">{{ space.title }}
                                    </option>
                                </select>
                            </div>

                            <!-- Manuals Dropdown -->
                            <div class="w-full">
                                <label for="manual" class="block text-sm font-medium text-gray-700 mb-1">Manuals</label>
                                <select multiple v-model="selectedManuals" id="manual" name="manual[]"
                                    class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                                    <option v-for="manual in filteredManuals" :value="manual.id" :key="manual.id">{{
                                        manual.title }}</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-2 ml-auto">
                                <button @click="showModal = false" type="button"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Annuler
                                </button>
                                <button @click="handleSubmit" type="button"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Soumettre
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add user -->
            <button @click="openAddUserModal"
                class="px-4 py-2 w-fit mb-2 text-white text-sm bg-green-500 rounded-md hover:bg-green-600 ">
                Ajouter Utilisateur
            </button>
            <!-- table -->
            <div v-if="usersStore.users && userStore.isAdmin">
                <div class="relative  shadow-md sm:rounded-lg overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-20">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Nom Utilisateur
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Photo
                                </th>
                                <th scope="col" class="px-6 py-3 w-16">
                                    Post
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Rôle
                                </th>
                                <th scope="col" class="px-6 py-3"> <!--approved or not -->
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in usersStore.users" :key="user.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ user.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 ">
                                    <img v-if="userStore.photo" class="w-8 rounded-full" :src="getImageUrl(user.photo)"
                                        alt="">
                                    <svg v-else fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                        class="flex-shrink-0  bg-white w-8 h-8 rounded-full text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z">
                                        </path>
                                    </svg>
                                </td>
                                <td class="px-6 py-4">
                                    {{ user.post }}

                                </td>
                                <td class="px-6 py-4">
                                    {{ user.role }}

                                </td>
                                <!-- Start status -->
                                <td class="px-6 py-4 font-bold text-blue-500 cursor-pointer" v-if="user.status === 0">
                                    <!-- en attente -->
                                    <!-- Modal  Edit/Delete Manual Buttons-->
                                    <Dropdown class="ml-auto">
                                        <template #trigger>
                                            <svg fill="currentColor" stroke="" stroke-width="1.5" viewBox="0 0 24 24"
                                                class="w-10 h-10 font-bold flex items-center text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:x`-700"
                                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z">
                                                </path>
                                            </svg>
                                        </template>
                                        <template #content>
                                            <div class=" rounded-md text-sm shadow-2xl flex flex-col gap-2" ref="options">
                                                <button @click="showAssignModel(user.id, 1)"
                                                    class="px-2 py-1 text-white bg-green-500 rounded-md hover:bg-green-600">
                                                    Approuvée
                                                </button>
                                                <button @click="cancel(user.id, -1)"
                                                    class="px-2 py-1 text-white bg-red-500 rounded-md hover:bg-red-600 sm:text-sm md:text-base">
                                                    Supprimer
                                                </button>
                                            </div>
                                        </template>
                                    </Dropdown>
                                </td>
                                <td class="px-6 py-4 font-bold text-green-500" v-else-if="user.status === 1">
                                    approuvée
                                </td>
                                <td class="px-6 py-4 font-bold text-red-500" v-else>
                                    pas approuvée
                                </td>
                                <!-- End Status -->
                                <td class="px-6 py-4 text-left">
                                    <a href="#"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <LoadingAnimation v-else />

            <!--Start Add User Section -->
            <!-- <div v-if="isAddUserModalOpen" class="flex items-center justify-center fixed inset-0" @click.self="closeAddUserModal"> -->
            <!-- <div class="w-full max-w-md p-6 mx-auto mt-16 bg-white rounded-md shadow-lg" ref="modal"> -->
            <div v-if="isAddUserModalOpen" class="fixed inset-0 z-10 flex items-center justify-center "
                @click.self="closeAddUserModal">
                <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
                    <h1 class="mb-6 text-3xl font-bold">Créer un utilisateur</h1>
                    <form @submit.prevent="createUser">

                        <div class="mb-4">
                            <TextInput label="Nom" placeholder="Elgountari Ayoub" v-model:input="name" inputType="text"
                                :error="errors.name ? errors.name[0] : ''" />
                        </div>

                        <div class="mb-4">
                            <TextInput label="Email" placeholder="elgountariayoub22@gmai.com" v-model:input="email"
                                inputType="email" :error="errors.email ? errors.email[0] : ''" />
                        </div>
                        <div class="mb-4">
                            <TextInput label="Mot de passe" v-model:input="password" inputType="password"
                                :error="errors.password ? errors.password[0] : ''" />
                        </div>
                        <div class="mb-4">
                            <TextInput label="Confiramtion de mot de passe" v-model:input="password_confirmation"
                                inputType="password" :error="errors.password ? errors.password[0] : ''" />

                        </div>
                        <div class="mb-4">
                            <TextInput label="Post" placeholder="Web Developer" v-model:input="post" inputType="text"
                                :error="errors.post ? errors.post[0] : ''" />
                        </div>
                        <div class="flex justify-between text-sm">
                            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                                Créer
                            </button>

                            <span @click="showModal = true"
                                class="px-4 py-2 text-white bg-gray-500 rounded-md hover:bg-gray-600">
                                Attribution d'espaces/manuels.
                            </span>
                        </div>
                    </form>

                </div>
            </div>
            <!--End Add User Section -->

        </DashboardLayout>
    </div>
</template>

<script setup>
import { RouterView } from 'vue-router';
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import LoadingAnimation from '../../components/global/LoadingAnimation.vue';
import { useRouter } from 'vue-router';
import Dropdown from '../../components/global/Dropdown.vue';

import { computed, watchEffect } from 'vue';
import { useSpacesStore } from '../../stores/spaces-store';

import { useUserStore } from '../../stores/user-store';
import { useUsersStore } from '../../stores/users-store';
import Swal from 'sweetalert2';
import TextInput from '../../components/global/TextInput.vue';


axios.defaults.withCredentials = true;

const router = useRouter();
const userStore = useUserStore();
const usersStore = useUsersStore();
const spacesStore = useSpacesStore();

onMounted(async () => {
    await usersStore.getUsers();
});
const showModal = ref(false);


const userId = ref(null);
const status = ref(null);
const showAssignModel = async (user_id, _status) => {
    showModal.value = true;
    userId.value = user_id;
    status.value = _status;

}

const cancel = async (userId, status) => {
    try {
        const res = await axios.post('/api/approve', {
            user_id: userId,
            status: status
        })
        // usersStore.getUsers();
    } catch (error) {
        console.log('ERROR IN APPROVING');
    }
}

const getImageUrl = (photo) => {
    const baseUrl = "http://localhost:8000/storage/";
    return baseUrl + photo;
}

const spaces = ref([]);
onMounted(async () => {
    await spacesStore.getSpaces();
    spaces.value = spacesStore.spaces;
})

const selectedSpaces = ref([]);
const selectedManuals = ref([]);

const filteredManuals = computed(() => {
    const filtered = [];
    for (const spaceId of selectedSpaces.value) {
        const space = spaces.value.find((space) => space.id === spaceId);
        if (space) {
            filtered.push(...space.manuals);
        }
    }
    return filtered;
});

const handleSubmit = () => {
    if (userId.value == null ||
        status.value == null) {
        createUser();
        return;
    }
    else {
        showModal.value = false;
        shareSpaces();
        shareManuals();
        approve(userId.value, status.value)
        userId.value = null;
        status.value = null;
        selectedSpaces.value = [];
        selectedManuals.value = [];
    };
}

const updateManuals = () => {
    const selectedSpaceIds = selectedSpaces.value;
    selectedManuals.value = selectedManuals.value.filter((manualId) => {
        const manualSpace = spaces.value.find((space) => space.manuals.some((manual) => manual.id === manualId));
        return manualSpace && selectedSpaceIds.includes(manualSpace.id);
    });
};

watchEffect(() => {
    // Update selected manuals when spaces selection changes
    updateManuals();
});

const approve = async (userId, status) => {
    await axios.post('/api/approve', {
        user_id: userId,
        status: status
    }).then(async (response) => {
        console.log(response);
        await usersStore.getUsers();
    }).catch(error => {
        console.log('ERROR IN APPROVING');
        console.log(error);

    })
}
const shareSpaces = async () => {
    await axios.post('/api/assignspace', {
        user_id: userId.value,
        spaces: selectedSpaces.value
    }).then(response => {
        console.log(response);
    }).catch(error => {
        console.log(error);
    })
}
const shareManuals = async () => {
    await axios.post('/api/assignmanual', {
        user_id: userId.value,
        manuals: selectedManuals.value
    }).then(response => {
        console.log(response);
    }).catch(error => {
        console.log(error);
    })
}

// -----------------------------------------------------------------------------|   |  ----
// -----------------------------------------------------------------------------|   | |---|
// -----------------------------------------------------------------------------|___| ___|
// <!--Start Add User Section -->

const name = ref(null)
const email = ref(null)
const password = ref(null)
const password_confirmation = ref(null)
const post = ref(null)

const errors = ref([]);


async function createUser() {
    try {
        // Get CSRF token from Laravel
        const csrf = await axios.get('/sanctum/csrf-cookie');

        errors.value = [];
        const res = await axios.post('/api/createUser', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
            post: post.value,
            spaces: selectedSpaces.value,
            manuals: selectedManuals.value

        }).then(response => {
            showModal.value = false;
            closeAddUserModal();
            console.log(response);
            // Clear form fields
            email.value = null;
            password.value = null;
            password_confirmation.value = null;
            post.value = null;
            selectedSpaces.value = [];
            selectedManuals.value = [];
            
            // Show Success Message
            console.log('Registration successful:', response.data);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'l\'utilisateur ' + name.value + ' a créé avec succès',
                showConfirmButton: false,
                timer: 1500
            })
            name.value = null;

            // empty the errors array
            errors.value = [];
        }).catch(error => {
            console.log('ERROR\n\n', error);
            errors.value = error.response.data.errors;
            console.error('Registration failed:', error);
            if (errors.lenght === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Quelque chose s\'est mal passé !',
                })
            }

        });

    } catch (err) {
        console.log('ERROR\N\N', error);
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


const isAddUserModalOpen = ref(false);
const openAddUserModal = () => {
    isAddUserModalOpen.value = true;
}
const closeAddUserModal = () => {
    isAddUserModalOpen.value = false;
}

// <!--End Add User Section -->
</script>

<style lang="scss" scoped></style>