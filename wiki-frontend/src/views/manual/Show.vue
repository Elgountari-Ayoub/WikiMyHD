
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <div v-if="manualStore">
                <!-- Home => Spaces link-->
                <RouterLink :to="{ name: 'spaces' }" class="hover:text-gray-900  text-base rounded">
                    <svg aria-hidden="true"
                        class="w-5 h-5 inline text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </RouterLink>
                <!-- Space link-->
                <RouterLink v-if="manualStore.space" :to="{ name: 'space', params: { id: `${manualStore.space.id}` } }"
                    class="hover:text-blue-500 text-base  rounded">
                    {{ manualStore.space.title }}
                    <svg aria-hidden="true"
                        class="w-5 h-5 inline text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </RouterLink>
                <!-- Manual link -->
                <RouterLink :to="{ name: 'manual', params: { id: `${manualStore.id}` } }"
                    class="hover:text-blue-500 text-base  rounded">
                    {{ manualStore.title }}
                    <svg aria-hidden="true"
                        class="w-5 h-5 inline text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </RouterLink>
            </div>


            <div>
                <!-- Add btn and search -->
                <div class="flex items-center mb-4 gap-4">
                    <!-- Add article : must have the space that will have the article -->
                    <button @click="toAddArticle()"
                        class="px-4 py-2 w-2/12 text-white text-sm text-center bg-green-500 rounded-md hover:bg-green-600 ">
                        Ajouter Article
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

                <LoadingAnimation v-if="articlesStore.articles.length == 0" />
                <div v-else
                    class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl-custom-grid-cols-4 xl:grid-cols-4 gap-4 mb-4 ">

                    <div v-for="article in filteredArticles()"
                        class="flex flex-col shadow-md justify-between gap-2 rounded h-60 bg-gray-50 dark:bg-gray-800 p-4 pt-16 ">


                        <!-- LOGO -->
                        <button @click="toArticle(article.id)"
                            class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
                            :style="{ backgroundColor: article.color }">
                            <span class="text-2xl ">{{ article.title[0].toUpperCase() }}
                            </span>
                        </button>

                        <div class="flex justify-center items-center ">
                            <!-- Title -->
                            <button @click="toArticle(article.id)" class="font-bold hover:text-blue-500 text-ellipsis"
                                :title="article.title">
                                {{ article.title.length > 20 ? article.title.slice(0, 20) + '...' : article.title }}
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

                        <div class="flex -justify-between text-sm">
                            <span class="mr-auto text-sm">By {{ getCreatorName(article.users) }}</span>

                            <!-- share article -->
                            <i v-if="userStore.isAdmin == true" class="ri-share-line cursor-pointer"
                                @click="openShareModal(article.id)"></i>
                            <!-- Share article with users Modal-->
                            <div v-show="isShareModalOpen"
                                class="fixed z-10 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
                                <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
                                    <div class="flex flex-col items-start justify-between p-6 space-y-4 w-96">
                                        <div class="text-lg font-bold text-gray-900 self-center">Users</div>
                                        <div class="w-full">
                                            <label for="spaces"
                                                class="block text-sm font-medium text-gray-700 mb-1"></label>
                                            <input type="text" v-model="searchQuery"
                                                placeholder="rechercher des utilisateurs"
                                                class="w-full text-sm px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md mb-2" />
                                            <select multiple v-model="selectedUsers" id="spaces" name="spaces[]"
                                                class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                                                <option v-for="user in filteredUsers" :value="user.id" :key="user.id">{{
                                                    user.name
                                                }}
                                                </option>
                                            </select>
                                            <div class="flex justify-between">
                                                <button class="p-2 mt-2 bg-blue-600 hover:bg-blue-700 text-white rounded"
                                                    @click="shareArticleWithUsers()">Soumettre</button>

                                                <button class="p-2 mt-2 bg-red-600 hover:bg-red-700 text-white rounded"
                                                    @click="closeShareModal()">Fermer</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Author -->
                        <!-- <span class="mr-auto text-sm">By {{ getCreatorName(article.users) }}</span> -->
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

import { ref, watchEffect, onMounted, watch, computed } from 'vue';
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
import { useUsersStore } from '../../stores/users-store';


axios.defaults.withCredentials = true;

const userStore = useUserStore();
const manualsStore = useManualsStore();
const manualStore = useManualStore();
const spaceStore = useSpaceStore();
const articlesStore = useArticlesSotre();
const paramsStore = useParamsStore();

const route = useRoute();
const router = useRouter();

// manualsStore.clearManuals();
// spaceStore.clearSpace();
articlesStore.clearArticles();

const getArticles = onMounted(async () => {
    if (paramsStore.getManualId()) {
        await manualStore.getManual(paramsStore.getManualId());
        articlesStore.getArticlesByManual(paramsStore.getManualId());
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

watch(() => spaceStore.id, (newValue, oldValue) => {
    getArticles();
});

const isModalOpen = ref(false);
const modalRef = ref(null);

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
    Swal.fire({
        title: "Êtes-vous sûr(e) ?",
        text: "Une fois supprimé, cet élément ne pourra pas être récupéré !",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: 'Confirmer',
        denyButtonText: `Annuler`,
    }).then(async (result) => {
        if (result.isConfirmed) {
            // show a sweet alert for the confirmation
            await axios.delete(`/api/articles/${articleId}`).then(response => {
                getArticles();
            }).catch(error => {
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Échec de la suppression, actualisez la page et réessayez',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
            )
        }
    })
}

// Search

// Start Search---------------------------------------------
const searchInput = ref('')
watch(searchInput, () => {
    filteredArticles();
});

const filteredArticles = () => {
    let isEmpty = /^\s*$/.test(searchInput.value);
    console.log(isEmpty);
    if (isEmpty) {
        return articlesStore.articles
    }
    else {
        console.log(searchInput.value);
        return articlesStore.articles.filter(article => {
            return article.title.toLowerCase().includes(searchInput.value.toLowerCase()) || article.content.toLowerCase().includes(searchInput.value.toLowerCase());
        });
    }
};

// End Search---------------------------------------------

// const searchInput = ref(null)
// const search = async () => {
//     try {
//         if (!searchInput.value) {
//             if (manualsStore.manuals.length === 0) {
//                 manualsStore.getArticles();
//             }
//             return;
//         }
//         const response = await axios.get(`/api/manuals/search/${searchInput.value}`);
//         if (response.data.length > 0) {
//             manualsStore.manuals = response.data
//             manualsStore.manuals.forEach(element => {
//                 element.color = "#" + Math.floor(Math.random() * 16777215).toString(16);
//             })
//             return;
//         }
//         manualsStore.manuals = [];

//     } catch (error) {
//         // Handle the error here if needed
//         console.error(error);
//     }
// };

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

// Start [Share Articles with users] Section

const selectedUsers = ref([]);
const searchQuery = ref('');
const usersStore = useUsersStore();
const isShareModalOpen = ref(false);

const articleId = ref(null);
function openShareModal(article_id) {
    isShareModalOpen.value = true;
    articleId.value = article_id
}
function closeShareModal() {
    isShareModalOpen.value = false;
    articleId.value = null;
    searchQuery.value = '';
    selectedUsers.value = [];
}

const filteredUsers = computed(() => {
    return usersStore.users.filter(user => {
        if (user.status === 1 && user.role != 'admin') {
            return user.name.toLowerCase().includes(searchQuery.value.toLowerCase());
        }
    });
});

const shareArticleWithUsers = async () => {
    if (selectedUsers.value.length > 0) {
        // return;
        await axios.post('/api/assignUsersToArticle', {
            article_id: articleId.value,
            users: selectedUsers.value,
        }).then(response => {
            closeShareModal();
            console.log(response);
            getArticles()
        }).catch(error => {
            console.log(error);
        })
    }


}

// End [Share Articles with users] Section

</script>
  
<style></style>