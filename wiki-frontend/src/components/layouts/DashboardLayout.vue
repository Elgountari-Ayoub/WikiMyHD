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
                                aria-expanded="false">
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
                            class="z-50 absolute right-10 top-14 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600">
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
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium gap-1">
                <li @click="setActiveLink('spaces')">
                    <div :class="{ 'bg-gray-200 ': isActiveLink('spaces') }"
                        class="flex spaces-color flex w-full hover:bg-gray-200 hover:text-blue-600 items-center p-2 text-gray-900 rounded-lg dark:text-white dark:hover:bg-gray-700">

                        <button @click="toggleSpacesList">
                            <svg v-if="showSpacesList" aria-hidden="true"
                                class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                            <svg v-else aria-hidden="true"
                                class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>

                        <RouterLink :to="{ name: 'spaces' }" @click="spaceIdStore.spaceId = null"
                            class="spaces-color flex w-full items-center rounded-lg dark:text-white dark:hover:bg-gray-700">
                            <span class="text-2xl ">
                                <!-- <i v-if="isActiveLink('spaces')" class="ri-dashboard-line"></i>
                                <i v-else class="ri-dashboard-fill text-3xl "></i> -->
                                <i class="ri-dashboard-line"></i>
                            </span>
                            <span class="ml-3">Espaces</span>
                        </RouterLink>
                    </div>
                    <!-- Spaces list -->
                    <transition name="fade">
                        <ul v-if="showSpacesList" class="ml-10">
                            <li v-for="space in spacesStore.spaces" class="cursor-pointer my-2  p-2 rounded">
                                <!-- Arrow icon -->
                                <div class="flex hover:text-blue-500">
                                    <button @click="toggleManualsList(space.id)">
                                        <svg v-if="isManualsListVisible(space.id)" aria-hidden="true"
                                            class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                        <svg v-else aria-hidden="true"
                                            class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>

                                    <span @click="toSpace(space.id)" :title="space.title">{{ space.title.length > 15 ? space.title.slice(0, 15)
                                        + '...' :
                                        space.title }}</span>
                                </div>
                                <!-- Manuals list -->
                                <transition name="fade">
                                    <ul v-if="isManualsListVisible(space.id)" class="ml-10 mt-2">
                                        <li v-for="manual in space.manuals" class="cursor-pointer px-2 rounded">
                                            <button @click="toManual(space.id, manual.id)"
                                                class="hover:text-blue-500 text-ellipsis" :title="manual.title">
                                                {{ manual.title.length > 10 ? manual.title.slice(0, 10) + '...' :
                                                    manual.title }}
                                            </button>
                                        </li>
                                    </ul>
                                </transition>
                            </li>
                        </ul>
                    </transition>
                </li>

                <li v-if="userStore.isAdmin" @click="setActiveLink('manuals')">
                    <RouterLink :to="{ name: 'manuals' }" @click="spaceIdStore.spaceId == null"
                        :class="{ 'bg-gray-200': isActiveLink('manuals') }"
                        class="flex items-center p-2  hover:text-blue-600 rounded-lg dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700">
                        <span class="text-2xl ">
                            <i class="ri-folder-3-line"></i>
                            <!-- <i v-if="isActiveLink('manuals')" class="ri-folder-3-line"></i>
                            <i v-else class="ri-folder-3-fill"></i> -->
                        </span>
                        <span class="manual-color flex-1 ml-4 whitespace-nowrap ">Manuels</span>
                        <!-- <span class="inline-flex items-center justify-center px-2 ml-3 text-sm font-medium text-gray-800 bg-gray-200 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span> -->
                    </RouterLink>
                </li>

                <li v-if="userStore.isAdmin" @click="setActiveLink('articles')">
                    <RouterLink :to="{ name: 'articles' }" @click="spaceIdStore.spaceId = null"
                        :class="{ 'bg-gray-200': isActiveLink('articles') }"
                        class="articles-color flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-200 hover:text-blue-600 dark:hover:bg-gray-700">
                        <span class="text-2xl">
                            <i class="ri-article-line"></i>
                            <!-- <i v-if="isActiveLink('articles')" class="ri-article-line"></i>
                            <i v-else class="ri-article-fill"></i> -->
                        </span>
                        <span class="flex-1 ml-4 whitespace-nowrap">Articles</span>
                        <!-- <span class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span> -->
                    </RouterLink>
                </li>

                <li v-if="userStore.isAdmin" @click="setActiveLink('users')">
                    <RouterLink :to="{ name: 'users' }" @click="spaceIdStore.spaceId = null"
                        :class="{ 'bg-gray-200': isActiveLink('users') }"
                        class="users-color flex hover:text-blue-600 items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700">
                        <span class="text-2xl">
                            <i class="ri-user-settings-line"></i>
                            <!-- <i v-if="isActiveLink('users')" class="ri-user-settings-line"></i>
                            <i v-else class="ri-user-settings-fill"></i> -->
                        </span>
                        <span class="flex-1 ml-4 whitespace-nowrap">Gestion des utilisateurs</span>
                    </RouterLink>
                </li>

                <li v-if="userStore.isAdmin" @click="setActiveLink('articlesManagement')">
                    <RouterLink :to="{ name: 'articlesManagement' }" @click="spaceIdStore.spaceId = null"
                        :class="{ 'bg-gray-200': isActiveLink('articlesManagement') }"
                        class="users-management-color hover:text-blue-600 flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700">
                        <span class="text-2xl">
                            <i class="ri-file-settings-line"></i>
                            <!-- <i v-if="isActiveLink('articlesManagement')" class="ri-file-settings-line"></i>
                            <i v-else class="ri-file-settings-fill"></i> -->
                        </span>
                        <span class="flex-1 ml-4 whitespace-nowrap">Gestion des articles</span>
                    </RouterLink>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 relative border-gray-200 border-dashed rounded-lg mt-14 dark:border-gray-700 min-h-[87vh]">
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
import { useSpaceStore } from '../../stores/space-store';
import { useManualStore } from '../../stores/manual-store';
import { useParamsStore } from '../../stores/params-store';

const router = useRouter();
const userStore = useUserStore();
const usersStore = useUsersStore();
const spacesStore = useSpacesStore();
const spaceStore = useSpaceStore();
const manualsStore = useManualsStore();
const manualStore = useManualStore();
const paramsStore = useParamsStore();

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

axios.defaults.withCredentials = true;

const toSpace = (spaceId) => {
    paramsStore.setSpaceId(spaceId);
    // router.push({ name: 'space' });
    window.open("/space", "_self");
}

function toManual(spaceId, manualId) {
    paramsStore.setSpaceId(spaceId);
    paramsStore.setManualId(manualId);

    window.open("/manual", "_self");

    //   router.push({ name: 'manual' })
}

const spaceId = ref();
const spaceIdStore = useSpaceIdStore();

onMounted(async () => {
    setActiveLink();
    if (spacesStore.spaces.length == 0) {
        await spacesStore.getSpaces();
        await manualsStore.getManuals();
    }
});

let menuOpen = ref(false)
let sideBareOpen = ref(false)

const toggleSideBare = () => {
    sideBareOpen.value = !sideBareOpen.value
}

const logout = async () => {
    axios.defaults.withCredentials = true;
    await axios.post('/logout')
        .then(response => {
            try {
                userStore.clearUser();
                console.log('User Store Cleard :>', userStore);

                spacesStore.clearSpaces();
                console.log('Space Store Cleard :>', spacesStore);

                spaceStore.clearSpace();
                console.log('Space Store Cleard :>', spaceStore);

                spaceIdStore.clearSpaceId();
                console.log('User Store Cleard :>', spaceIdStore);

                manualsStore.clearManuals();
                console.log('Manual Store Cleard :>', manualsStore);

                manualStore.clearManual();
                console.log('Manual Store Cleard :>', manualStore);

                usersStore.clearUsers();
                console.log('Users Store Cleard :>', usersStore);

                paramsStore.clear();
                console.log('Params Store Cleard :>', paramsStore);

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


const setActiveLink = (targetLinkName = '') => {
    paramsStore.activeLink = targetLinkName;

}
// setActiveLink(null);
const isActiveLink = (targetLinkName) => {
    return paramsStore.activeLink === targetLinkName ? true : false;
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

