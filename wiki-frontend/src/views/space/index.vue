<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <RouterLink :to="{ name: 'spaces' }" class="hover:text-gray-900  text-base rounded">
                <svg aria-hidden="true"
                    class="w-6 h-8 inline text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                    </path>
                </svg>
            </RouterLink>
            <!-- Add btn and search -->
            <div class="flex items-center mb-4 gap-4">
                <button v-if='userStore.isAdmin' @click="openModal" type="submit"
                    class="px-4 py-2 w-2/12 text-white text-sm bg-green-500 rounded-md hover:bg-green-600 ">
                    Ajouter Espace
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
                        placeholder="Search">
                </div>
            </div>

            <LoadingAnimation v-if="spacesStore.spaces.length == 0" />
            <div v-else
                class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl-custom-grid-cols-4 xl:grid-cols-4 gap-4 mb-4 min-h-[65vh]">

                <div v-if="!searchInput" v-for="space in spacesStore.spaces" :key="space.id"
                    class="flex flex-col rounded-md justify-between gap-2 rounded h-60 bg-gray-50 shadow-md dark:bg-gray-800 p-4">

                    <!-- members count -->
                    <div class="border-b flex justify-between gap-2 ">
                        <span>
                            {{ space.users.length }}<i class="ri-group-line"></i>
                        </span>
                        <i class="ri-information-line cursor-pointer" :title="space.description"></i>
                    </div>

                    <!-- LOGO -->
                    <button @click="toSpace(space.id)"
                        class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
                        :style="{ backgroundColor: space.color }">
                        <span class="text-2xl">{{
                            space.title[0].toUpperCase() }}
                        </span>
                    </button>

                    <div class="flex justify-center items-center">
                        <!-- TITLE -->
                        <button @click="toSpace(space.id)" class="font-bold hover:text-blue-500 text-ellipsis"
                            :title="space.title">
                            {{ space.title.length > 20 ? space.title.slice(0, 20) + '...' : space.title }}
                        </button>

                        <!-- BTNS -->
                        <div class="ml-auto flex gap-4" v-if='userStore.isAdmin'>
                            <button @click="openEditSpaceModal(space.id, space.title, space.description)"
                                class="text-lg text-blue-500 rounded-md hover:text-blue-700 sm:text-sm md:text-base">
                                <i class="ri-pencil-line"></i>
                            </button>
                            <button @click="deleteSpace(space.id)"
                                class="text-lg text-red-500 rounded-md hover:text-red-700 sm:text-sm md:text-base">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Author -->
                    <div class="flex justify-between text-sm">
                        <span>By {{ getCreatorName(space.users) }}</span>
                        <!-- share space -->
                        <i v-if="userStore.isAdmin == true" class="ri-share-line cursor-pointer"
                            @click="openShareModal(space.id)"></i>
                        <!-- Share space with users Modal-->
                        <div v-show="isShareModalOpen"
                            class="fixed z-10 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
                            <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
                                <div class="flex flex-col items-start justify-between p-6 space-y-4 w-96">
                                    <div class="text-lg font-bold text-gray-900 self-center">Users</div>
                                    <div class="w-full">
                                        <label for="spaces" class="block text-sm font-medium text-gray-700 mb-1"></label>
                                        <input type="text" v-model="searchQuery" placeholder="Search users"
                                            class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md mb-2" />
                                        <select multiple v-model="selectedUsers" id="spaces" name="spaces[]"
                                            class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                                            <option v-for="user in filteredUsers" :value="user.id" :key="user.id">{{
                                                user.name
                                            }}
                                            </option>
                                        </select>
                                        <div class="flex justify-between">
                                            <button class="p-2 mt-2 bg-blue-600 hover:bg-blue-700 text-white rounded"
                                                @click="shareSpaceWithUsers()">Soumettre</button>

                                            <button class="p-2 mt-2 bg-red-600 hover:bg-red-700 text-white rounded"
                                                @click="closeShareModal()">Fermer</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div v-else v-for="space in filteredSpaces"
                    class="flex flex-col rounded-md justify-between gap-2 rounded h-60 bg-gray-50 shadow-md dark:bg-gray-800 p-4">

                    <!-- members count -->
                    <div class="border-b flex justify-between gap-2 ">
                        <span>
                            {{ space.users.length }}<i class="ri-group-line"></i>
                        </span>
                        <i class="ri-information-line cursor-pointer" :title="space.description"></i>
                    </div>

                    <!-- LOGO -->
                    <button @click="toSpace(space.id)"
                        class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
                        :style="{ backgroundColor: space.color }">
                        <span class="text-2xl">{{
                            space.title[0].toUpperCase() }}
                        </span>
                    </button>

                    <div class="flex justify-center items-center">
                        <!-- TITLE -->
                        <button @click="toSpace(space.id)" class="font-bold hover:text-blue-500 text-ellipsis"
                            :title="space.title">
                            {{ space.title.length > 20 ? space.title.slice(0, 20) + '...' : space.title }}
                        </button>>

                        <!-- BTNS -->
                        <div class="ml-auto flex gap-4" v-if='userStore.isAdmin'>
                            <button @click="openEditSpaceModal(space.id, space.title, space.description)"
                                class="text-lg text-blue-500 rounded-md hover:text-blue-700 sm:text-sm md:text-base">
                                <i class="ri-pencil-line"></i>
                            </button>
                            <button @click="deleteSpace(space.id)"
                                class="text-lg text-red-500 rounded-md hover:text-red-700 sm:text-sm md:text-base">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Author -->
                    <div class="flex justify-between text-sm">
                        <span>By {{ getCreatorName(space.users) }}</span>
                        <!-- share space -->
                        <i v-if="userStore.isAdmin == true" class="ri-share-line cursor-pointer"
                            @click="openShareModal(space.id)"></i>
                        <!-- Share space with users Modal-->
                        <div v-show="isShareModalOpen"
                            class="fixed z-10 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
                            <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
                                <div class="flex flex-col items-start justify-between p-6 space-y-4 w-96">
                                    <div class="text-lg font-bold text-gray-900 self-center">Users</div>
                                    <div class="w-full">
                                        <label for="spaces" class="block text-sm font-medium text-gray-700 mb-1"></label>
                                        <input type="text" v-model="searchQuery" placeholder="Search users"
                                            class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md mb-2" />
                                        <select multiple v-model="selectedUsers" id="spaces" name="spaces[]"
                                            class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                                            <option v-for="user in filteredUsers" :value="user.id" :key="user.id">{{
                                                user.name
                                            }}
                                            </option>
                                        </select>
                                        <div class="flex justify-between">
                                            <button class="p-2 mt-2 bg-blue-600 hover:bg-blue-700 text-white rounded"
                                                @click="shareSpaceWithUsers()">Soumettre</button>

                                            <button class="p-2 mt-2 bg-red-600 hover:bg-red-700 text-white rounded"
                                                @click="closeShareModal()">Fermer</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- Modal  Add Space form-->
            <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center " @click.self="closeModal">
                <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
                    <h1 class="mb-4 text-2xl font-semibold">Ajouter un espace</h1>
                    <form @submit.prevent="addSpace" class="space-y-4 ">
                        <div>
                            <label for="title" class="block mb-2 font-medium text-gray-700">Titre</label>
                            <input v-model="form.title" type="text" id="title" name="title"
                                class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a title" required>
                        </div>
                        <div>
                            <label for="description" class="block mb-2 font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description" id="description" name="description" rows="4"
                                class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a description"
                                required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Soumettre</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal  Edit Space form-->
            <div v-if="isEditSpaceModalOpen" class="fixed inset-0 z-50 flex items-center justify-center "
                @click.self="closeEditSpaceModal">
                <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
                    <h1 class="mb-4 text-2xl font-semibold">Modifier l'espace</h1>
                    <form @submit.prevent="editSpace" class="space-y-4 ">
                        <div>
                            <label for="title" class="block mb-2 font-medium text-gray-700">Titre</label>
                            <input v-model="form.title" type="text" id="title" name="title"
                                class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a title" required>
                        </div>
                        <div>
                            <label for="description" class="block mb-2 font-medium text-gray-700">Description</label>
                            <textarea v-model="form.description" id="description" name="description" rows="4"
                                class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a description"
                                required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Editer</button>
                        </div>
                    </form>
                </div>
            </div>
        </DashboardLayout>
    </div>
</template>


<script setup>
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import SearchInput from '../../components/Partials/SearchInput.vue';
import Swal from 'sweetalert2';
import Dropdown from '../../components/global/Dropdown.vue';
import { useUserStore } from '../../stores/user-store';

import { ref, watchEffect, onMounted, watch } from 'vue';
import axios from 'axios';
import { RouterView, useRouter } from 'vue-router';
import LoadingAnimation from '../../components/global/LoadingAnimation.vue';
import { useSpacesStore } from '../../stores/spaces-store';
import { useManualsStore } from '../../stores/manuals-store';
import { useSpaceIdStore } from '../../stores/space-id-store';
import { useParamsStore } from '../../stores/params-store';
import { useUsersStore } from '../../stores/users-store';
import { computed } from '@vue/reactivity';

axios.defaults.withCredentials = true;

const spaceIdStore = useSpaceIdStore();
const userStore = useUserStore();
const spacesStore = useSpacesStore();
const manualsStore = useManualsStore();
const paramsStore = useParamsStore();

const selectedUsers = ref([]);
const searchQuery = ref('');
const usersStore = useUsersStore();
const isShareModalOpen = ref(false);
const getSpaces = onMounted(async () => {
    spaceIdStore.spaceId = null;
    await spacesStore.getSpaces();
    await usersStore.getUsers();
});

// -----------------------------------------------------------------------------

const spaceId = ref(null);
function hasSpaceId(user, spaceId) {
    if (Array.isArray(user.spaces)) {
        return user.spaces.some(space => space.id === spaceId);
    }
    return false;
}
const filteredUsers = computed(() => {
    return usersStore.users.filter(user => {
        if (user.status === 1 && user.role != 'admin' && !hasSpaceId(user, spaceId.value)) {
            return user.name.toLowerCase().includes(searchQuery.value.toLowerCase());
        }
    });
});


function openShareModal(space_id) {
    isShareModalOpen.value = true;
    spaceId.value = space_id
}
function closeShareModal() {
    isShareModalOpen.value = false;
    spaceId.value = null;
    searchQuery.value = '';
    selectedUsers.value = [];
}

const shareSpaceWithUsers = async () => {
    if (selectedUsers.value.length > 0) {
        console.log(selectedUsers);
        // return;
        await axios.post('/api/assignUserToSpace', {
            space_id: spaceId.value,
            users: selectedUsers.value,
        }).then(response => {
            console.log(response);
            getSpaces();
            closeShareModal();
        }).catch(error => {
            console.log(error);
        })
    }


}
// -------------------------------------------------------

const isModalOpen = ref(false);
const isEditSpaceModalOpen = ref(false);
const modalRef = ref(null);

// Add space model
const openModal = () => {
    form.value.title = '';
    form.value.description = '';
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.value.title = '';
    form.value.description = '';
};

// Edit Space Modal
const openEditSpaceModal = (spaceId, spaceTitle, spaceDescription) => {
    form.value.id = spaceId;
    form.value.title = spaceTitle;
    form.value.description = spaceDescription;
    isEditSpaceModalOpen.value = true;
};
const closeEditSpaceModal = () => {
    isEditSpaceModalOpen.value = false;
};

const form = ref({
    id: null,
    title: null,
    description: null,
})


const getCreatorName = (users) => {
    let creatorName = '';
    users.forEach(user => {
        if (user.pivot.is_creator == 1) {
            creatorName = user.name;
        }
    });
    return creatorName;
}
// Sapce CRUD + search

// Add Space
const addSpace = async () => {
    await axios.post('/api/spaces', {
        title: form.value.title,
        description: form.value.description
    }).then(async (response) => {
        // Reset form fields after successful submission
        closeModal();
        form.value.id = '';
        form.value.title = '';
        form.value.description = '';
        // spacesStore.setSpaces(response);
        await spacesStore.getSpaces();
        // Swal.fire({
        //     position: 'top-end',
        //     icon: 'success',
        //     width: '25rem',
        //     title: 'l\'espace ajouté avec succès',
        //     showConfirmButton: false,
        //     timer: 1500,
        // })
    }).catch(error => {
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'error d\'insertion',
            showConfirmButton: false,
            timer: 1500
        })
        console.error(error);
    });
};

// Edit Space
const editSpace = async () => {
    await axios.post(`/api/spaces/${form.value.id}`, {
        _method: 'PUT',
        title: form.value.title,
        description: form.value.description
    }).then(async (response) => {
        // Reset form fields after successful submission
        closeEditSpaceModal();
        form.value.is = '';
        form.value.title = '';
        form.value.description = '';
        await spacesStore.getSpaces();

        // Swal.fire({
        //     position: 'top-end',
        //     icon: 'success',
        //     width: '25rem',
        //     title: 'l\'espace mis à jour avec succès',
        //     showConfirmButton: false,
        //     timer: 1500,
        // })
    }).catch(error => {
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'échec de la mise à jour de l\'espace',
            showConfirmButton: false,
            timer: 1500
        })
        console.error(error);
    });
}

// delete Space
const deleteSpace = async (spaceId) => {
    Swal.fire({
        title: "Êtes-vous sûr(e) ?",
        text: "Une fois supprimé, cet élément ne pourra pas être récupéré !",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: 'Confirmer',
        denyButtonText: `Annuler`,
    }).then(async (result) => {
        if (result.isConfirmed) {
            await axios.delete(`/api/spaces/${spaceId}`)
                .then(async (response) => {
                    await spacesStore.getSpaces();

                }).catch(error => {
                    console.log(error);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Échec de la suppression, actualisez la page et réessayez',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
                );
        }
    })
}

// Search
// Find a space by title
const searchInput = ref(null)
watch(searchInput, () => {
    let isEmpty = /^\s*$/.test(searchInput.value);
    if (isEmpty) {
        searchInput.value = null;
    }
});

const filteredSpaces = computed(() => {
    return spacesStore.spaces.filter(space => {
        return space.title.toLowerCase().includes(searchInput.value.toLowerCase()) || space.description.toLowerCase().includes(searchInput.value.toLowerCase());
    });
});


// Redirect to Space Manauls View
const router = useRouter();

const toSpace = (spaceId) => {
    paramsStore.setSpaceId(spaceId);
    router.push({ name: 'space' });
}

// Close modal when clicking outside
// watchEffect(() => {
//     const handleClickOutside = (event) => {
//         const modalElement = modalRef.value;

//         if (modalElement && !modalElement.contains(event.target)) {
//             closeModal();
//         }
//     };

//     document.addEventListener('click', handleClickOutside);

//     return () => {
//         document.removeEventListener('click', handleClickOutside);
//     };
// });

</script>

<style>
@media (min-width: 1280px) {
    .xl-custom-grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }
}
</style>
