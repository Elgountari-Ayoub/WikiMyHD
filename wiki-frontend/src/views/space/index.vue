
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <LoadingAnimation v-if="spaces.length == 0" />
            <div v-else>


                <!-- Add btn and search -->
                <div class="flex items-center mb-4 gap-4" >
                    <!-- <RouterLink :to="{ name: 'createSpace' }"> -->
                    <button  v-if='userStore.isAdmin' @click="openModal" type="submit"
                        class="px-4 py-2 w-40 text-white text-sm bg-green-500 rounded-md hover:bg-green-600 ">
                        Ajouter Espace
                    </button>
                    <!-- </RouterLink> -->
                    <!-- <SearchInput /> -->
                    <form class="relative z-10 flex items-center md:px-16 lg:px-24 xlg:px-32 2xl:px-40 sm:px-2  w-full m-auto"
                        @submit.prevent="search">
                        <!-- <form class="relative z-10 flex items-center m-auto px-52" @submit.prevent="submit"> -->
                        <div class="relative w-full m-auto">
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

                <!-- Modal  Add Space form-->
                <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center "
                    @click.self="closeModal">
                    <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
                        <h1 class="mb-4 text-2xl font-semibold">Ajouter un espace</h1>
                        <form @submit.prevent="submitForm" class="space-y-4 ">
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


                <!-- spaces -->
                <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <div v-for="space in spaces" :key="space.id"
                        class="flex flex-col  rounded-md justify-between gap-2 rounded h-52 bg-gray-100 dark:bg-gray-800">
                        <p class="px-4 py-3 text-black border rounded-full m-auto w-fit"
                            :style="{ backgroundColor: space.color }">{{
                                space.title[0] }}</p>
                        <div class="flex justify-center px-8 py-2 items-center">
                            <p class="">{{ space.title.slice(0, 20) }}</p>
                            <!-- <p class="font-bold text-4xl">:</p> -->


                            <!-- Modal  Edit/Delete Space Buttons-->
                            <Dropdown class="ml-auto" v-if='userStore.isAdmin'>
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

import { ref, watchEffect, onMounted } from 'vue';
import axios from 'axios';
import { RouterView } from 'vue-router';
import LoadingAnimation from '../../components/global/LoadingAnimation.vue';


axios.defaults.withCredentials = true;

const userStore = useUserStore();

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

const spaces = ref([]);
const getSpaces = onMounted(async () => {
    try {
        const response = await axios.get('http://localhost:8000/api/spaces');
        spaces.value = response.data;

        // give the 1st letter a color
        spaces.value.forEach(element => {
            element['color'] = '#' + Math.floor(Math.random() * 16777215).toString(16);
        });

    } catch (error) {
        console.error(error);
    }
});


// Add Space
const submitForm = async () => {
    try {
        const response = await axios.post('http://localhost:8000/api/spaces', {
            title: form.value.title,
            description: form.value.description
        });

        // Handle the response here if needed
        console.log(response.data);

        // Reset form fields after successful submission
        form.value.is = '';
        form.value.title = '';
        form.value.description = '';


        Swal.fire({
            position: 'top-end',
            icon: 'success',
            width: '25rem',
            title: 'l\'espace ajouté avec succès',
            showConfirmButton: false,
            timer: 1500,
        })
        getSpaces();
        // Close the modal after form submission
        closeModal();
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

// Edit Space


const editSpace = async () => {
    console.log(form.value.id);
    console.log(form.value.title);
    console.log(form.value.description);
    try {
        const response = await axios.post(`http://localhost:8000/api/spaces/${form.value.id}`, {
            _method: 'PUT',
            title: form.value.title,
            description: form.value.description
        });

        // Handle the response here if needed
        console.log(response.data);

        // Reset form fields after successful submission
        form.value.is = '';
        form.value.title = '';
        form.value.description = '';


        Swal.fire({
            position: 'top-end',
            icon: 'success',
            width: '25rem',
            title: 'l\'espace mis à jour avec succès',
            showConfirmButton: false,
            timer: 1500,
        })
        getSpaces();
        // Close the modal after form submission
        closeEditSpaceModal();
    } catch (error) {
        // Handle the error here if needed
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'échec de la mise à jour de l\'espace',
            showConfirmButton: false,
            timer: 1500
        })
        console.error(error);
    }


    // Show the Edit form
    // store the new space data into  a from just like the add
    //send an update request like the add, but with put request
    // make sure u set the api route
}


// delete Space
const deleteSpace = async (spaceId) => {
    // show a sweet alert for the confirmation
    try {
        console.log(spaceId);
        const response = await axios.delete(`/spaces/${spaceId}`);
        getSpaces();

        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'L\'espace a été supprimé avec succès',
            showConfirmButton: false,
            timer: 1500

        })

    } catch (err) {
        console.log(err);
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'Échec de la suppression, actualisez la page et réessayez',
            showConfirmButton: false,
            timer: 1500
        })
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


function getRandomColor() {
    const colors = ['#FF0000', '#00FF00', '#0000FF']; // Array of colors excluding white and gray
    const randomIndex = Math.floor(Math.random() * colors.length);
    return colors[randomIndex];
}



function toggleOptions(space) {
    space.showOptions = !space.showOptions;
};
// function editSpace(space) {
//     // Handle edit functionality for the space
//     console.log('Edit:', space);
// };
// function deleteSpace(space) {
//     // Handle delete functionality for the space
//     console.log('Delete:', space);
// }

// Search


const searchInput = ref(null)
const search = async () => {
    try {
        if (searchInput.value === null) {
            return;
        }
        const response = await axios.get(`http://localhost:8000/api/spaces/search/${searchInput.value}`);

        // Handle the response here if needed
        console.log(response.data);
        spaces.value = response.data;

        // give the 1st letter a color
        spaces.value.forEach(element => {
            element['color'] = '#' + Math.floor(Math.random() * 16777215).toString(16);
        });
        // Reset form fields after successful submission
        // searchInput.value = null;

        // getSpaces();
        // Close the modal after form submission
    } catch (error) {
        // Handle the error here if needed
        console.error(error);
    }
};
</script>

<style></style>