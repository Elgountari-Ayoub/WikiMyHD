<script setup>
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import SearchInput from '../../components/Partials/SearchInput.vue';
import Swal from 'sweetalert2';


import { ref, watchEffect, onMounted } from 'vue';
import axios from 'axios';


axios.defaults.withCredentials = true;

const title = ref('');
const description = ref('');
const isModalOpen = ref(false);
const modalRef = ref(null);
const randColors = getRandomColor();

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const form = ref({
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
        console.log(form.value);
        const response = await axios.post('http://localhost:8000/api/spaces', {
            title: form.value.title,
            description: form.value.description
        });

        // Handle the response here if needed
        console.log(response.data);

        // Reset form fields after successful submission
        title.value = '';
        description.value = '';


        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'l\'espace ajouté avec succès',
            showConfirmButton: false,
            timer: 1500
        })
        getSpaces();
        // Close the modal after form submission
        closeModal();
    } catch (error) {
        // Handle the error here if needed
        console.error(error);
    }
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


function getRandomColor() {
    const colors = ['#FF0000', '#00FF00', '#0000FF']; // Array of colors excluding white and gray
    const randomIndex = Math.floor(Math.random() * colors.length);
    return colors[randomIndex];
}
function toggleOptions(space) {
    space.showOptions = !space.showOptions;
};
function editSpace(space) {
    // Handle edit functionality for the space
    console.log('Edit:', space);
};
function deleteSpace(space) {
    // Handle delete functionality for the space
    console.log('Delete:', space);
}

// Search

const searchInput = ref(null)
const search = async () => {
    try {
        if(searchInput.value === null){
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

<template>
    <div>
        <DashboardLayout>
            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg mt-14 dark:border-gray-700">
                <!-- Add btn and search -->
                <div class="flex items-center mb-4">
                    <!-- <RouterLink :to="{ name: 'createSpace' }"> -->
                    <button @click="openModal" type="submit"
                        class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                        Ajouter Espace
                    </button>
                    <!-- </RouterLink> -->
                    <!-- <SearchInput /> -->
                    <form class="relative z-10 flex items-center px-24 m-auto" @submit.prevent="search">
                        <!-- <form class="relative z-10 flex items-center m-auto px-52" @submit.prevent="submit"> -->
                        <div class="relative w-full">
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
                        <h1 class="mb-4 text-2xl font-semibold">Add a Space</h1>
                        <form @submit.prevent="submitForm" class="space-y-4 ">
                            <div>
                                <label for="title" class="block mb-2 font-medium text-gray-700">Title</label>
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


                <!-- spaces -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div v-for="space in spaces" :key="space.id"
                        class="flex flex-col items-center justify-around gap-2 rounded h-52 bg-gray-50 dark:bg-gray-800">
                        <p class="px-4 py-3 text-black border rounded-full" :style="{ backgroundColor: space.color }">{{
                            space.title[0] }}</p>
                        <p class="">{{ space.title.slice(0, 20) }}</p>
                    </div>
                </div>

            </div>

        </DashboardLayout>
    </div>
</template>


<style></style>