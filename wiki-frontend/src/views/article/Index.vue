
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <div class="flex items-center" v-if="false">
                <!-- HOME => ALL SPACES PAGE -->
                <RouterLink :to="{ name: 'spaces' }" class="hover:text-gray-900  text-base rounded">
                    <svg aria-hidden="true"
                        class="w-6 h-8 inline text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </RouterLink>
                <!--  -->
                <RouterLink :to="{ name: 'manuals' }" class="hover:text-blue-500 text-base  rounded">
                    {{ spaceIdStore.spaceTitle }}
                    <svg aria-hidden="true"
                        class="w-6 h-8 inline text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </RouterLink>
            </div>

            <div>
                <!-- Add btn and search -->
                <div class="flex items-center mb-4 gap-4">
                    <!-- Add article : most have the space that will have the article-->
                    <!-- <button @click="openModal" type="submit" -->
                    <RouterLink :to="{ name: 'addArticle' }"
                        class="px-4 py-2 w-2/12 text-white text-sm text-center bg-green-500 rounded-md hover:bg-green-600 ">
                        Ajouter Article
                    </RouterLink>
                    <!-- <SearchInput /> -->
                    <!-- md:w-4/12 lg:w-6/12 sm:w-4/12   -->
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

                <LoadingAnimation v-if="articlesStore.articles.length == 0" />
                <div v-else
                    class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl-custom-grid-cols-4 xl:grid-cols-4 gap-4 mb-4">

                    <div v-for="article in articlesStore.articles"
                        class="flex flex-col shadow-sm justify-between gap-2 rounded h-52 bg-gray-50 dark:bg-gray-800">
                        <!-- <span v-if="!spaceIdStore.spaceId" class="font-blod p-2 border-b flex justify-between px-4" -->
                        <span class="font-blod p-2 border-b flex justify-between " :style="{ color: article.color }">
                            <span>{{ article.space.title }}</span>
                            <span class="ml-auto">{{ getCreatorName(article.users) }}</span>
                        </span>

                        <!-- article logo [first letter] -->
                        <RouterLink :to="{ name: 'article', params: { id: `${article.id}` } }"
                            class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
                            :style="{ backgroundColor: article.color }">
                            <span class="text-2xl ">{{ article.title[0].toUpperCase() }}
                            </span>
                        </RouterLink>

                        <div class="flex justify-center p-4 items-center">
                            <!-- article title -->
                            <RouterLink :to="{ name: 'article', params: { id: `${article.id}` } }"
                                class="hover:text-blue-500">{{ article.title.slice(0,
                                    100) }}
                            </RouterLink>

                            <!-- Modal  Edit/Delete article Buttons-->
                            <Dropdown class="ml-auto"
                                v-if='getCreatorId(article.users) == userStore.id || userStore.isAdmin'>
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
                                        <button @click="openEditManualModal(article.id, article.title, article.description)"
                                            class="px-2 py-1 text-white bg-blue-500 rounded-md hover:bg-blue-600 sm:text-sm md:text-base">
                                            Editer
                                        </button>
                                        <button @click="deleteManual(article.id)"
                                            class="px-2 py-1 text-white bg-red-500 rounded-md hover:bg-red-600 sm:text-sm md:text-base">
                                            Supprimer
                                        </button>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>

                <!-- Modal  Add article form-->
                <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center "
                    @click.self="closeModal">
                    <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
                        <h1 class="mb-4 text-2xl font-semibold">Ajouter un article</h1>
                        <form @submit.prevent="addArticle" class="space-y-4 ">
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

                <!-- Modal  Edit article form-->
                <div v-if="isEditManualModalOpen" class="fixed inset-0 z-50 flex items-center justify-center "
                    @click.self="closeEditManualModal">
                    <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
                        <h1 class="mb-4 text-2xl font-semibold">Modifier le article</h1>
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
import { RouterLink, RouterView } from 'vue-router';
// Store [pinia]
import { useUserStore } from '../../stores/user-store';
import { useUsersStore } from '../../stores/users-store';
import { useSpacesStore } from '../../stores/spaces-store';
import { useManualsStore } from '../../stores/manuals-store';
import { useSpaceIdStore } from '../../stores/space-id-store';
import { useArticlesSotre } from '../../stores/articles-store';

const userStore = useUserStore();
const manualsStore = useManualsStore();
const spacesStore = useSpacesStore();
const spaceIdStore = useSpaceIdStore();
const articlesStore = useArticlesSotre();

axios.defaults.withCredentials = true;

watch(() => spaceIdStore.spaceId, (newValue, oldValue) => {
    getManuals();
});

const spaceId = ref();
const getManuals = onMounted(async () => {
    articlesStore.getArticles();
    if (spaceIdStore.spaceId) {
        await manualsStore.getManualsBySpace(spaceIdStore.spaceId)
            .then(async (response) => {
                await spacesStore.getSpaces();

            }).catch(error => {
                console.log(error);
            });
    }
    else {
        articlesStore.getArticles();
        await manualsStore.getManuals().then(async (response) => {
            await spacesStore.getSpaces();
        });
    }

});

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
    let creatorName = '';
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

// Add article model
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

// Add article
const addArticle = async () => {
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
    // show a sweet alert for the confirmation
    try {
        const response = await axios.delete(`/api/manuals/${manualId}`);
        getManuals();
        // Swal.fire({
        //     position: 'top-end',
        //     icon: 'success',
        //     title: 'le manuel a été supprimé avec succès',
        //     showConfirmButton: false,
        //     timer: 1500

        // })

    } catch (err) {
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'Échec de la suppression, actualisez la page et réessayez',
            showConfirmButton: false,
            timer: 1500
        })
    }
}

// Search
const searchInput = ref(null)
const search = async () => {
    try {
        if (!searchInput.value) {
            console.log('empty');
            if (manualsStore.manuals.length === 0) {
                manualsStore.getManuals();
            }
            return;
        }
        const response = await axios.get(`/api/manuals/search/${searchInput.value}`);
        if (response.data.length > 0) {
            manualsStore.manuals = response.data
            manualsStore.manuals.forEach(element => {
                element.color = "#" + Math.floor(Math.random() * 16777215).toString(16);
            })
            return;
        }
        manualsStore.manuals = [];

    } catch (error) {
        // Handle the error here if needed
        console.error(error);
    }
};



// HELPERS-------------------------
// Edit Manual Modal
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

const getImageUrl = (photo) => {
    const baseUrl = "http://localhost:8000/storage/";
    return baseUrl + photo; // Concatenating the base URL and the photo variable
}

</script>

<style></style>