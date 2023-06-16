
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <div class="relative">
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

                <LoadingAnimation v-if="articlesStore.articles.length == 0" />
                <div v-else
                    class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl-custom-grid-cols-4 xl:grid-cols-4 gap-4 mb-4 min-h-[60vh]">

                    <div v-if="!searchInput" v-for="article in articlesStore.articles"
                        class="flex flex-col shadow-md justify-between gap-2 rounded h-60 bg-gray-50 dark:bg-gray-800 p-4 ">

                        <div class="border-b flex gap-2 text-sm flex-start pb-2">
                            {{ article.space.title }} <strong>:</strong> {{ article.manual.title }}
                        </div>

                        <!-- LOGO -->
                        <button @click="toArticle(article.id)"
                            class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
                            :style="{ backgroundColor: article.color }">
                            <span class="text-2xl ">{{ article.title[0].toUpperCase() }}
                            </span>
                        </button>

                        <div class="flex justify-center items-center ">
                            <!-- Title -->
                            <button @click="toArticle(article.id)" class="font-bold hover:text-blue-500">
                                <span>{{ article.title.slice(0,
                                    100) }}
                                </span>
                            </button>

                            <!-- Btns -->
                            <div class="ml-auto flex gap-4"
                                v-if='getCreatorId(article.users) == userStore.id || userStore.isAdmin'>
                                <button @click="toEditArticle(article.id)"
                                    class="text-lg text-blue-500 rounded-md hover:text-blue-700 sm:text-sm md:text-base">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <button @click="deleteArticle(article.id)"
                                    class="text-lg text-red-500 rounded-md hover:text-red-700 sm:text-sm md:text-base">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Author -->
                        <span class="mr-auto text-sm">By {{ getCreatorName(article.users) }}</span>
                    </div>
                    <div v-else v-for="article in filteredArticles"
                        class="flex flex-col shadow-md justify-between gap-2 rounded h-60 bg-gray-50 dark:bg-gray-800 p-4 ">

                        <div class="border-b flex gap-2 text-sm flex-start pb-2">
                            {{ article.space.title }} <strong>:</strong> {{ article.manual.title }}
                        </div>

                        <!-- LOGO -->
                        <button @click="toArticle(article.id)"
                            class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
                            :style="{ backgroundColor: article.color }">
                            <span class="text-2xl ">{{ article.title[0].toUpperCase() }}
                            </span>
                        </button>

                        <div class="flex justify-center items-center ">
                            <!-- Title -->
                            <button @click="toArticle(article.id)" class="font-bold hover:text-blue-500">
                                <span>{{ article.title.slice(0,
                                    100) }}
                                </span>
                            </button>

                            <!-- Btns -->
                            <div class="ml-auto flex gap-4"
                                v-if='getCreatorId(article.users) == userStore.id || userStore.isAdmin'>
                                <button @click="toEditArticle(article.id)"
                                    class="text-lg text-blue-500 rounded-md hover:text-blue-700 sm:text-sm md:text-base">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <button @click="deleteArticle(article.id)"
                                    class="text-lg text-red-500 rounded-md hover:text-red-700 sm:text-sm md:text-base">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Author -->
                        <span class="mr-auto text-sm">By {{ getCreatorName(article.users) }}</span>
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
import { RouterView, useRoute, useRouter } from 'vue-router';
// Store [pinia]
import { useUserStore } from '../../stores/user-store';

import { useSpacesStore } from '../../stores/spaces-store';
import { useSpaceStore } from '../../stores/space-store';

import { useManualsStore } from '../../stores/manuals-store';
import { useManualStore } from '../../stores/manual-store';

import { useArticlesSotre } from '../../stores/articles-store';
import { useParamsStore } from '../../stores/params-store';
import { computed } from '@vue/reactivity';

axios.defaults.withCredentials = true;

const userStore = useUserStore();
const articlesStore = useArticlesSotre();
const paramsStore = useParamsStore();

const route = useRoute();
const router = useRouter();

articlesStore.clearArticles();
const getArticles = onMounted(async () => {
    articlesStore.getArticles();
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
    space_id: null,
    manual_id: null,
    title: null,
    content: null,
})

// ARTICLE CRUD + SEARCH

// delete Article
const deleteArticle = async (articleId) => {
    // show a sweet alert for the confirmation
    try {
        const response = await axios.delete(`/api/articles/${articleId}`);
        getArticles();


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

// Search---------------------------------------------

const searchInput = ref(null)
watch(searchInput, () => {
    let isEmpty = /^\s*$/.test(searchInput.value);
    if (isEmpty) {
        searchInput.value = null;
    }
});

const filteredArticles = computed(() => {
    return articlesStore.articles.filter(article => {
        return article.title.toLowerCase().includes(searchInput.value.toLowerCase()) || article.content.toLowerCase().includes(searchInput.value.toLowerCase());
    });
});
// Search---------------------------------------------

// HELPERS-------------------------
// Edit Article Modal

const toArticle = (articleId) => {
    paramsStore.articleId = articleId;
    window.open('/article', '_blank');
}

const toEditArticle = (articleId) => {
    paramsStore.articleId = articleId
    router.push({ name: 'editArticle' })

};
const toAddArticle = () => {
    router.push({ name: 'addArticle' })

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