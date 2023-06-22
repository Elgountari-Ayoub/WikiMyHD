
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <div>
                <!-- Add btn and search -->
                <div class="flex items-center m-auto max-w-[50vw] mb-4 sticky gap-4 z-96 ">
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

                <LoadingAnimation v-if="manualsStore.manuals.length == 0" />
                <div v-else
                    class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl-custom-grid-cols-4 xl:grid-cols-4 gap-4 mb-4 min-h-[65vh]">

                    <div v-if="!searchInput" v-for="manual in manualsStore.manuals"
                        class="flex flex-col shadow-sm justify-between gap-2 rounded h-60 bg-gray-50 dark:bg-gray-800 p-4">
                    <!-- <span class="font-blod p-2 border-b flex justify-between " >
                        
                        <span class="ml-auto">{{ getCreatorName(manual.users) }}</span>
                                                                        </span> -->
                        <div class="border-b flex justify-between gap-2 text-sm ">
                            <span v-if="!spaceIdStore.spaceId">{{ manual.space.title }}</span>
                            <span class="flex gap-2">
                                {{ manual.users.length }}<i class="ri-group-line"></i>
                                <i class="ri-information-line cursor-pointer" :title="manual.description" @click="showDescription(manual.description)"></i>
                            </span>
                        </div>

                        <button @click="toManual(manual.space.id, manual.id)"
                            class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
                            :style="{ backgroundColor: manual.color }">
                            <span class="text-2xl ">{{ manual.title[0].toUpperCase() }}
                            </span>
                        </button>

                        <div class="flex justify-center items-center">
                            <!-- Title -->
                            <button @click="toManual(manual.space.id, manual.id)"
                                class="font-bold hover:text-blue-500 text-ellipsis" :title="manual.title">
                                {{ manual.title.length > 20 ? manual.title.slice(0, 20) + '...' : manual.title }}
                            </button>

                            <!-- Modal  Edit/Delete Manual Buttons-->
                            <div class="ml-auto flex gap-4"
                                v-if='getCreatorId(manual.users) == userStore.id || userStore.isAdmin'>
                                <button @click="openEditManualModal(manual.id, manual.title, manual.description)"
                                    class="text-lg text-blue-500 rounded-md hover:text-blue-700 sm:text-sm md:text-base">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <button @click="deleteManual(manual.id)"
                                    class="text-lg text-red-500 rounded-md hover:text-red-700 sm:text-sm md:text-base">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex -justify-between text-sm">
                            <span class="mr-auto text-sm">By {{ getCreatorName(manual.users) }}</span>
                            <!-- share space -->
                            <i v-if="userStore.isAdmin == true" class="ri-share-line cursor-pointer"
                                @click="openShareModal(manual.id)"></i>
                            <!-- Share space with users Modal-->
                            <div v-show="isShareModalOpen"
                                class="fixed z-10 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
                                <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
                                    <div class="flex flex-col items-start justify-between p-6 space-y-4 w-96">
                                        <div class="text-lg font-bold text-gray-900 self-center">Users</div>
                                        <div class="w-full">
                                            <label for="spaces"
                                                class="block text-sm font-medium text-gray-700 mb-1"></label>
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
                                                    @click="shareManualWithUsers()">Soumettre</button>

                                                <button class="p-2 mt-2 bg-red-600 hover:bg-red-700 text-white rounded"
                                                    @click="closeShareModal()">Fermer</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else v-for="manual in filteredManuals"
                        class="flex flex-col shadow-sm justify-between gap-2 rounded h-60 bg-gray-50 dark:bg-gray-800 p-4">
                    <!-- <span class="font-blod p-2 border-b flex justify-between " >
                        
                        <span class="ml-auto">{{ getCreatorName(manual.users) }}</span>
                                                                        </span> -->
                        <div class="border-b flex justify-between gap-2 text-sm ">
                            <span v-if="!spaceIdStore.spaceId">{{ manual.space.title }}</span>
                            <span class="flex gap-2">
                                {{ manual.users.length }}<i class="ri-group-line"></i>
                                <i class="ri-information-line cursor-pointer" :title="manual.description" @click="showDescription(manual.description)"></i>

                            </span>
                        </div>

                        <button @click="toManual(manual.space.id, manual.id)"
                            class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
                            :style="{ backgroundColor: manual.color }">
                            <span class="text-2xl ">{{ manual.title[0].toUpperCase() }}
                            </span>
                        </button>

                        <div class="flex justify-center items-center">
                            <!-- Title -->
                            <button @click="toManual(manual.space.id, manual.id)"
                                class="font-bold hover:text-blue-500 text-ellipsis" :title="manual.title">
                                {{ manual.title.length > 20 ? manual.title.slice(0, 20) + '...' : manual.title }}
                            </button>

                            <!-- Modal  Edit/Delete Manual Buttons-->
                            <div class="ml-auto flex gap-4"
                                v-if='getCreatorId(manual.users) == userStore.id || userStore.isAdmin'>
                                <button @click="openEditManualModal(manual.id, manual.title, manual.description)"
                                    class="text-lg text-blue-500 rounded-md hover:text-blue-700 sm:text-sm md:text-base">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <button @click="deleteManual(manual.id)"
                                    class="text-lg text-red-500 rounded-md hover:text-red-700 sm:text-sm md:text-base">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex -justify-between text-sm">
                            <span class="mr-auto text-sm">By {{ getCreatorName(manual.users) }}</span>
                            <!-- share space -->
                            <i v-if="userStore.isAdmin == true" class="ri-share-line cursor-pointer"
                                @click="openShareModal(manual.id)"></i>
                            <!-- Share space with users Modal-->
                            <div v-show="isShareModalOpen"
                                class="fixed z-10 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
                                <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
                                    <div class="flex flex-col items-start justify-between p-6 space-y-4 w-96">
                                        <div class="text-lg font-bold text-gray-900 self-center">Users</div>
                                        <div class="w-full">
                                            <label for="spaces"
                                                class="block text-sm font-medium text-gray-700 mb-1"></label>
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
                                                    @click="shareManualWithUsers()">Soumettre</button>

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


                <!-- Modal  Add Manual form-->
                <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center "
                    @click.self="closeModal">
                    <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
                        <h1 class="mb-4 text-2xl font-semibold">Ajouter un manual</h1>
                        <form @submit.prevent="addManual" class="space-y-4 ">
                            <div>
                                <label for="title" class="block mb-2 font-medium text-gray-700">Titre</label>
                                <input v-model="form.title" type="text" id="title" name="title"
                                    class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a title"
                                    required>
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

                <!-- Modal  Edit Manual form-->
                <div v-if="isEditManualModalOpen" class="fixed inset-0 z-50 flex items-center justify-center "
                    @click.self="closeEditManualModal">
                    <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
                        <h1 class="mb-4 text-2xl font-semibold">Modifier le manual</h1>
                        <form @submit.prevent="editManual" class="space-y-4 ">
                            <div>
                                <label for="title" class="block mb-2 font-medium text-gray-700">Titre</label>
                                <input v-model="form.title" type="text" id="title" name="title"
                                    class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a title"
                                    required>
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
            </div>
        </DashboardLayout>
    </div>
</template>


<script setup>
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import SearchInput from '../../components/Partials/SearchInput.vue';
import Swal from 'sweetalert2';
import Dropdown from '../../components/global/Dropdown.vue';
import LoadingAnimation from '../../components/global/LoadingAnimation.vue'

import { ref, watchEffect, onMounted, watch } from 'vue';
import axios, { Axios } from 'axios';
import { RouterView, useRouter } from 'vue-router';
// Store [pinia]
import { useUserStore } from '../../stores/user-store';
import { useUsersStore } from '../../stores/users-store';
import { useSpacesStore } from '../../stores/spaces-store';
import { useManualsStore } from '../../stores/manuals-store';
import { useSpaceIdStore } from '../../stores/space-id-store';
import { useParamsStore } from '../../stores/params-store';
import { computed } from '@vue/reactivity';

const userStore = useUserStore();
const manualsStore = useManualsStore();
const spacesStore = useSpacesStore();
const spaceIdStore = useSpaceIdStore();
const paramsStore = useParamsStore();


const router = useRouter();

const selectedUsers = ref([]);
const searchQuery = ref('');
const usersStore = useUsersStore();
const isShareModalOpen = ref(false);

axios.defaults.withCredentials = true;

const spaceId = ref();
const getManuals = onMounted(async () => {
    manualsStore.getManuals();
    spacesStore.getSpaces();
    usersStore.getUsers();

});

// -------------------------------------------
const manualId = ref(null);
function hasManualId(user, manualId) {
    if (Array.isArray(user.manuals)) {
        return user.manuals.some(manual => manual.id === manualId);
    }
    return false;
}

// return user.spaces.some(space => space.id === spaceId);
const filteredUsers = computed(() => {
    return usersStore.users.filter(user => {
        if (user.status === 1 && user.role != 'admin' && !hasManualId(user, manualId.value)) {
            return user.name.toLowerCase().includes(searchQuery.value.toLowerCase());
        }
    });
});

function openShareModal(space_id) {
    isShareModalOpen.value = true;
    manualId.value = space_id
}
function closeShareModal() {
    isShareModalOpen.value = false;
    manualId.value = null;
    searchQuery.value = '';
    selectedUsers.value = [];
}

const shareManualWithUsers = async () => {
    if (selectedUsers.value.length > 0) {
        // return;
        await axios.post('/api/assignUserToManual', {
            manual_id: manualId.value,
            users: selectedUsers.value,
        }).then(response => {
            console.log(response);
            closeShareModal();
            getManuals()
        }).catch(error => {
            console.log(error);
        })
    }


}
// -------------------------------------------


// Search---------------------------------------
const searchInput = ref(null)
watch(searchInput, () => {
    let isEmpty = /^\s*$/.test(searchInput.value);
    if (isEmpty) {
        searchInput.value = null;
    }
});

const filteredManuals = computed(() => {
    return manualsStore.manuals.filter(manual => {
        return manual.title.toLowerCase().includes(searchInput.value.toLowerCase()) || manual.description.toLowerCase().includes(searchInput.value.toLowerCase());
    });
});
// Search---------------------------------------

const getCreatorId = (users) => {
    let creatorId = -1;
    users.forEach(user => {
        if (user.pivot.is_creator == 1) {
            creatorId = user.id;
        }
    });
    return creatorId;
}

const getCreatorName = (users) => {
    let creatorName = -1;
    users.forEach(user => {
        if (user.pivot.is_creator == 1) {
            creatorName = user.name;
        }
    });
    return creatorName;
}

const isModalOpen = ref(false);
const isEditManualModalOpen = ref(false);

const modalRef = ref(null);

// Add manual model
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

const form = ref({
    id: null,
    title: null,
    description: null,
})

// MANAUL CRUD + SEARCH

// Add Manual
const addManual = async () => {
    try {
        const response = await axios.post('/api/manuals', {
            space_id: spaceIdStore.spaceId,
            title: form.value.title,
            description: form.value.description
        });
        closeModal();
        // Reset form fields after successful submission
        form.value.is = '';
        form.value.title = '';
        form.value.description = '';

        // Swal.fire({
        //     position: 'top-end',
        //     icon: 'success',
        //     width: '25rem',
        //     title: 'le manuel ajouté avec succès',
        //     showConfirmButton: false,
        //     timer: 1500,
        // })
        getManuals();
    } catch (error) {
        // Handle the error here if needed
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'error d\'insertion',
            showConfirmButton: false,
            timer: 1500
        })
        console.error(error);
    }
};

// Edit Manual
const editManual = async () => {
    try {
        const response = await axios.post(`/api/manuals/${form.value.id}`, {
            _method: 'PUT',
            title: form.value.title,
            description: form.value.description
        });
        closeEditManualModal();
        // Reset form fields after successful submission
        form.value.is = '';
        form.value.title = '';
        form.value.description = '';

        // Swal.fire({
        //     position: 'top-end',
        //     icon: 'success',
        //     width: '25rem',
        //     title: 'le manuel mis à jour avec succès',
        //     showConfirmButton: false,
        //     timer: 1500,
        // })
        getManuals();
    } catch (error) {
        // Handle the error here if needed
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'échec de la mise à jour de le manuel',
            showConfirmButton: false,
            timer: 1500
        })
        console.error(error);
    }
}

// delete Manual
const deleteManual = async (manualId) => {
    Swal.fire({
        title: "Êtes-vous sûr(e) ?",
        text: "Une fois supprimé, cet élément ne pourra pas être récupéré !",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: 'Confirmer',
        denyButtonText: `Annuler`,
    }).then(async (result) => {
        if (result.isConfirmed) {
            await axios.delete(`/api/manuals/${manualId}`).then(response => {
                getManuals();
            }).catch(async (error) => {
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Échec de la suppression, actualisez la page et réessayez',
                    showConfirmButton: false,
                    timer: 1500
                })
            })
        }
    }
    )
    // await axios.delete(`/api/manuals/${manualId}`).then(repsonse => {

    // }).catch(error => {
    //     Swal.fire({
    //         position: 'top-end',
    //         icon: 'warning',
    //         title: 'Échec de la suppression, actualisez la page et réessayez',
    //         showConfirmButton: false,
    //         timer: 1500
    //     })
    // });
    // getManuals();


}



// HELPERS-------------------------
// Edit Manual Modal
function toManual(spaceId, manualId) {
    paramsStore.setSpaceId(spaceId);
    paramsStore.setManualId(manualId);

    router.push({ name: 'manual' })
}


const openEditManualModal = (manualId, manualTitle, manualDescription) => {
    form.value.id = manualId;
    form.value.title = manualTitle;
    form.value.description = manualDescription;
    isEditManualModalOpen.value = true;
};
const closeEditManualModal = () => {
    isEditManualModalOpen.value = false;
};
// Close modal when clicking outside
watchEffect(() => {
    const handleClickOutside = (event) => {
        const modalElement = modalRef.value;

        if (modalElement && !modalElement.contains(event.target)) {
            closeModal();
        }
    };

    document.addEventListener('click', handleClickOutside);

    return () => {
        document.removeEventListener('click', handleClickOutside);
    };
});


const showDescription = (description) => {
    Swal.fire({
        text: description
    })
}
const getImageUrl = (photo) => {
    const baseUrl = "http://localhost:8000/storage/";
    return baseUrl + photo; // Concatenating the base URL and the photo variable
}

</script>

<style></style>