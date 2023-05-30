<template>
    <div id="AccountView">
        <RouterView />
        <DashboardLayout>
            <div v-if="usersStore.users.length !== 0 && userStore.isAdmin">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                                        <span>rr</span>
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
                                                <button @click="openAffectSpacesModal(user.id, 1)"
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
                                <td class="px-6 py-4 text-right">
                                    <a href="#"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <LoadingAnimation v-else />
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


import { useUserStore } from '../../stores/user-store';
import { useUsersStore } from '../../stores/users-store';
import Swal from 'sweetalert2';

axios.defaults.withCredentials = true;

const router = useRouter();
const userStore = useUserStore();
const usersStore = useUsersStore();

onMounted(async () => {
    usersStore.fetchUsers();
});

const openAffectSpacesModal = async (userId, status) => {
    try {
        const res = await axios.post('http://localhost:8000/api/approve',{
            id_user: userId,
            status: status
        })
        usersStore.fetchUsers();
    } catch (error) {
        console.log('ERROR IN APPROVING');
    }
    // update the code in order to give the user the ability to affect some spaces to the approved user
    

}

const cancel = async (userId, status) => {
    try {
        const res = await axios.post('http://localhost:8000/api/approve',{
            id_user: userId,
            status: status
        })
        usersStore.fetchUsers();
    } catch (error) {
        console.log('ERROR IN APPROVING');
    }
}

const getImageUrl = (photo) => {
    const baseUrl = "http://localhost:8000/storage/";
    return baseUrl + photo; // Concatenating the base URL and the photo variable
}
</script>

<style lang="scss" scoped></style>