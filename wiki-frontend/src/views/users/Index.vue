<template>
    <RouterView />
    <DashboardLayout>

        <div v-if="isModalOpen" @click.self="closeModal"
            class="fixed inset-0 z-50 flex flex-col items-center justify-center overflow-scroll ">
            <div class="bg-white rounded-md shadow-2xl w-96 min-h-[99vh] ">

                <!-- Toggle Taps Buttons -->
                <div class="flex w-full bg-gray-200 pt-4 text-sm ">
                    <button @click="OpenCrearUserModal"
                        class="text-black bg-gray-50 w-fit rounded-t p-2 px-4 ml-4 border-2 border-b-white"
                        :class="{ 'bg-transparent text-blue-500 hover:text-blue-700 border-2 border-solid border-b-gray-200 hover:border-gray-300': !isAddUserModalOpen }">Créer</button>
                    <button @click="OpenAssignSpaceModal"
                        class="text-black bg-gray-50 w-fit rounded-t p-2 px-4  border-2 border-b-white"
                        :class="{ 'bg-transparent text-blue-500 hover:text-blue-700 border-2 border-solid border-b-gray-200 hover:border-gray-300': isAddUserModalOpen }">Attribuer</button>
                </div>

                <!--Start Add User Section -->
                <!-- Modal 1 -->
                <div v-if="isAddUserModalOpen" class="p-8 " @click.self="closeAddUserModal">
                    <div class="" ref="modal">
                        <h2 class="mb-6 font-bold">Créer un utilisateur</h2>
                        <form @submit.prevent="createUser">
                            <div class="mb-4">
                                <TextInput label="Nom" placeholder="Elgountari Ayoub" v-model:input="name" inputType="text"
                                    class="text-sm" :error="errors.name ? errors.name[0] : ''" />
                            </div>

                            <div class="mb-4">
                                <TextInput label="Email" placeholder="elgountariayoub22@gmai.com" v-model:input="email"
                                    inputType="email" class="text-sm" :error="errors.email ? errors.email[0] : ''" />
                            </div>
                            <div class="mb-4">
                                <TextInput label="Mot de passe" v-model:input="password" inputType="password"
                                    class="text-sm" :error="errors.password ? errors.password[0] : ''" />
                            </div>
                            <div class="mb-4">
                                <TextInput label="Confiramtion de mot de passe" v-model:input="password_confirmation"
                                    inputType="password" class="text-sm"
                                    :error="errors.password ? errors.password[0] : ''" />

                            </div>
                            <div class="mb-4">
                                <TextInput label="Post" placeholder="Web Developer" v-model:input="post" inputType="text"
                                    class="text-sm" :error="errors.post ? errors.post[0] : ''" />
                            </div>
                            <div class="flex justify-between text-sm">
                                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                                    Créer
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
                <!--End Add User Section -->

                <!-- Start [Assing Spaces/Manuals To User] Section -->
                <!-- Modal 2-->
                <div v-else @click.self="showModal = false" class="p-8 ">
                    <div class="">
                        <h2 class="mb-6 font-bold">Sélectionner les espaces et les manuel</h2>
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
                        <button @click="handleSubmit" type="button"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-auto">
                            Soumettre
                        </button>
                    </div>
                    <!-- </div> -->
                    <!-- End [Assing Spaces/Manuals To User] Section -->
                </div>
                <!--End [Assing Spaces/Manuals To User] Section -->

            </div>
        </div>

        <!-- ------------------------------------------------------------------------------------------------------ -->

        <!-- Start [Assing Spaces/Manuals To User] Section -->
        <!-- Modal 2-->
        <div v-if="showModal" @click.self="showModal = false"
            class="fixed inset-0 z-30 flex flex-col items-center justify-center">
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

                    <button @click="handleSubmit" type="button"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-auto">
                        Soumettre
                    </button>
                </div>
            </div>
            <!-- End [Assing Spaces/Manuals To User] Section -->
        </div>
        <!-- ------------------------------------------------------------------------------------------------------ -->

        <!-- Add user btn -->
        <div class="flex items-center mb-4 gap-4">
            <button @click="openModal"
                class="px-4 py-2 w-fit mb-2 text-white text-sm bg-green-500 rounded-md hover:bg-green-600 ">
                Ajouter Utilisateur
            </button>
            <div class="flex items-center m-auto w-8/12 sticky gap-4 z-96 ">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input v-model="searchInput" name="search" type="text" id="simple-search"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:border-gray-500 "
                    placeholder="rechercher par nom email ou toute propriété">
            </div>
        </div>

        <!-- ------------------------------------------------------------------------------------------------------ -->


        <!-- Toggle Taps Buttons -->
        <div class="flex w-full bg-gray-200 pt-4 text-sm ">
            <button @click="onActiveUsers = true"
                class="text-black bg-gray-50 w-fit rounded-t p-2 px-4 ml-4 border-2 border-b-gray-200"
                :class="{ 'bg-transparent text-blue-500 hover:text-blue-700 border-2 border-solid border-b-gray-200 hover:border-gray-300': !onActiveUsers }">Actif</button>
            <button @click="onActiveUsers = false"
                class="text-black bg-gray-50 w-fit rounded-t p-2 px-4  border-2 border-b-gray-200"
                :class="{ 'bg-transparent text-blue-500 hover:text-blue-700 border-2 border-solid border-b-gray-200 hover:border-gray-300': onActiveUsers }">Archivé</button>
        </div>
        <!-- table -->
        <div v-if="usersStore.users && userStore.isAdmin">
            <div class="relative  shadow-md sm:rounded-lg overflow-x-auto">
                <table v-if="onActiveUsers" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-20">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3    ">
                                Photo
                            </th>
                            <th scope="col" class="px-6 py-3">
                                nom
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Espaces
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Manuels
                            </th>
                            <th scope="col" class="px-6 py-3 w-16">
                                Post
                            </th>
                            <th scope="col" class="px-6 py-3 w-16">
                                Rôle
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Changer
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(user, index) in  filteredUsers()" :key="index">
                            <tr v-if="user.status === 0 || user.status === 1"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50  dark:hover:bg-gray-600">
                                <td class="px-6 py-4 ">
                                    <img v-if="user.photo" class="w-8 rounded-full" :src="getImageUrl(user.photo)" alt="">
                                    <svg v-else fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                        class="flex-shrink-0  bg-white w-8 h-8 rounded-full text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z">
                                        </path>
                                    </svg>
                                </td>
                                <th scope="row"
                                    class="px-6 py-4  font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ user.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ user.email }}
                                </td>

                                <td class="px-6 py-4">
                                    <ul>
                                        <li v-for="spaceTitle in getUserSpacesTitles(user)">
                                            - {{ spaceTitle }}
                                        </li>
                                    </ul>
                                </td>

                                <td class="px-6 py-4">
                                    <ul>
                                        <li v-for="manualTitle in getUserManualsTitles(user)">
                                            - {{ manualTitle }}

                                        </li>
                                    </ul>
                                </td>
                                <td class="px-6 py-4">
                                    {{ user.post }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ user.role }}
                                </td>
                                <!-- Start status -->
                                <td class="px-6 py-4 font-bold text-xl flex justify-between" v-if="user.status === 0">
                                    <!-- en attente -->
                                    <i @click="showAssignModel(user.id, 1)" title="Approuvée"
                                        class="ri-check-line cursor-pointer text-blue-500 hover:text-blue-700"></i>
                                    <i @click="archive(user.id, -2)" title="Annuler"
                                        class="ri-close-line cursor-pointer text-red-500 hover:text-red-700"></i>
                                </td>

                                <td class="px-6 py-4 font-bold text-xl flex justify-betweentext-green-500"
                                    v-else-if="user.status === 1">
                                    <i class="ri-check-line bg-green-500 text-white" title="approuvée"></i>

                                </td>

                                <td v-else class="px-6 py-4 font-bold text-xl text-red-500 flex gap-4 justify-between">
                                    <i title="Annulé" class="ri-close-line bg-red-500 text-white"></i>
                                </td>

                                <!-- toggle user status -->
                                <td v-if="user.status === 1 && user.role != 'admin'"
                                    class="px-6 py-4 font-bold text-sm text-red-500  gap-4 justify-between">
                                    <button @click="toggleSwitch(index, user.id, user.status)"
                                        :class="{ 'bg-green-500': user.status === 1, 'bg-red-500': !(user.status === 1) }"
                                        class="relative w-16 h-8  px-1 rounded-full transition-colors duration-300">
                                        <span
                                            :class="{ 'translate-x-8': user.status === 1, 'translate-x-0': !(user.status === 1) }"
                                            class="block w-6 h-6 rounded-full bg-white shadow-md transform transition-transform duration-300"></span>
                                        <span
                                            class="absolute inset-0 flex items-center justify-center font-semibold text-white z-10">
                                            <span v-if="user.status === 1" class="mr-auto ml-1">On</span>
                                            <span v-if="!(user.status === 1)" class="ml-auto mr-1">Off</span>

                                        </span>
                                    </button>
                                </td>
                                <td>

                                </td>
                            </tr>
                        </template>


                    </tbody>
                </table>
                <table v-else class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-20">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3    ">
                                Photo
                            </th>
                            <th scope="col" class="px-6 py-3">
                                nom
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Espaces
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Manuels
                            </th>
                            <th scope="col" class="px-6 py-3 w-16">
                                Post
                            </th>
                            <th scope="col" class="px-6 py-3 w-16">
                                Rôle
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Changer
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Suppression
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(user, index) in  filteredUsers()" :key="index">
                            
                            <tr v-if="user.status === -1 || user.status === -2"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50  dark:hover:bg-gray-600">
                                <td class="px-6 py-4 ">
                                    <img v-if="user.photo" class="w-8 rounded-full" :src="getImageUrl(user.photo)" alt="">
                                    <svg v-else fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                        class="flex-shrink-0  bg-white w-8 h-8 rounded-full text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z">
                                        </path>
                                    </svg>
                                </td>
                                <th scope="row"
                                    class="px-6 py-4  font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ user.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ user.email }}
                                </td>

                                <td class="px-6 py-4">
                                    <ul>
                                        <li v-for="spaceTitle in getUserSpacesTitles(user)">
                                            - {{ spaceTitle }}
                                        </li>
                                    </ul>
                                </td>

                                <td class="px-6 py-4">
                                    <ul>
                                        <li v-for="manualTitle in getUserManualsTitles(user)">
                                            - {{ manualTitle }}

                                        </li>
                                    </ul>
                                </td>
                                <td class="px-6 py-4">
                                    {{ user.post }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ user.role }}
                                </td>
                                <!-- Start status -->
                                <td class="px-6 py-4 font-bold text-xl flex justify-between" v-if="user.status === 0">
                                    <!-- en attente -->
                                    <i @click="showAssignModel(user.id, 1)" title="Approuvée"
                                        class="ri-check-line cursor-pointer text-blue-500 hover:text-blue-700"></i>
                                    <i @click="archive(user.id, -1)" title="Annuler"
                                        class="ri-close-line cursor-pointer text-red-500 hover:text-red-700"></i>
                                </td>

                                <td class="px-6 py-4 font-bold text-xl flex justify-betweentext-green-500"
                                    v-else-if="user.status === 1">
                                    <i class="ri-check-line bg-green-500 text-white" title="approuvée"></i>

                                </td>

                                <td v-else class="px-6 py-4 font-bold text-xl text-red-500 flex gap-4 justify-between">
                                    <i title="Annulé" class="ri-close-line bg-red-500 text-white"></i>
                                </td>
                                <!-- End status -->

                                <!-- toggle user status -->
                                <td v-if="user.status !== 0"
                                    class="px-6 py-4 font-bold text-sm text-red-500  gap-4 justify-between">
                                    <button @click="toggleSwitch(index, user.id, user.status)"
                                        :class="{ 'bg-green-500': user.status === 1, 'bg-red-500': !(user.status === 1) }"
                                        class="relative w-16 h-8  px-1 rounded-full transition-colors duration-300">
                                        <span
                                            :class="{ 'translate-x-8': user.status === 1, 'translate-x-0': !(user.status === 1) }"
                                            class="block w-6 h-6 rounded-full bg-white shadow-md transform transition-transform duration-300"></span>
                                        <span
                                            class="absolute inset-0 flex items-center justify-center font-semibold text-white z-10">
                                            <span v-if="user.status === 1" class="mr-auto ml-1">On</span>
                                            <span v-if="!(user.status === 1)" class="ml-auto mr-1">Off</span>

                                        </span>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <span @click="deleteUser(user.id)"
                                        class="text-white bg-red-500 px-2 py-2 cursor-pointer hover:bg-red-600 rounded m-auto">supprimer</span>
                                </td>
                            </tr>
                        </template>


                    </tbody>
                </table>
            </div>
        </div>
        <LoadingAnimation v-else />
    </DashboardLayout>
</template>

<script setup>
import { RouterView } from 'vue-router';
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import { onMounted, ref, watch } from 'vue';
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
import { removeDuplicates } from '@tiptap/core';

axios.defaults.withCredentials = true;

const router = useRouter();
const userStore = useUserStore();
const usersStore = useUsersStore();
const spacesStore = useSpacesStore();

const spaces = ref([]);
const getUsers = onMounted(async () => {
    await usersStore.getUsers();

    await spacesStore.getSpaces();
    spaces.value = spacesStore.spaces;

    isOn.value = usersStore.users.map(user => {
        if (user.status == 1) {
            return true;
        } else {
            return false;
        }
    });
})

const showModal = ref(false);

const userId = ref(null);
const status = ref(null);
const showAssignModel = async (user_id, _status) => {
    showModal.value = true;
    userId.value = user_id;
    status.value = _status;

}

const archive = async (userId, status) => {
    await axios.post('/api/updateUserStatus', {
        user_id: userId,
        status: status
    }).then(async (response) => {
        console.log(response);
        await usersStore.getUsers();
    }).catch(error => {
        console.log(error);
        console.log('ERROR IN CANCLE USER');
    });
    // usersStore.getUsers();
}
const deleteUser = async (userId) => {

    Swal.fire({
        title: "Êtes-vous sûr(e) ?",
        text: "Une fois supprimé, cet élément ne pourra pas être récupéré !",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: 'Confirmer',
        denyButtonText: `Annuler`,
    }).then(async (result) => {
        if (result.isConfirmed) {
            await axios.delete(`/api/users/${userId}`)
                .then(async (response) => {
                    console.log(response);
                    await usersStore.getUsers();
                }).catch(error => {
                    console.log(error);
                    console.log('ERROR IN DELETING THE USER');
                });
        }
    })
}

const getImageUrl = (photo) => {
    const baseUrl = "http://localhost:8000/storage/";
    return baseUrl + photo;
}

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
        if (selectedSpaces.value.length > 0) {
            console.log(selectedSpaces.value);
            shareSpaces();
        }
        if (selectedManuals.value.length > 0) {
            console.log(selectedManuals.value);
            shareManuals();
        }
        approve(userId.value, status.value)
        userId.value = null;
        status.value = null;
        selectedSpaces.value = [];
        selectedManuals.value = [];
    };
    getUsers();
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

const updateUserStatus = async (userId, status) => {
    await axios.post('/api/updateUserStatus', {
        user_id: userId,
        status: status
    }).then(async (response) => {
        console.log(response);
        await usersStore.getUsers();
    }).catch(error => {
        console.log('ERROR IN UPDATING THE STATUS');
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

// ############################################################################################################
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
// ############################################################################################################

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

    errors.value = {} // Reset any previous errors

    // Validate form inputs
    if (!name.value) {
        errors.value.name = ['Veuillez entrer votre nom']
    }

    if (!email.value) {
        errors.value.email = ['Veuillez entrer votre adresse e-mail']
    }

    if (!password.value) {
        errors.value.password = ['Veuillez entrer un mot de passe']
    } else if (password.value !== password_confirmation.value) {
        errors.value.password = ['Les mots de passe ne correspondent pas']
    }

    if (!post.value) {
        errors.value.post = ['Veuillez entrer votre poste']
    }

    // If there are any errors, stop form submission
    if (Object.keys(errors.value).length > 0) {
        isAddUserModalOpen.value = true;
        return
    }

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
            closeModal();
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
            getUsers();
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

const isModalOpen = ref(false)
const openModal = () => {
    isModalOpen.value = true;
    isAddUserModalOpen.value = true;
}
const closeModal = () => {
    isModalOpen.value = false;
}

const isAddUserModalOpen = ref(false);
const closeAddUserModal = () => {
    isAddUserModalOpen.value = false;
}

const OpenCrearUserModal = () => {
    isAddUserModalOpen.value = true;
}
const OpenAssignSpaceModal = () => {
    isAddUserModalOpen.value = false;
};


// <!--End Add User Section -->


// Start The Search Section
const searchInput = ref('')
watch(searchInput, () => {
    filteredUsers();
});

const getUserByName = (user) => {
    return user.name.toLowerCase().includes(searchInput.value.toLowerCase())
}
const getUserByEmail = (user) => {
    return user.email.toLowerCase().includes(searchInput.value.toLowerCase())
}
const getUserByPost = (user) => {
    return user.post.toLowerCase().includes(searchInput.value.toLowerCase())
}
const getUserByRole = (user) => {
    return user.role.toLowerCase().includes(searchInput.value.toLowerCase())
}
const getUserBySpace = (user) => {
    return user.spaces.some(space => space.title.toLowerCase().includes(searchInput.value.toLowerCase()));
}
const getUserByanual = (user) => {
    return user.manuals.some(manual => manual.title.toLowerCase().includes(searchInput.value.toLowerCase()));
}
const filteredUsers = () => {
    let isEmpty = /^\s*$/.test(searchInput.value);
    if (isEmpty) {
        return usersStore.users
    } else {
        return usersStore.users.filter(user => {
            return getUserByName(user) || getUserBySpace(user) || getUserByanual(user) || getUserByEmail(user) || getUserByPost(user) || getUserByRole(user);
        });
    }

};
// End The Search Section

// ---------------------------------
// Start The Users Spaces Section
const getUserSpacesTitles = (user) => {
    let res = [];
    user.spaces.filter(space => {
        res.push(space.title)
    })
    return res;
}

// End The Users Spaces Section


// ---------------------------------
// Start The User Manuals Section
const getUserManualsTitles = (user) => {
    let res = [];
    user.manuals.filter(manual => {
        res.push(manual.title)
    })
    return res;
}
// End The User Manuals Section

// Toggle the Spaces list


// Toggle the user status
const isOn = ref([]);

const toggleSwitch = (index, user_id, oldStatus, newStatus) => {

    Swal.fire({
        title: "Êtes-vous sûr(e) ?",
        // text: "Une fois supprimé, cet élément ne pourra pas être récupéré !",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: 'Confirmer',
        denyButtonText: `Annuler`,
    }).then(async (result) => {
        if (result.isConfirmed) {
            if (oldStatus === newStatus) {
                return;
            }
            if (isOn.value[index]) {
                archive(user_id, -1);
            } else if (oldStatus === -1) {
                updateUserStatus(user_id, 1)
            } else if (oldStatus === -2) {
                showAssignModel(user_id, 1)
            }
        }
    })

};
const onActiveUsers = ref(true);

</script>

<style lang="scss" scoped></style>