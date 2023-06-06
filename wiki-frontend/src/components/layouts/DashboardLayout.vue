<template>
    <RouterView />
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                        type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <RouterLink to='/spaces'>
                        <span
                            class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">WikiMy-HD</span>
                    </RouterLink>
                </div>


                <div class="flex items-center">
                    <div class="flex flex-col items-center ml-3">
                        <div>
                            <button type="button" @click="menuOpen = !menuOpen"
                                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img v-if="userStore.photo" class="w-8 rounded-full" :src="getImageUrl(userStore.photo)"
                                    alt="">
                                <svg v-else fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                    class="flex-shrink-0  bg-white w-8 h-8 rounded-full text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <div v-if="userStore.id && menuOpen"
                            class="z-50 absolute right-10 top-14 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                            id="dropdown-user">
                            <div class="px-4 py-3" role="none">
                                <p class="text-sm text-gray-900 dark:text-white" role="none">
                                    {{ userStore.name }}
                                </p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                    {{ userStore.email }}
                                </p>
                            </div>
                            <ul class="py-1" role="none">
                                <li>
                                    <RouterLink to="/account/profile"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Profil
                                    </RouterLink>
                                </li>
                                <li>
                                    <button @click="logout"
                                        class="w-full text-start block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300
                                                                                                                                                                                                                                    dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">Se déconnecter
                                    </button>

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
        aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800 ">
            <ul class="space-y-2 font-medium  gap-1">
                <li>
                    <div class="flex">
                        <button @click="toggleSpacesList">
                            <svg v-if="showSpacesList" aria-hidden="true"
                                class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                            <svg v-else aria-hidden="true"
                                class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                        <RouterLink :to="{ name: 'spaces' }" @click="spaceIdStore.spaceId = null"
                            class="spaces-color flex hover:bg-gray-100 items-center p-2 text-gray-900 rounded-lg dark:text-white dark:hover:bg-gray-700">
                            <svg aria-hidden="true"
                                class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                            <span class="ml-3">Espaces</span>
                        </RouterLink>
                    </div>
                    <transition name="fade">
                        <ul v-if="showSpacesList" class="ml-14">
                            <li v-for="space in spacesStore.spaces" class="cursor-pointer my-2">
                                <div class="flex">
                                    <button @click="toggleManualsList(space.id)">
                                        <svg v-if="isManualsListVisible(space.id)" aria-hidden="true"
                                            class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        <svg v-else aria-hidden="true"
                                            class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                    <span @click="getManuals(space.id, space.title)">{{ space.title }}</span>
                                </div>
                                <transition name="fade">
                                    <ul v-if="isManualsListVisible(space.id)" class="ml-14">
                                        <li v-for="manual in space.manuals" class="cursor-pointer">
                                            <!-- getArticles -->
                                            <span @click="getManuals(space.id, space.title)">{{ manual.title }}</span>
                                        </li>
                                    </ul>
                                </transition>
                            </li>
                        </ul>
                    </transition>
                </li>
                <li v-if="userStore.isAdmin">
                    <RouterLink :to="{ name: 'manuals' }" @click="spaceIdStore.spaceId = null"
                        class="manuals-color flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg aria-hidden="true"
                            class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        <span class="manual-color flex-1 ml-3 whitespace-nowrap">Manuals</span>
                        <!-- <span class="inline-flex items-center justify-center px-2 ml-3 text-sm font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span> -->
                    </RouterLink>
                </li>
                <li v-if="userStore.isAdmin">
                    <RouterLink :to="{ name: 'articles' }" @click="spaceIdStore.spaceId = null"
                        class="articles-color flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg aria-hidden="true"
                            class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z">
                            </path>
                            <path
                                d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z">
                            </path>
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Articles</span>
                        <!-- <span class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span> -->
                    </RouterLink>
                </li>
                <li v-if="userStore.isAdmin">
                    <RouterLink :to="{ name: 'users' }" @click="spaceIdStore.spaceId = null"
                        class="users-color flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg fill="currentColor" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                            class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z">
                            </path>
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Gestion des utilisateurs</span>
                    </RouterLink>
                </li>
                <li v-if="userStore.isAdmin">
                    <RouterLink :to="{ name: 'spaces' }" @click="spaceIdStore.spaceId = null"
                        class="users-management-color flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                            class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z">
                            </path>
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Gestion des articles</span>
                    </RouterLink>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg mt-14 dark:border-gray-700">
            <slot />
        </div>
    </div>
</template>

<script setup>

import { RouterLink } from 'vue-router';
import { useUserStore } from '../../stores/user-store';
import { onMounted, ref, watch } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import { useSpacesStore } from '../../stores/spaces-store';
import { useManualsStore } from '../../stores/manuals-store';
import { useUsersStore } from '../../stores/users-store';
import { useSpaceIdStore } from '../../stores/space-id-store';

const router = useRouter();
const userStore = useUserStore();
const usersStore = useUsersStore();
const spacesStore = useSpacesStore();
const manualsStore = useManualsStore();

const showSpacesList = ref(false);
const showManualsList = ref(false);

const toggleSpacesList = () => {
    showSpacesList.value = !showSpacesList.value;
    showManualsList.value = null
}

const toggleManualsList = (spaceId) => {
    showManualsList.value = showManualsList.value === spaceId ? null : spaceId;
}

const isManualsListVisible = (spaceId) => {
    return showManualsList.value === spaceId;
}

// redirectToSpaceManualsPage
const getManuals = async (spaceId, spaceTitle) => {
    try {
        spaceIdStore.spaceId = spaceId;
        spaceIdStore.spaceTitle = spaceTitle;
        router.push({ name: 'manuals' })
    } catch (error) {
        console.log('ERR\N\N', error);
    }
}

// watch(() => spacesStore.spaces, (newValue, oldValue) => {
//     refreshManuals();
// });
// watch(() => manualsStore.manuals, (newValue, oldValue) => {
//     // refreshManuals();
// });


const spaceId = ref();
const spaceIdStore = useSpaceIdStore();


onMounted(async () => {
    if (spacesStore.spaces.length == 0) {
        await spacesStore.getSpaces();
    }
});

let menuOpen = ref(false)

const logout = async () => {
    await axios.post('/logout')
        .then(response => {
            try {
                userStore.clearUser();
                console.log('User Store Cleard :>', userStore);

                spacesStore.clearSpaces();
                console.log('Space Store Cleard :>', userStore);

                spaceIdStore.clearSpaceId();
                console.log('User Store Cleard :>', spaceIdStore);

                manualsStore.clearManuals();
                console.log('Manual Store Cleard :>', userStore);

                usersStore.clearUsers();
                console.log('Users Store Cleard :>', usersStore);
                console.log('logout success');
            } catch (error) {
                console.log('logout failed', error);
            }
            router.push({ name: 'login' })
            // Show Success Message
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Déconnexion réussie',
                showConfirmButton: false,
                timer: 1000
            })
        }).catch(error => {
            // Show Faild Message
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: 'Échec de la déconnexion',
                showConfirmButton: false,
                timer: 1000
            })
            console.log('THE ERROR', error);
        });
}


const getImageUrl = (photo) => {
    const baseUrl = "http://localhost:8000/storage/";
    return baseUrl + photo; // Concatenating the base URL and the photo variable
}
</script>

<style lang="css" scoped>
/* .spaces-color {
    color: white;
    background-color: #663399;
}

.manuals-color {
    color: white;
    background-color: #ff7f50;
}

.articles-color {
    color: white;
    background-color: #ff4162;
}

.users-color {

}

.users-management-color {

} */

.fade-enter-active,
.fade-leave-active {
    transition: opacity .3s ease;
}

.fade-enter-from,
.fade-leave-to {
    /* transition: opacity .5s ease; */

    opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
    /* transition: opacity .5s ease; */

    opacity: 1;
}
</style>

