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
                <!-- <SearchInput /> -->
                <form class="relative z-10 flex items-center w-8/12 m-auto" @submit.prevent="search">
                    <div class="relative w-full m-auto">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input @change="search" v-model="searchInput" name="search" type="text" id="simple-search"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:border-gray-500 "
                            placeholder="Search">
                    </div>
                    <button type="submit"
                        class="p-2.5 ml-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="sr-only">Search</span>
                    </button>
                </form>
            </div>

            <LoadingAnimation v-if="spacesStore.spaces.length == 0" />
            <div v-else
                class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl-custom-grid-cols-4 xl:grid-cols-4 gap-4 mb-4">

                <div v-for="space in spacesStore.spaces" :key="space.id"
                    class="flex flex-col rounded-md justify-between gap-2 rounded h-52 bg-gray-50 shadow dark:bg-gray-800">

                    <RouterLink :to="{ name: 'space', params: { id: `${space.id}` } }"
                        class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
                        :style="{ backgroundColor: space.color }">
                        <span class="text-2xl ">{{
                            space.title[0].toUpperCase() }}</span>
                    </RouterLink>

                    <div class="flex justify-center px-8 py-2 items-center">

                        <RouterLink :to="{ name: 'space', params: { id: `${space.id}` } }" class="hover:text-blue-500">{{
                            space.title.slice(0, 100) }}
                        </RouterLink>
                        <!-- Modal  Edit/Delete Space Buttons-->
                        <Dropdown class="ml-auto cursor-pointer" v-if='userStore.isAdmin'>
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
                                <div class="px-2 rounded-md shadow-2xl flex flex-col gap-2" ref="options">
                                    <button @click="openEditSpaceModal(space.id, space.title, space.description)"
                                        class="px-2 py-1 text-white bg-blue-500 rounded-md hover:bg-blue-600 sm:text-sm md:text-base">
                                        Editer
                                    </button>
                                    <!-- {{ space.id }} -->
                                    <button @click="deleteSpace(space.id)"
                                        class="px-2 py-1 text-white bg-red-500 rounded-md hover:bg-red-600 sm:text-sm md:text-base">
                                        Supprimer
                                    </button>
                                </div>
                            </template>
                        </Dropdown>

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
                                class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Submit</button>
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

axios.defaults.withCredentials = true;

const spaceIdStore = useSpaceIdStore();

const userStore = useUserStore();
const spacesStore = useSpacesStore();
const manualsStore = useManualsStore();
onMounted(async () => {
    spaceIdStore.spaceId = null;
    if (spacesStore.spaces.length == 0) {
        await spacesStore.getSpaces();
    }
});

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

// Sapce CRUD + search

// Add Space
const addSpace = async () => {
    await axios.post('/api/spaces', {
        title: form.value.title,
        description: form.value.description
    }).then(async (response) => {
        console.log('response gg', response);
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
    // show a sweet alert for the confirmation
    console.log(spaceId);
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
    // Swal.fire({
    //     position: 'top-end',
    //     icon: 'success',
    //     title: 'L\'espace a été supprimé avec succès',
    //     showConfirmButton: false,
    //     timer: 1500

    // })

}

// Search
const searchInput = ref(null)
watch(searchInput, () => {
    setTimeout(search, 1000);

});
// Find a space by title
const spaces = ref([]);
const search = async () => {
    try {
        let isEmpty = /^\s*$/.test(searchInput.value);
        if (isEmpty) {
            spacesStore.getSpaces();
            console.log();
        }
        const response = await axios.get(`/api/spaces/search/${searchInput.value}`);

        // Handle the response here if needed
        console.log(response.data.spaces);
        spacesStore.setSpaces(response);

        // give the 1st letter a color
        // spaces.value.forEach(element => {
        //     element['color'] = '#' + Math.floor(Math.random() * 16777215).toString(16);
        // });
        // Reset form fields after successful submission
        // searchInput.value = null;

        // Close the modal after form submission
    } catch (error) {
        // Handle the error here if needed
        console.error(error);
    }
};


// Redirect to Space Manauls View
const router = useRouter();
const getManuals = async (spaceId, spaceTitle) => {
    try {
        spaceIdStore.spaceId = spaceId;
        spaceIdStore.spaceTitle = spaceTitle;
        router.push({ name: 'manuals' })
    } catch (error) {
        console.log('ERR\N\N', error);
    }
}

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



// watch(() => manualsStore.manuals, async (newValue, oldValue) => {
//     await spacesStore.getSpaces()
// });


</script>

<style>
@media (min-width: 1280px) {
    .xl-custom-grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }
}
</style>
