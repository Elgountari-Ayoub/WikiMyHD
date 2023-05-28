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
                                </td>
                                <td class="px-6 py-4">
                                    {{ user.post }}

                                </td>
                                <td class="px-6 py-4">
                                    {{ user.role }}

                                </td>
                                <!-- Start status -->
                                <td @click="" class="px-6 py-4 font-bold text-blue-500 cursor-pointer"
                                    v-if="user.status === 0">
                                    en attente
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

import { useUserStore } from '../../stores/user-store';
import { useUsersStore } from '../../stores/users-store';
import Swal from 'sweetalert2';

axios.defaults.withCredentials = true;

const router = useRouter();
const userStore = useUserStore();
const usersStore = useUsersStore();

onMounted(async () => {

});


const getImageUrl = (photo) => {
    const baseUrl = "http://localhost:8000/storage/";
    return baseUrl + photo; // Concatenating the base URL and the photo variable
}
</script>

<style lang="scss" scoped></style>