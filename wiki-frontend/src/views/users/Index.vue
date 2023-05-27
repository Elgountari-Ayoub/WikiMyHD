<template>
    <div id="AccountView">
        <RouterView />
        <DashboardLayout>
            <div v-if="users.lenght != 0 && userStore.isAdmin">
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
                                <th scope="col" class="px-6 py-3">
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
                            {{ users.length }}
                            <tr v-for="user in users" :key="user.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ user.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{user.email}}
                                </td>
                                <td class="px-6 py-4">
                                    
                                </td>
                                <td class="px-6 py-4">
                                    $2999
                                </td>
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
import { useUserStore } from '../../stores/user-store';
import axios from 'axios';
import LoadingAnimation from '../../components/global/LoadingAnimation.vue';
import { useRouter } from 'vue-router';

axios.defaults.withCredentials = true;



const userStore = useUserStore();
const users = ref([]);

onMounted(() => {
    userStore.fetchUser();
    fetchUsers();
});

const router = useRouter();
const fetchUsers = async () => {
    try {
        const res = await axios.get('http://localhost:8000/api/users') // Get all the users
        users.value = res.data;
    } catch (err) {
        let errMessage = err.response.data.message;
        if (errMessage === 'Route [home] not defined.') {
            console.log('ta sir tl3b m3a khotek 😂😂😂');
            router.push({ name: 'spaces' })
        }
        console.log('this is the error message =>\n\t',errMessage);

    }


}
</script>

<style lang="scss" scoped></style>