<template>
    <div id="AccountView">
        <RouterView />
        <DashboardLayout>
            <div>
                <!-- Modal -->
                <div v-if="showModal"
                    class="fixed z-10 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
                    <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
                        <div class="flex flex-col items-start justify-between p-6 space-y-4">
                            <div class="text-lg font-bold text-gray-900">Modal Title</div>

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

                            <div class="flex justify-end space-x-4">
                                <button @click="showModal = false" type="button"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Cancel
                                </button>
                                <button @click="handleSubmit" type="button"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Spaces and Manuals -->
                <!-- <div v-if="selectedSpaces.length > 0 || selectedManuals.length > 0" class="mt-4">
                    <div class="text-lg font-bold">Selected Spaces:</div>
                    <ul class="list-disc ml-4">
                        <li v-for="spaceId in selectedSpaces" :key="spaceId">{{ spaceId }}</li>
                    </ul>
                    <div class="text-lg font-bold mt-4">Selected Manuals:</div>
                    <ul class="list-disc ml-4">
                        <li v-for="manual in selectedManuals" :key="manual">{{ manual }}</li>
                    </ul>
                </div> -->
            </div>
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
    showModal.value = false;
    shareSpaces();
    shareManuals();
    approve(userId.value, status.value)
    userId.value = null;
    status.value = null;
    selectedSpaces.value = [];
    selectedManuals.value = [];
};

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
</script>

<style lang="scss" scoped></style>